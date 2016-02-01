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
- Create a user account to execute your `supervisor` tasks (by default is set to `edb-supervisor`). It should be have permissions to connect to your Shopware servers using SSH. 
- Execute `php setup.phar` through the command line
- Follow the instructions
- Symlink _APPLICATION_/supervisord/edb.conf.dist into your `supervisor` to configuration folder. Keep in mind that supervisor might be configured to only include file that end in `.conf`.


### Using the development version

If you are installing the EDB in a development environment and have been granted access to the git repository for the project, you 
should refer to the included README file for instructions on how to best set up the EDB for development purposes.

## Shopware servers

Shopware server machines also have special requirements in order to be compatible with the Enterprise Dashboard. For more details on dependencies and configuration instructions, see [this page](/enterprise/tech-guide/shopware-server-configuration-guide)