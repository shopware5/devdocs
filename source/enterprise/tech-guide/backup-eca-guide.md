---
layout: default
title: Backup the Client Administration
github_link: enterprise/tech-guide/backup-eca-guide.md
indexed: false
---

This guide describes how you back up the Client Administration.

<div class="toc-list"></div>

## How to create a backup

### 1. The mysql database.

First of all, you have to back up the mysql database. The database is usually called __shopware_client_administration__.

You can do it e.g. with mysqldump:
````
mysqldump -u [username] -p shopware_client_administration > [filename].sql
````

----------------------

### 2. The Parameters.yml

The second document to back up is the __parameters.yml__.

It can be found in the folder:
````
ClientAdministration/app/config/parameters.yml
````

There you can find all hashes to encrypt and decrypt.

----------------------

### 3. The upload directory

The last folder is the upload directory.

````
ClientAdministration/uploads
````

There are all hooks and each uploaded file.

It is sufficient to copy it.
````
cp ./ClientAdministration/uploads [Backup directory]
````

----------------------

## How to restore the backup

### 1. The mysql database

First you have to apply your sql file to the database.

````
CREATE DATABASE shopware_client_administration;
````

````
mysql -u root shopware_client_administration < [Backup directory].sql

````

----------------------

### 2. The Parameters.yml

Override the default config __ClientAdministration/app/config/parameters.yml__ with the __backuped parameters.yml__.

----------------------

### 3. The Upload directory

The last step is to push back the uploads into the uploads directory.

````
ClientAdministration/uploads
````