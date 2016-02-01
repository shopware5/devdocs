---
layout: default
title: Shopware Server Configuration Guide
github_link: enterprise/tech-guide/shopware-server-configuration-guide.md
indexed: false
---

This guide covers the steps necessary to configure a new Shopware Server to be used in conjunction with your Enterprise Dashboard.

<div class="alert alert-warning">
This guide should only be used in the Enterprise Dashboard context. It is not meant to be used for standalone Shopware installations.
</div>

<div class="toc-list"></div>

## Requirements

- The must meet the requirements of any Shopware version you wish to install on it
- `tar`
- `python-mysqldb`
- A system user `edb-deploy` must exist and be accessible by SSH by your EDB server's supervisor user  (defaults to `edb-supervisor`) without prompting for passwords 
- A MySQL user `edb-deploy` must exist with `edb-deploy` as password (this will be changed before the initial stable release). It must have permissions to create new databases.
- `acl` installed and enabled for the mount point in which your Shopware instances will be installed
- `/tmp` folder access

While not strictly necessary, it is recommended that you add the `edb-deploy` user to your web server's group (typically `www-data` for Apache). This usually helps avoid certain pitfalls related to file system permission handling.

Keep in mind that if you are using the EDB to connect to pre-existing Shopware installations, these hosts also need to meet these requirements, otherwise the EDB will not be able to correctly install the necessary integration plugin.


## Configuration steps

The following steps are meant to guide you through the setup of a new Shopware server that you will use with the Shopware Enterprise Dashboard. If you want to use the Shopware Enterprise Dashboard with a previously configured Shopware server, you might need to adapt some of these steps, depending on your exact server configuration.

### Install required packages 

All system packages required by Shopware and the Enterprise Dashboard. Refer to the requirements list above and Shopware's documentation for more info.
 
 
### Create a user account

To access your Shopware server, the Enterprise Dashboard requires a user account. This user account should be named `edb-deploy` and be accessible by the Enterprise Dashboard Server's `edb-supervisor` user (or other, in case you changed it during the setup process).

Additionally, the Enterprise Dashboard Server's `edb-supervisor` user must be able to login using SSH to your Shopware server without triggering a password prompt. You can run the following command on the Enterprise Dashboard Server to this this:
  
```
sudo -u edb-supervisor ssh edb-deploy@<ip-or-host-name>
```

This command should take you directly to a shell in your Shopware Server. If that doesn't happen, you need to review your SSH configuration. We recommend using SSH key authentication between your two servers. Additionally, for increased security, you should use different pairs of keys for connecting to different servers. You can find extensive documentation online about how to do this, and how to create a `.ssh/config` file that will reflect your desired configuration.
  
### Create a MySQL account

Certain actions will require the Enterprise Dashboard to access your Shopware Server's database directly. For that reason, you should ensure that a `edb-deploy` MySQL user exists and has `edb-deploy` as a password. It must have permissions to create new databases, as well as access the existing ones. As a security measure, you can limit this user to local access. All MySQL actions from the Enterprise Dashboard are tunneled in SSH by `ansible`.
 
 
### Handle file system permissions

As a security precaution, we use file system ACLs, which should be active on your file system. Access to the `/tmp` folder is also required, for storing temporary data.
 
### Configure your server on the Enterprise Dashboard

After following the above steps, your server is now ready to be used together with the Enterprise Dashboard. You can log in to the Enterprise Dashboard, access the `Servers` panel and configure you server.