---
layout: default
title: Shopware Server Configuration Guide
github_link: enterprise/tech-guide/shopware-server-configuration-guide.md
indexed: false
---

This guide describes how you need to configure your Shopware servers in order to be compatible with the Enterprise Dashboard.
<div class="toc-list"></div>

## System Requirements

The following system libraries/application are required to install and run Shopware through the Enterprise Dashboard.

##### Shopware Host

The basic requirements are the same as for Shopware itself. [Please review this guide for details.](https://developers.shopware.com/sysadmins-guide/system-requirements)

##### RAM / Memory

Make sure the server has enough memory for deployment tasks like `mysqldump` and `unzip`. It is therefore recommended to set an appropriate SWAP size.

##### Ansible Node Setup

The Enterprise Dashboard uses [Ansible](http://www.ansible.org) for it's client communication. Although Ansible's logic is mostly executed on it's host, there are a few required packages that must be met

* Python 2.*, with python-simplejson - [docs](http://docs.ansible.com/ansible/intro_installation.html#managed-node-requirements)
* Mysql Tools, `mysql` and `mysqldump`, as well as `MySQLdb` - [docs](http://docs.ansible.com/ansible/mysql_db_module.html#requirements-on-host-that-executes-module)
* GNU `tar` needs to support the `--exclude=FILE` option - [docs](https://www.gnu.org/software/tar/)
* `unzip` needs to be installed - [docs](http://linux.about.com/od/commands/l/blcmdl1_unzip.htm)

##### MySQL Deployment User Setup

* Username: *edb-deploy*
* Password: *edb-deploy*
* Needs privileges to create databases

##### Unix Deployment User Setup

* Username: *edb-deploy*
* Needs a home directory
* Needs to be part of the web server group
* Web server needs to be part of edb-deploy group
* Needs access to the */tmp* directory
* Needs to own the directory directory where new shops can be installed

## HowTo: Setup on Ubuntu 14.04

This HOWTO should help you understand the requirements postulated above, but be aware that based on your specific
operating system and version the commands you actually have to execute may differ vastly. We assume that you
have already setup a system that is capable of executing Shopware and has an configured apache host.

First you create a MySQL user that has full access from the current host:

````shell
mysql -u _YOUR_USER_ -p _YOUR_PASSWORD_ -e "CREATE USER 'edb-deploy'@'localhost' IDENTIFIED BY 'edb-deploy'" 
````
> Notice: MySQL defaults to grant newly created users all rights. If you want to reduce this, please make sure that the user is still able to create tables.


Now you create the edb-deploy shell user and add him to the apache group (in this case `www-data`). 

````shell
useradd edb-deploy -s /bin/bash -m
usermod -a -G edb-deploy www-data
````
> Notice: Although it is not necessary to specify a shell for the deployment user, I found it very helpful for debugging purposes.

We need to add the Apache users group to the edb-deploy user

````shell
usermod -a -G www-data edb-deploy
````

After that we can securely set the ownership of the apache host directory to the deploy user, in this case simply `/var/www`
 
````shell
chown -R edb-deploy:edb-deploy /var/www
````

Now add the **public ssh key** of the Enterprise Dashboards background process user to the edb-deploy user. To enable SSH access from the Enterprise Dashboard.

````shell
touch /home/edb-deploy/.ssh/authorized_keys
echo "YOUR PUBLIC KEY" >> /home/edb-deploy/.ssh/authorized_keys
chown edb-deplpoy /home/edb-deploy/.ssh/authorized_keys
chmod 0600 /home/edb-deploy/.ssh/authorized_keys

````
Congratulations now the server is set up correctly.