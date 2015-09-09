---
layout: default
title: MediaService
github_link: developers-guide/shopware-5-media-service/index.md
indexed: true
---
The `Shopware\Bundle\MediaBundle` defines how Shopware manages its media files. Shopware 5.1 includes a new media management layer, which abstracts the location of your media files. This applies to both new and existing installations, and there is no possibility to revert to the old behaviour.

## The Problem

Since the beginning of Shopware, the media files were organized in a folder called `media` with sub-folders for each media type, e.g. image, video, and so on. Inside a sub-folder, every file has just been thrown in. The problem is that if you have a huge amount of media files, file operations get very slow, especially on Windows systems.

For that reason, we decided to apply common techniques to our media management and introduce the new MediaService.

## What has changed?

The key idea to keep in mind is that you should no longer perform any direct file system operation to your files. That means you should always use the MediaService to interact with media files. In the past, the most common use-case was to save a path like `media/image/my-fancy-image.png` to the database and prepend the base url to it. A path like this `media/image/my-fancy-image.png` should now be considered as **virtual path** which gets mapped to the selected file system.

## How to use the MediaService

In the MediaService, every method parameter runs through a normalizer which strips everything unrelated from a path. So all of the following examples will result in `media/image/my-fancy-image.png`:

* `https://www.myshop.com/shop/media/image/my-fancy-image.png`
* `/var/www/shop1/media/image/my-fancy-image.png`
* `media/image/5c/af/3e/my-fancy-image.png`

*Tip: You can use this normalizer too by calling `$mediaService->normalize($path)`*

The following code snippets should be self-explaining. To keep them clean, keep this variables in mind.

```php
$path = 'media/image/my-fancy-image.png';
$mediaService = $container->get('shopware_media.media_service');
```

#### URL generation

The following example shows how to generate a url based on the virtual path.

```php
$url = $mediaService->getUrl($path);
// result: https://www.myshop.com/media/image/0a/20/03/my-fancy-image.png
```

Simply get the `shopware_media.media_service` from the DI container and call `getUrl()` with your virtual path. As a result you'll get a full qualified URL and you are able to bind it to a view. There is no need to use the `{link ...}` smarty expression.


#### Check existing

This should be used as replacement for `file_exists()`

```php
$fileExists = $mediaService->has($path);
```

#### Reading

This should be used as replacement for `file_get_contents()` or `fopen()`/`fread()`

```php
$fileContent = $mediaService->read($path);
$fileStream = $mediaService->readStream($path);
```

#### Writing

This should be used as a replacement for `file_put_contents()` or `fopen()`/`fwrite()`

```php
$mediaService->write($path, $fileContent);
$mediaService->writeStream($path, $fileStream);
```

#### Deleting

This should be used as a replacement for `unlink()`

```php
$mediaService->delete($path);
```

#### Moving / Renaming

This should be used as a replacement for `rename()`

```php
$mediaService->rename($path, $newPath);
```

## Migration

### Migrate folder structure

To make the migration easy, we've added a new CLI command to migrate all of your media files at once. 

```bash
bin/console sw:media:migrate
```

If you can't or decide not to run this command, your media files will still be migrated. The live migration mechanism moves the files to the right place as they get requested. However, we recommend that you use the CLI command to migrate your files, as the live migration might impact your shop's performance.

### Migrate to another file system

#### Adapters

By default, adapters for local and FTP based file systems are included in Shopware 5.1. We provide an [Amazon S3 adapter](https://github.com/ShopwareLabs/SwagMediaS3) and a [SFTP adapter](https://github.com/ShopwareLabs/SwagMediaSftp). You can download and install them just like any other Shopware plugin. Keep in mind that no official support will be provided for these plugins.

#### Build your own adapter

Since our MediaService is built on top of [Flysystem](http://flysystem.thephpleague.com), feel free to create your own adapter and share it with the community.

#### Example: Migrating all media files to Amazon S3

Assuming you already installed the [Amazon S3 adapter](https://github.com/ShopwareLabs/SwagMediaS3) plugin, you now need to configure it. To do so, you have to edit your Shopware `config.php` and add a new adapter called `s3` and a configuration to access Amazon S3 account like described in the [plugin description on GitHub](https://github.com/ShopwareLabs/SwagMediaS3).

You can now simply run this command to move all files from your local media file system to Amazon S3.

```bash
bin/console sw:media:migrate --from=local --to=s3
```

#### Caution: Your files will be moved.

You won't have any media file left on your local file system. The migration itself might take some time, depending on the number of media files you have. If you cancel the migration or your server crashes, you can continue the migration by running the command again or rollback by flipping the parameters like seen below.

```bash
bin/console sw:media:migrate --from=s3 --to=local
```