---
layout: default
title: Shopware Enterprise Dashboard Installation Guide
github_link: enterprise/tech-guide/installation-guide.md
indexed: false
---

This guide will cover the Shopware Enterprise Dashboard installation process

<div class="toc-list"></div>

## System Requirements

The following system libraries/applications are required to install and run the Shopware Enterprise Dashboard.

Since the Enterprise Dashboard not only has a webfrontend that is served through a webserver but also uses background processes for potentially long running actions we need a little more infrastructure then the typical PHP application.

##### Requirements

- Linux operating system
- PHP 5.6 or later (PHP 7 recommended) including CLI support, with extensions gd, zip, curl, intl, pdo and pdo_mysql
- Apache2 web server including mod_rewrite
- MySQL
- Ansible Version 2.0.*
- Beanstalk Version 1.4+, with a max job size of at least `65533 Byte`
- Supervisor 3.0+ with configured RPC access
- A distinct shell user for SSH access to the connected shop servers 
- The EDB requires to be run from the root of an apache host, prepare a domain
- Set PHP *(cli & apache2)* and MySQL timezone to UTC

## Installing the Shopware Enterprise Dashboard

- Download the installation package
- Extract the archive to your web server's content folder
- Enable web server access to *_APPLICATION_/web* 
- Execute `php setup.phar` through the command line
- Follow the instructions
- Remember to symlink the Supervisor configuration in *_APPLICATION_/supervisord/edb.conf.dist* to enable the background processes

## HowTo: Setup the background processes on Ubuntu 14.04

> Important: This is intended to help you understand and not a fully secured production setting.

Since you should already be familiar with webserver setup from your past Shopware experience, we are showing you here the Enterprise Dashboard specific background process setup.

Install [supervisor](http://supervisord.org/installing.html#installing-to-a-system-with-internet-access), [beanstalkd](https://www.vultr.com/docs/setup-beanstalkd-and-beanstalk-console-on-ubuntu-14) and [ansible](http://docs.ansible.com/ansible/intro_installation.html#latest-releases-via-apt-ubuntu).

````shell
apt-get install software-properties-common
apt-add-repository ppa:ansible/ansible
apt-get update
apt-get install supervisor
apt-get install beanstalkd
apt-get install ansible
````

First we enable the RPC access for supervisor. Start editing the file. 

````shell
vi /etc/supervisor/init.d/rpc.conf
````

And paste this content:

````ini
[inet_http_server]
port = *:9001
username = edb-supervisor-rpc
password = edb-supervisor-rpc
````

This binds the Port 9001 to the supervisor RPC-API. To enable the configuration we need to restart the supervisor service.
 
````shell
service supervisor restart
````

Now the EDB has access to the current status of it's background processes.

Next we create a shell user under which the background processes will be executed

````shell
useradd edb-supervisor -s /bin/bash -m
````
> Notice: Although it is not necessary to specify a shell for the deployment user, I found it very helpful for debugging purposes.

Now create a SSH key *without a password* for the edb-deploy user. Since it is an interactive command, make sure to save the key in `home/edb-supervisor/.ssh/example_ssh`  
 
````shell
ssh-keygen -t rsa -b 4096 -C "your_email@example.com"
chown edb-supervisor:edb-supervisor -R home/edb-supervisor/.ssh
chmod 0600 home/edb-supervisor/.ssh/example_ssh
````

And register the ssh key with the ssh agent

````shell
eval "$(ssh-agent -s)"
ssh-add home/edb-supervisor/.ssh/example_ssh
````

Now we link the Enterprise Dashboards supervisor config to the supervisor service and restart it so the workers are executed.

```shell
ln -s _APPLICATION_PATH_/supervisord/edb.conf.dist /etc/supervisor/conf.d/edb.conf
supervisorctl reload
```
> Notice: Some supervisor configurations may only allow `*.conf` files to be enabled.

After following [the shopware server guide](/enterprise/tech-guide/shopware-server-configuration-guide) you should now be able to connect without a password to a shopware server by executing

````
sudo -i -u edb-supervisor ssh edb-deploy@_HOST_
````
> Notice: you should try this for all new servers, because it will also register the new Shopware Server as a known host.

Congratulations you now set up the background processes with a user that is capable of connecting to Shopware servers through a key file. 