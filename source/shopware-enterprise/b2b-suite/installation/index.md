---
layout: default
title: Installation Guide
github_link: b2b-suite/installation/index.md
indexed: true
tags: [b2b, installation]
menu_title: Installation Guide
menu_order: 3
group: Shopware Enterprise
subgroup: B2B-Suite
---

<div class="toc-list"></div>

## General

We provide two kinds of virtualization, a vagrant and a docker based solution. Our developers use the docker containers mainly. These containers are also used in our continuous integration process. The supported functions are for both systems equal if the host systems is based on Linux. Instead of testing windows in combination with docker we evaluated the vagrant setup.
 
If you want to install the B2B-Suite for production environment your system must fit with the defined requirements from the [Shopware core](https://developers.shopware.com/sysadmins-guide/system-requirements/).

### Minimum Requirements

The B2B Suite is based on the minimum requirements of the Shopware core.

#### For Shopware 5
These requirements apply from **B2B Suite 3.1.0 and above**.

* Shopware 5.6
* PHP 7.2
* MySQL 5.7
* No IE 10 support

**Like in the Shopware 5 core PHP 7.2.20, 7.3.7, 7.4.14 and MySQL 8.0.20 - 8.0.21 are not supported.**

## Installation on a Linux based system
### Docker (recommended)
As minimum requirement, we need a docker runtime with version 1.12.* or higher. [psh.phar](https://github.com/shopwareLabs/psh) provides the following available docker commands:

```bash
./psh.phar docker:start     # start & build containers
./psh.phar docker:ssh       # ssh access web server
./psh.phar docker:ssh-mysql # ssh access mysql
./psh.phar docker:status    # show running containers and network bridges
./psh.phar docker:stop      # stop the containers
./psh.phar docker:destroy   # clear the whole docker cache
```

To start the docker environment just type 
```bash
./psh.phar docker:start
```
on your command line. The several containers are booted and afterwards you can login into your web container with 
```bash
./psh.phar docker:ssh
```
After that, you can start the initialization process by typing 
```bash
./psh.phar init
```

After a few minutes, our test environment should be available under the address [10.100.200.46](http://10.100.200.46).

To get a full list of available commands, you can use 
```bash
./psh.phar
```

## Installation on a OS X based system
The following commands are available to create a mac setup. Apache, MySQL and ant are
required. You can use brew package manager to install them.

```bash
./psh.phar mac:init         # build installation
./psh.phar mac:start        # start apache, mysql 
./psh.phar mac:stop         # stop apache, mysql
./psh.phar mac:restart      # restart apache, mysql
```
You can change the database configuration in your own .psh.yaml file.
```bash
mac:
    paths:
      - "dev-ops/mac/actions"
    const:
      DB_USER: "USERNAME"
      DB_PASSWORD: "PASSWORD"
      DB_HOST: "DB_HOST"
      SW_HOST: "SWHost"
```
For a better explanation, use the provided .psh.yaml.dist file as an example.

#### Common
Once the environment has booted successfully, you can use the common scripts to setup shopware

```bash
./psh.phar clear # remove vendor components and previously set state
./psh.phar init # init composer, install plugins
./psh.phar unit # execute test suite
```

## Quick Start Guide
A [quick start guide](/shopware-enterprise/b2b-suite/component-guide/quick_start/#installation) for the B2B-Suite is in the component-guide.
