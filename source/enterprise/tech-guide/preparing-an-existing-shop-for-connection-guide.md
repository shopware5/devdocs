---
layout: default
title: Shopware Server Configuration Guide
github_link: enterprise/tech-guide/shopware-server-configuration-guide.md
indexed: false
---

This guide describes how you need to configure an existing Shopware installation before connecting it to the ECA. We assume 
you have already executed the [Shopware server configuration guide](/enterprise/tech-guide/shopware-server-configuration-guide)
<div class="toc-list"></div>

## What is the ECA trying to do?

The ECA tries to upload and install the Connector plugin.
 
1. Upload the plugin zip file to the remote /tmp directory
2. Extract it to a newly created directory in `_shop_/engine/Shopware/Plugins/Local/Backend/`
3. execute the cli installer through `bin/console`
4. Clears the caches to finish the execution

## Why is it not working out of the box?

To connect an existing Shop to the ECA it is necessary to set the **filesystem rights** manually. On a fresh installation the ECA can control
file ownership and set it so that the webserver and deployment user can access the installation. This is then secured by the plugin and the ECA.

But since existing shops don't have the plugin yet, file ownership needs to be changed manually in order for the ECA to deploy the plugin.

To give you an overview how the ECA sets the ownership this is the output of `ls -ahl` of a freshly installed shop root:

```sh
drwxrwxr-x 12 ssh-deploy ssh-deploy 4.0K Jul 29 07:55 .
drwxr-xr-x  6 ssh-deploy ssh-deploy 4.0K Jul 29 07:51 ..
-rw-rw-r--  1 ssh-deploy ssh-deploy 2.9K Sep 16  2015 .htaccess
-rw-rw-r--  1 ssh-deploy ssh-deploy 3.4K May 23 09:45 CONTRIBUTING.md
-rw-rw-r--  1 ssh-deploy ssh-deploy 4.5K Sep 16  2015 README.md
-rw-rw-r--  1 ssh-deploy ssh-deploy  72K May 23 09:45 UPGRADE.md
-rw-rw-r--  1 ssh-deploy ssh-deploy 1.4K May 23 09:45 autoload.php
drwxrwxr-x  2 ssh-deploy ssh-deploy 4.0K Jul 29 07:54 bin
-rw-rw-r--  1 ssh-deploy ssh-deploy 2.8K May 23 09:45 composer.json
-rw-rw-r--  1 ssh-deploy ssh-deploy 146K May 23 09:45 composer.lock
-rw-rw-r--  1 ssh-deploy ssh-deploy  191 Jul 29 07:51 config.php
drwxrwxr-x  4 ssh-deploy ssh-deploy 4.0K Sep 16  2015 engine
-rw-rw-r--  1 ssh-deploy ssh-deploy  48K May 23 09:45 eula.txt
-rw-rw-r--  1 ssh-deploy ssh-deploy  47K May 23 09:45 eula_en.txt
drwxrwxr-x  4 ssh-deploy ssh-deploy 4.0K Sep 16  2015 files
-rw-rw-r--  1 ssh-deploy ssh-deploy  35K Sep 16  2015 license.txt
drwxrwxr-x  9 ssh-deploy ssh-deploy 4.0K Jul 29 07:54 media
drwxrwxr-x  5 ssh-deploy ssh-deploy 4.0K Sep 16  2015 recovery
-rw-rw-r--  1 ssh-deploy ssh-deploy 3.7K May 23 09:45 shopware.php
drwxrwxr-x 13 ssh-deploy ssh-deploy 4.0K Jul 29 07:54 templates
drwxrwxr-x  4 ssh-deploy ssh-deploy 4.0K Jul 29 07:54 themes
drwxrwxr-x  4 ssh-deploy ssh-deploy 4.0K May 23 09:45 var
drwxrwxr-x 19 ssh-deploy ssh-deploy 4.0K Jul 29 07:54 vendor
drwxrwxr-x  3 ssh-deploy ssh-deploy 4.0K Sep 16  2015 web
```

As you can see the deployment user (in this cas `ssh-deploy`) owns all files and, since the webserver and deployment user share the same unix groups, 
everything is group readable and writable.

In almost all linux systems changing file ownership is **restricted to root**. Therefore you yourself will have to take care of this change.

## What do I need to do then?

Log into your shop servers shell and adjust the filesystem access rights manually.

1. Set ownership of the shop to either your webserver or your deployment user
2. Update file permissions to make the files group readable and writable
3. Set bin/console to be executable

On your usual linux system this should look like the following commands:

```sh
yhown -R ssh-deploy:ssh-deploy _shop_/
chmod -Rv u+rwX g+rwX _shop_/
chmod  +x _shop_/bin/console
```

After executing these commands you should be able to connect the shop through the ECA's user interface.





