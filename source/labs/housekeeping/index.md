---
layout: labs
title: Housekeeping
github_link: labs/housekeeping/index.md
shopware_version: X
indexed: true
group: Labs
menu_title: Housekeeping
menu_order: 500
---

In oder to be able to support all the new features and improvements described in these documents, the shopware core will need to 
undergo a lot of structural changes - including refactorings, rewrites and other technical modifications. We are currently
in the process of evaluating the new technical basis of the system and have decided on some of the most fundamental technologies already. 
As we have announced on the last Shopware Community Day in June 2017, we are going to bid farewell to ExtJS as the basis for 
the shopware backend. Additionally, the Zend framework will go and give place to a more tightly integrated, full-stack Symfony 3 based approach.
The technology stack will be based on PHP 7.1 and MySQL database schema fully integrated with foreign key constraints where sensible.

# Changelog

## Update from 2017-09-27

We've restored out testing environment to make sure that ported components still work in the new environment.

And talking about environment, we've ditched the configurtion via `config.php` and created a `.env.dist` file which now provides all credentials and configurations for the kernel. One of the best advantages when using environment variables is, that you can set them in your apache2, nginx and other webserver's config. That makes deployment a lot easier.

To follow our own new concepts, we've added deptrac and other code quality tools. This prevents developers from using services in layers where they are not meant to be, like using the database connection in the controller.
