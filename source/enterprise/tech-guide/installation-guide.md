---
layout: default
title: Shopware Enterprise Dashboard Installation Guide
github_link: enterprise/tech-guide/installation-guide.md
indexed: false
---

This guide will cover the Shopware Enterprise Dashboard installation process

<div class="toc-list"></div>


## Dependencies - Enterprise Dashboard host

The following system libraries/application are required to install and run the Shopware Enterprise Dashboard from a release package:

- Linux operating system
- PHP 5.6 or later (PHP 7 recommended) including CLI support
- Apache2 web server
- MySQL
- Ansible Version 1.9+
- Beanstalk Version 1.9+
- Supervisor 3.0+ 

If you are setting up a development version of the EDB, you might need the following additional tools:
- Ant
- NodeJS + NPM
  - Grunt
  - Bower
  - Jasmine
- Vagrant + Virtualbox

Similar architectures might also work, but are not officially supported.

## Installing the Shopware Enterprise Dashboard

### Using the installation package

For evaluation, testing or production purposes, it's recommended using one of the available installation packages:
- Download the installation package
- Extract the archive to your web server's content folder
- Enable web server access to _APPLICATION_/web
- Execute `php setup.phar` through the command line
- Follow the instructions
- Symlink _APPLICATION_/supervisord/edb.conf.dist into your `supervisor` to configuration folder. Keep in mind that supervisor might be configured to only include file that end in `.conf`.


### Using the development version

If you are installing the EDB in a development environment and have been granted access to the git repository for the project, you 
should refer to the included README file for instructions on how to best set up the EDB for development purposes.

## Dependencies - Shopware hosts

Shopware host machines also have special requirements in order to be compatible with the Enterprise Dashboard:
- The must meet the requirements of any Shopware version you wish to install on them
- `tar`
- `python-mysqldb`
- A user `edb-deploy` must exist with `edb-deploy` as password (this will be changed before the initial stable release). It must have permissions to read and write to your configured web server folder.
- `acl` installed and enabled for the mount point in which your Shopware instances will be installed
- `/tmp` folder access
- Each Shopware host machine must be present in the EDB host's `known_hosts` file of the user that runs the worker tasks

While not strictly necessary, it is recommended that you add the `edb-deploy` user to your web server's group (typically `www-data` for Apache). This usually helps avoid certain pitfalls related to file system permission handling.

Keep in mind that if you are using the EDB to connect to pre-existing Shopware installations, these hosts also need to meet these requirements, otherwise the EDB will not be able to correctly install the necessary integration plugin.
