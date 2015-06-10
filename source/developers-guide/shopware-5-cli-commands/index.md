---
layout: default
title: Shopware 5 CLI commands
github_link: developers-guide/shopware-5-cli-commands/index.md
indexed: true
---

Shopware 5 includes several CLI commands that you can use to run operations that, for any reason, can or should not be executed from the browser interface. The commands described below will help you perform otherwise complicated tasks, or optimize, in the background, certain aspects of your shop, which will make it perform better.

As CLI commands may be added in future releases, this document may not contain all the available commands. Moreover, plugins can add new, custom commands, that will not be covered here. To get a full list of the available CLI commands in your Shopware installation, type the following in a CLI shell:

```
php bin/console
```

You can get detailed description for a specific command using the following syntax:

```
php bin/console <command> --help
```

## Snippets

### sw:snippets:find:missing

This command expects a `locale` key (e.g. `en_GB`, see all possible value in `s_core_locales`) as a required argument. For that locale, it will check the snippets database table to find unique snippets (defined by a unique `name`-`namespace` pair) that are defined for other locales but not the given one. Those snippets are then exported into the ´snippets´ folder of your Shopware installation (by default, it doesn't exist in Shopware 5 installations, and it will be automatically created if needed) as .ini files. If the target file already exists, the new snippets will be appended to it.

The command accepts two optional arguments:

- `target`: Folder to which the snippets will be written. Defaults to `snippets`

- `fallback`: By default, the exported snippets are left with empty values. If you provide a locale key in this argument, the snippets are exported with the value of the matching snippet in the fallback language (if available).

This command is useful in many situations. It can be used to find missing translations for your plugin's snippets, or to export complete snippet sets, if you wish to create a new translation plugin for Shopware.
 
### sw:snippets:remove

This command requires a `folder` argument. It scans that folder (and subfolders) for .ini snippet files and, for those found, removes them from the database.

### sw:snippets:to:db

This command loads all snippets from the .ini files inside the`snippets` folder in your Shopware installation path into the database. 

- `include-plugins`: If provided, the command will also search all your active plugins for a `snippets` folder, and import those too.

- `force`: By default, if a snippet being imported already exists in the database, it will not be overwritten. Use the `force` argument to change this behaviour

- `source`: use this argument if your wish to import snippets from a folder other that then `snippets` folder in the root of your Shopware installation.

### sw:snippets:to:ini

Exports snippets from the database into .ini files. It requires a ´locale´ argument (e.g. `en_GB`, see all possible value in `s_core_locales`) indicating which snippet set to export. If a file for a given namespace already exists, the snippets will be appended to the existing content.

- `target`: Folder to which the snippets will be written. Defaults to `snippets`


### sw:snippets:to:sql

Loads snippets from the `snippets` folder of your Shopware installation and creates a SQL file that, when executed, will insert those snippets into the `s_core_snippets` table. It requires a `file` argument containing the desired location of the SQL file. 

- `force`: By default, if the target `file` already exists, it will not be overwritten. Use this argument to change this behaviour.

- `include-default-plugins`: Set this option to also export snippets included in Shopware's core plugins

- `include-plugins`: If set, active plugin snippets will also be exported.

- `update`: By default, the generated SQL script only performs inserts. If the `update` option is provided, it will also handle update scenarios when duplicates are found. If the existing database snippet has `dirty` = 0, the value will be overwritten. If `dirty` is 1, it's not changed. Please note that, for a large number of snippets, enabling update support will make the SQL statements significantly slower to execute upon importing.
