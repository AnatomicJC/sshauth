<?php
$server_user = $_POST['user'];
$server_hostname = $_POST['hostname'];

$mysqli = new mysqli(
  $_ENV['MYSQL_HOST'],
  $_ENV['MYSQL_USER'],
  $_ENV['MYSQL_PASSWORD'],
  $_ENV['MYSQL_DATABASE']);

$query = "-- Search per server
SELECT public_key FROM ssh_keys
INNER JOIN link_servers ON ssh_keys.ssh_user = link_servers.ssh_user_username
WHERE link_servers.server_user = '${server_user}'
AND link_servers.server_hostname = '${server_hostname}'
AND (
  (CURDATE() BETWEEN link_servers.valid_from AND link_servers.valid_until)
  OR
  (link_servers.valid_until = '0000-00-00 00:00:00' AND link_servers.valid_from = '0000-00-00 00:00:00')
)

UNION

-- Search per servers group
SELECT public_key FROM ssh_keys
INNER JOIN link_server_groups ON ssh_keys.ssh_user = link_server_groups.ssh_user_username
INNER JOIN servers_groups_link ON link_server_groups.groupname = servers_groups_link.server_groupname
WHERE link_server_groups.server_user = '${server_user}'
AND servers_groups_link.server_hostname = '${server_hostname}'
AND (
  (CURDATE() BETWEEN link_server_groups.valid_from AND link_server_groups.valid_until)
  OR
  (link_server_groups.valid_until = '0000-00-00 00:00:00' AND link_server_groups.valid_from = '0000-00-00 00:00:00')
)

UNION

-- Search Masters
SELECT public_key FROM ssh_keys
INNER JOIN link_server_groups ON ssh_keys.ssh_user = link_server_groups.ssh_user_username
WHERE link_server_groups.groupname = 'Masters'
AND (
  (CURDATE() BETWEEN link_server_groups.valid_from AND link_server_groups.valid_until)
  OR
  (link_server_groups.valid_until = '0000-00-00 00:00:00' AND link_server_groups.valid_from = '0000-00-00 00:00:00')
)";

$result = $mysqli->query($query);

while($row = $result->fetch_assoc()) {
    echo $row['public_key'] . PHP_EOL;
}

?>
