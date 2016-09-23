---
layout: default
title: Update the Client Administration
github_link: enterprise/tech-guide/update-eca-guide.md
indexed: false
---

This guide describes how you update the Enterprise Client Administration.

<div class="toc-list"></div>

## How to update the ECA

----------------------

### 1. Start the maintenance mode

First of all, you should turn the eca into the maintenance mode for security reasons.
This blocks the frontend from every user interaction.

To enable the maintenance mode create a `app.lock` file in the root directory of your eca.

E.g.
`touch /var/www/enterprise-client-administration/app.lock`

----------------------

### 2. Turn of the ECA background processes

Now you can turn of the ECA background processes, by stopping the supervisor service.

Just start a terminal and execute

```shell
sudo supervisorctl stop all
```

----------------------

### 3. Backup the ECA.

Next, you should create a [backup](/enterprise/tech-guide/backup-eca-guide/) of your ECA.

For this usecase we already provide an userguide. [Back up the eca](/enterprise/tech-guide/backup-eca-guide/)

----------------------

### 4. Install the ECA

After you have created a backup of your ECA, you can install the new version.

Just [download](/enterprise/tech-guide/installation-guide/) the updated version of the ECA package and then extract all files into your existing directory.

Then execute the `setup.phar` and follow the instructions of the terminal.

----------------------

### 5. Turn back the ECA into production

Now you can execute 

```shell
sudo supervisorctl restart
```

in the terminal to restart the ECA background processes.

Then you can turn off the maintenance mode, so the frontend is accessible.

```shell
rm /var/www/enterprise-client-administration/app.lock
```

After you completed these steps you should open the frontend and validate the update and that the processes are working.

----------------------