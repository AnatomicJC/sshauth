<?php
$server_user = $_POST['user'];
$server_hostname = $_POST['hostname'];

// https://stackoverflow.com/questions/60174/how-can-i-prevent-sql-injection-in-php

$db = new PDO(
  sprintf('mysql:dbname=%s;host=%s;charset=utf8', $_ENV['MYSQL_DATABASE'], $_ENV['MYSQL_HOST']),
  $_ENV['MYSQL_USER'],
  $_ENV['MYSQL_PASSWORD']);

$db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$query = "-- Search per server
SELECT public_key FROM ssh_keys
INNER JOIN link_servers ON ssh_keys.ssh_user = link_servers.ssh_user_username
WHERE link_servers.server_user = :server_user_1
AND link_servers.server_hostname = :server_hostname_1
AND (
  (CURDATE() BETWEEN link_servers.valid_from AND link_servers.valid_until)
  OR
  (link_servers.valid_until IS NULL AND link_servers.valid_from IS NULL)
)

UNION

-- Search per servers group
SELECT public_key FROM ssh_keys
INNER JOIN link_server_groups ON ssh_keys.ssh_user = link_server_groups.ssh_user_username
INNER JOIN servers_groups_link ON link_server_groups.groupname = servers_groups_link.server_groupname
WHERE link_server_groups.server_user = :server_user_2
AND servers_groups_link.server_hostname = :server_hostname_2
AND (
  (CURDATE() BETWEEN link_server_groups.valid_from AND link_server_groups.valid_until)
  OR
  (link_server_groups.valid_until IS NULL AND link_server_groups.valid_from IS NULL)
)

UNION

-- Search Masters
SELECT public_key FROM ssh_keys
INNER JOIN link_server_groups ON ssh_keys.ssh_user = link_server_groups.ssh_user_username
WHERE link_server_groups.groupname = 'Masters'
AND (
  (CURDATE() BETWEEN link_server_groups.valid_from AND link_server_groups.valid_until)
  OR
  (link_server_groups.valid_until IS NULL AND link_server_groups.valid_from IS NULL)
)";

$stmt = $db->prepare($query);
$stmt->execute(
  array(
    'server_user_1' => $server_user,
    'server_hostname_1' => $server_hostname,
    'server_user_2' => $server_user,
    'server_hostname_2' => $server_hostname,
  )
);

foreach($stmt as $row) {
    echo $row['public_key'] . PHP_EOL;
}

?>
