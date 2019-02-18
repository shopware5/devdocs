---
layout: default
title: MediaService
github_link: developers-guide/shopware-5-media-service/index.md
shopware_version: 5.1.0
indexed: true
history:
    2015-09-08: creation
    2015-12-15: added strategy documentation
group: Developer Guides
subgroup: General Resources
menu_title: MediaService
menu_order: 70
---

The `Shopware\Bundle\MediaBundle` defines how Shopware manages its media files. Shopware 5.1 includes a new media management layer, which abstracts the location of your media files. This applies to both new and existing installations, and there is no possibility to revert to the old behaviour.

<div class="toc-list" data-depth="3"></div>

## The problem

Since the beginning of Shopware, the media files were organized in a directory called `media` with subdirectories for each media type, e.g. image, video, and so on. Inside a sub-directory, every file has just been thrown in. The problem is that, if you have a huge amount of media files, file operations get very slow, especially on Windows systems.

For that reason, we decided to apply commonly used techniques to our media management and introduce the new MediaService.

## The solution

The main goal of the MediaService is to abstract and handle all file operations, so that you don't have to worry about solving common problems associated with it. The key idea to keep in mind is that you shouldn't directly perform any file system operation on your files. Should you need to read, move or delete your files, you should always use the MediaService.

In the past, the most common use-case was to load a path like `media/image/my-fancy-image.png` from the database and, during the template render process, prepend the base url to it. These paths are still used, but now as **virtual paths**. These paths identify your files, and are used by the MediaService to access them. Note that the actual file will **not** be in this exact location in your file system. It's up to the MediaService to retrieve the real path from this virtual path.

## Handling your files using the MediaService

The following code snippets should be self-explanatory. They assume you already retrieved the `shopware_media.media_service` service into the `$mediaService` variable:

```php
$mediaService = $container->get('shopware_media.media_service');
```

### URL generation

The following example shows how to generate a url based on the virtual path.

```php
echo $mediaService->getUrl('media/image/my-fancy-image.png');
// result: https://www.myshop.com/media/image/0a/20/03/my-fancy-image.png
```

Simply get the `shopware_media.media_service` from the DI container and call `getUrl()` with your virtual path. As a result you'll get a full qualified URL and you are able to bind it to a view. There is no need to use the `{link ...}` Smarty expression.

#### Smarty

In your Smarty templates you may use the `{media path=...}` expression to get the fully qualified URL.

```html
<img src="{media path="media/image/my-fancy-image.png"}">
```

`{media}` evaluates the given path at template's compile time, so you cannot use runtime variables for its path argument (generally you will use a constant path as in the example above).


### Check if a files exists

This should be used as replacement for `file_exists()`

```php
$fileExists = $mediaService->has('media/image/my-fancy-image.png');
```

### Reading

This should be used as replacement for `file_get_contents()` and `fopen()`/`fread()`

```php
$fileContent = $mediaService->read('media/image/my-fancy-image.png');
$fileStream = $mediaService->readStream('media/image/my-fancy-image.png');
```

### Writing

This should be used as a replacement for `file_put_contents()` and `fopen()`/`fwrite()`

```php
$mediaService->write('media/image/my-fancy-image.png', $fileContent);
$mediaService->writeStream('media/image/my-fancy-image.png', $fileStream);
```

### Deleting

This should be used as a replacement for `unlink()`

```php
$mediaService->delete('media/image/my-fancy-image.png');
```

### Moving / Renaming

This should be used as a replacement for `rename()`

```php
$mediaService->rename('media/image/my-fancy-image.png', 'media/image/super-duper-fancy-image.png');
```

### Handling virtual paths

**Virtual paths**, like the examples shown above, are used by the MediaService to access your files. To better handle all kinds of paths, the MediaService includes a `normalize($path)` method that you can use determine the correct virtual path from different variations of the file's path. As an example, the following 3 paths will all be converted into the correct format (`media/image/my-fancy-image.png`) by the path normalizer:

**Example: Convert to virtual path**

```php
$mediaService->normalize('https://www.myshop.com/shop/media/image/my-fancy-image.png');
$mediaService->normalize('/var/www/shop1/media/image/my-fancy-image.png');
$mediaService->normalize('media/image/5c/af/3e/my-fancy-image.png');

// result: media/image/my-fancy-image.png
```

You can use this normalizer too by calling `$mediaService->normalize($path)`. Note that, when using the MediaService operations, you don't need to explicitly normalize the paths, as this is done for you automatically. As such, the following lines would all produce the same result:

**Example: Generate URL**

```php
$url = $mediaService->getUrl('media/image/my-fancy-image.png');
$url = $mediaService->getUrl('/var/www/shop1/media/image/my-fancy-image.png');
$url = $mediaService->getUrl($mediaService->normalize('/var/www/shop1/media/image/my-fancy-image.png'));

// result: https://www.myshop.com/media/image/0a/20/03/my-fancy-image.png
```

## File system adapters

Using the MediaService allows Shopware to better cope with problems like the one described in this document's first section. However, abstracting the media file system also provides a way to support distributed hosting of content. This can be done by using **file system adapters**. These adapters allow you to store files in places other than your current server, like a FTP server or a CDN service provider. As these adapters are abstracted by the MediaService, they are transparent for the controller logic, and will work out of the box with any Shopware plugin that uses the MediaService.

By default, adapters for local and FTP based file systems are included since Shopware 5.1.

### Bullt-in Adapters since Shopware 5.5

* Amazon S3
* Google Cloud Platform

**Example Configuration S3**

```php
'cdn' => [
    'backend' => 's3',
    'adapters' => [
        's3' => [
            'type' => 's3',
            'mediaUrl' => 'YOUR_S3_OR_CLOUDFRONT_ENDPOINT',
            'bucket' => 'YOUR_S3_BUCKET_NAME',
            'region' => 'YOUR_S3_REGION',
            'credentials' => [
                'key' => 'YOUR_AWS_KEY',
                'secret' => 'YOUR_AWS_SECRET'
            ]
        ]
    ]
]
```


**Example Configuration GCP**

```php
'cdn' => [
    'backend' => 'gcp',
    'adapters' => [
        'gcp' => [
            'type' => 'gcp',
            'mediaUrl' => 'YOUR_GCP_PUBLIC_URL',
            'bucket' => 'YOUR_GCP_BUCKET',
            'projectId' => 'YOUR_GCP_PROJECT_ID',
            'keyFilePath' => 'PATH_TO_GCP_KEY_FILE',
        ]
    ]
]
```

### Existing Adapters

You can download and install the following provider plugins just like any other Shopware plugin. Keep in mind that no official support will be provided for these plugins.

* [Amazon S3](https://github.com/shopwareLabs/SwagMediaS3) - Required only on < 5.5
* [Microsoft Azure](https://github.com/shopwareLabs/SwagMediaAzure)
* [Google Cloud Platform](https://github.com/shopwareLabs/SwagMediaGCP) - Required only on < 5.5
* [SFTP](https://github.com/shopwareLabs/SwagMediaSFTP)

### Build your own adapter

Since our MediaService is built on top of [Flysystem](http://flysystem.thephpleague.com), feel free to create your own adapter and share it with the community. You can take the plugins mentioned above as example.

### Migrating your files

To make migrations easy, we've added a new CLI command to migrate all of your media files at once to the location specified by the currently configured adapter.

```bash
bin/console sw:media:migrate
```

If you are upgrading to Shopware 5.1 or later from 5.0 or previous version, you might also use this command to migrate your files from the legacy location to their new directory. If you can't or decide not to run this command, your media files will still be migrated. The live migration mechanism moves the files to the right place as they get requested. However, we recommend that you use the CLI command to migrate your files, as the live migration might impact your shop's performance.

<div class="alert alert-info">
<strong>Important information for nginx users</strong><br/>
If you are still facing problems with media files, you should update your nginx configuration to the latest version. A working configuration can be found <a href="https://github.com/bcremer/shopware-with-nginx">at GitHub</a>. It adds a location for the media directory and redirects a failed image lookup to a new frontend controller, which then tries to find the requested image.
</div>

#### Example: Migrating all media files to Amazon S3

Assuming you already installed the [Amazon S3 adapter](https://github.com/ShopwareLabs/SwagMediaS3) plugin, you now need to configure it. To do so, you have to edit your Shopware `config.php` and add a new adapter called `s3` and a configuration to access Amazon S3 account like described in the [plugin description on GitHub](https://github.com/ShopwareLabs/SwagMediaS3).

You can now simply run this command to move all files from your local media file system to Amazon S3.

```bash
bin/console sw:media:migrate --from=local --to=s3
```

<div class="alert alert-warning">
After executing this command, you won't have any media file left on your local file system. The migration itself might take some time, depending on the number of media files you have. If you cancel the migration or your server crashes, you can continue the migration by running the command again.
</div>

Should you want to revert the migration to Amazon S3, you can use the following command:

```bash
bin/console sw:media:migrate --from=s3 --to=local
```

Again, keep in mind that the live migration mechanism will still be in place, meaning that, if your Amazon S3 adapter is still configured, files will be migrated back to Amazon's servers when loaded by any incoming frontend request.

#### Example: Migrating back to the simple directory structure

Shopware provides you with two strategies: `md5` (default) and `plain`. A strategy describes how your media files are stored. By default, you'll use the `md5` based directory structure, which splits the files into three sub directories. This is intended to gain performance for a large set of media files. In case you want the old directory structure back, you have to do the following steps.

##### 1. Create a new CDN adapter in your config.php

You should add the following options to your `config.php`. Please notice the `strategy` property in the `local` adapter. You can easily switch between strategies using this parameter.

```php
'cdn' => [
    'adapters' => [
        'local' => [
            'type' => 'local',
            'mediaUrl' => '',
            'strategy' => 'plain',
            'path' => realpath(__DIR__ . '/'),
            'permissions' => [
                'file' => [
                    'public' => 0666 & ~umask(),
                    'private' => 0600 & ~umask(),
                ],
                'dir' => [
                    'public' => 0777 & ~umask(),
                    'private' => 0700 & ~umask(),
                ]
            ],
        ],
        'old_local' => [
            'type' => 'local',
            'mediaUrl' => '',
            'path' => realpath(__DIR__ . '/'),
            'permissions' => [
                'file' => [
                    'public' => 0666 & ~umask(),
                    'private' => 0600 & ~umask(),
                ],
                'dir' => [
                    'public' => 0777 & ~umask(),
                    'private' => 0700 & ~umask(),
                ]
            ],
        ]
    ]
]
```

##### 2. Migrating to the plain strategy

```bash
bin/console sw:media:migrate --from=old_local --to=local
```

After you've ran the command, your media files should be in the place they were at before the media service ways introduced.

Feel free to create your strategy and share it with the community.
