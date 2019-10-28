sshauth
-------

This repository contains sshauth service code.

## Requirements ##

 - apache2
 - mysql-server
 - phpmyadmin

## What is this ? ##

The aim of this service is to manage servers SSH accesses in one place. 
As an example, If you want to enable user Kevin to access all Africa servers, no more need to copy Kevin public ssh key on ***each*** africa server. Open phpmyadmin, add Kevin user to africa group and Kevin will be authorized to connect to Africa servers, simple and easy.
You can set a "valid until" param to authorize Kevin to access to the servers for one week. After one week, Africa server access will be revoked.

## Why PHPMyAdmin ??? ##

I have to time to write a web interface.

## How it works ? ##

On recent SSH releases, there is a new `AuthorizedKeysCommand` option in /etc/ssh/sshd_server. This option allows sshd daemon to use a script, program or anything else to authorize SSH access. The script has to return the public ssh keys who are allowed to connect.

I provide in this repo a `sshauth` script who will request a "sshauth" service. The "sshauth" service is provided by the `index.php` file. `index.php` requests a database and return allowed public ssh keys.

## SQL Tables ##

* ssh_users: your users
* ssh_keys: public SSH keys of your users
* servers: your server's hostnames
* server_groups: groupnames of your servers (South Africa, Europe, Office, etc)
* server_users: local users on your servers (root, user1, user2)
* servers_groups_link: Intermediate table where you can assign a server hostname to one or several groups
* link_servers: It is the place where you allow users to connect to a user of a server, with end of validity
* link_server_groups: It is the place where you allow users to connect to group of servers, with end of validity

## How to start ? ##

1. fill `ssh_users` table with name of employees allowed to connect to your servers through SSH. Example: Bob, Alice, John Doe, etc.
2. fill the `ssh_keys` table with public key of your users, you can associate as many keys as you want per user.
3. fill the `servers` table with hostname of your servers: server1, server2, etc.
4. fill the `server_users` table with users defined on your servers: root, user1, user2, etc.
5. fill the `server_groups` table with Group names of servers. By example: Office for office servers, Europe for european servers, Webservers, etc.
6. With the `servers_groups_link` table, you can associate a server hostname to a group of servers
7. With the `link_servers` table, you will be able to link a user (Bob or Alice) to a specific user on a server (user1@stuff.domain.ltd)
8. With the `link_server_groups`, you will be able to link a user (Bob or Alice) to a specific user on a group of servers (by example: any user1 of american servers)

Special tip: In `server_groups` table, create a `Masters` group, then associate any user to this group in `link_server_groups` table. Your user will be allowed on all servers :-)

## How to deploy the ssh config to enable this feature ? ##

I wrote an ansible ssh-config role for this: https://github.com/AnatomicJC/ansible-ssh-config

## License ##

This software is released under the WTFPL
Do What the Fuck You Want to Public License http://www.wtfpl.net/about/
