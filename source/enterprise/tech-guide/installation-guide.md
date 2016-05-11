---
layout: default
title: Shopware Enterprise Dashboard Installation Guide
github_link: enterprise/tech-guide/installation-guide.md
indexed: false
---

This guide will cover the Shopware Enterprise Dashboard installation process

<div class="toc-list"></div>

## System Requirements

The following system libraries/applications are required to install and run the Shopware Enterprise Dashboard:

##### Requirements

- Linux operating system
- PHP 5.6 or later (PHP 7 recommended) including CLI support and mod_rewrite
- Apache2 web server
- MySQL
- Ansible Version 2.0+
- Beanstalk Version 1.4+, with a max job size of at least `65533 Byte`
- Supervisor 3.0+ 
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

To help you with the more abstract requirements, we show you here a possible way to setup the background processes.

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

After following [the shopware server guide](/enterprise/tech-guide/shopware-server-configuration-guide) you should now be able to connect without a password to a shopware server by executing

````
sudo -i -u edb-supervisor ssh edb-deploy@_HOST_
````
Congratulations you now set up the background processes with a user that is capable of connecting to Shopware servers through a key file. 