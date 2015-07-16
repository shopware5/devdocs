---
title: Create delta updates using rsync
tags:
    - rsync

authors: [bc]
---

To create shopware update packages we use the well-known unix utility [rsync](https://rsync.samba.org/).

## Delta update

A delta update is a update that contains only the files that have changed. This results in smaller update packages, faster downloads and a overall better update experience.

To create a new update package we first have to create the corresponding install package for that release. Also we need the oldest install package we want to allow an delta update from.

In the following example we use:
- `install_4.2.0/` as the oldest supported release
- `install_5.0.2/` as the new release
- `update_5.0.2/` as the delta update package

## Create the update

We use the not so well-known rsync option `--compare-dest`:

From the [man page](http://linux.die.net/man/1/rsync):
> **--compare-dest=DIR**  - This option instructs rsync to use DIR on the destination machine as an additional hierarchy to compare destination files against doing transfers (if the files are missing in the destination directory). If a file is found in DIR that is identical to the sender's file, the file will NOT be transferred to the destination directory. This is useful for creating a sparse backup of just files that have changed from an earlier backup.

As always, when using rsync, trailing slashes are important:

```bash
rsync -rcE --compare-dest=/tmp/install_4.2.0/ /tmp/install_5.0.2/ /tmp/update_5.0.2/
```

Please note that `--compare-dest` has to be either absolute or relative to first argument.

The command above can also be written using relative paths:

```bash
rsync -rcE --compare-dest=../install_4.2.0/ install_5.0.2/ update_5.0.2/
```

This command will compare the directories `install_4.2.0/` and `install_5.0.2/`. All changed files will end up in `update_5.0.2/`.

Finally, we delete the empty directories that rsync left behind, using the unix `find` utility:

```bash
find update_5.0.2/ -type d -empty -delete
```



