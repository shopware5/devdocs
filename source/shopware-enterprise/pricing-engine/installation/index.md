---
layout: default
title: Installation Guide
github_link: pricing-engine/installation/index.md
indexed: true
tags: [pricing engine, installation]
menu_title: Installation Guide
menu_order: 2
group: Shopware Enterprise
subgroup: Pricing Engine
---

<div class="toc-list"></div>

## General

At the moment we provide only a docker based virtualization solution. Our developers use the docker containers mainly. These containers are also used in our continuous integration process. The supported functions are for both systems equal if the host systems is based on Linux.
 
If you want to install the Pricing Engine for production environment your system must fit with the defined requirements from the [Shopware core](https://developers.shopware.com/sysadmins-guide/system-requirements/). In contrast to the Shopware core requirements we need php 7.0 or higher and MySQL 5.7.0 or higher.

## Installation on a Linux based system
### Docker (recommended)
As minimum requirement, we need a docker runtime with version 1.12.* or higher and a [phive](https://phar.io/#Install) installation. Before you can use [psh](https://github.com/shopwareLabs/psh) you have to execute phive install in the root directory. After that psh provides the following available docker commands:

```bash
./psh docker:start     # start & build containers
./psh docker:ssh       # ssh access web server
./psh docker:ssh-mysql # ssh access mysql
./psh docker:status    # show running containers and network bridges
./psh docker:stop      # stop the containers
./psh docker:destroy   # clear the whole docker cache
```

To start the docker environment just type 
```bash
./psh docker:start
```
on your command line. The several containers are booted and afterwards you can login into your web container with 
```bash
./psh docker:ssh
```
After that, you can start the initialization process by typing 
```bash
./psh init
```

After a few minutes, our test environment should be available under the address [http://10.222.222.30](http://10.222.222.30).

To get a full list of available commands, you can use 
```bash
./psh
```
