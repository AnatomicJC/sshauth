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

## How to deploy the ssh config to enable this feature ? ##

I wrote an ansible ssh-config role for this: https://github.com/AnatomicJC/ansible-ssh-config

## License ##

This software is released under the WTFPL
Do What the Fuck You Want to Public License http://www.wtfpl.net/about/
