---
layout: default
title: Media Optimizer
github_link: developers-guide/media-optimizer/index.md
shopware_version: 5.2.17
indexed: true
tags:
  - media
  - optimizer
  - pagespeed
  - thumbnails
group: Developer Guides
subgroup: General Resources
menu_title: Media Optimizer
menu_order: 300
---

The service `shopware_media.optimizer_service` optimizes files using external tools after uploading them using the media manager.

<div class="toc-list"></div>

## General

The optimisation of your media files is made lossless, your files will not be compressed. The optimisation only strips meta data and for displaying unnecessary data to shrink the filesize. To compress the files, you should still use the shopware internal compression.

Apparent from our tests, another compression by these tools won't shrink your files again, so it's enough to compress them by using the Shopware settings and just let the tools optimize the files lossless.

The optimisation task will run after each upload of an image and after its thumbnail creation.

## Optimize existing files using the CLI

The console command `sw:media:optimize` scans your `media` directory for files and chooses an appropriate runner to optimize the file. The decision, which runner should be used, depends on the mime-type of the file. Each runner accepts at least one mime-type, e.g. `image/jpeg`. The order of runners is defined by the priority in the dependency injection container. See [below](#tag-the-optimizer) for more information.

### Display available optimizer

Each optimizer has the method `isRunnable()` which indicates, if the job is runnable. The built-in optimizer like `jpegtran` do a simple check if the binary is present. To display all available optimizer, add the `--info` (`-i`) option.

```bash
$ bin/console sw:media:optimize --info

+-----------+----------+-----------------------+
| Optimizer | Runnable | Supported mime-types  |
+-----------+----------+-----------------------+
| jpegoptim | Yes      | image/jpeg, image/jpg |
| pngout    | Yes      | image/png             |
| optipng   | Yes      | image/png             |
| jpegtran  | Yes      | image/jpeg, image/jpg |
| pngcrush  | Yes      | image/png             |
+-----------+----------+-----------------------+
```

### Change media directory

By default, the optimizer will search files within the `media` directory. Additionally, the first argument can be another path. So if your media files are placed in `web/media`, you can provide `web/media` as first argument for this command.

### Filter files by creation date

A full scan of files can take a long time to complete. For that, there is a `--modified` (`-m`) option to filter a range by providing a [PHP compatible time string](https://secure.php.net/manual/en/datetime.formats.php). Here are some examples:

#### Files of the last 24 hours

```bash
$ bin/console sw:media:optimize --modified="after 24 hours ago"
```

#### Files of the last 2 weeks

```bash
$ bin/console sw:media:optimize --modified="after 2 weeks ago"
```

### Skip initial scan

To provide an accurate progress bar of the task, it counts all files within the directory beforehand. In cases of large media libraries, you can skip that initial scan by adding the option `--skip-scan`. The optimisation process will start immediately.

## Example: Create optimizer using a HTTP API

Each optimizer must implement the interface `Shopware\Bundle\MediaBundle\Optimizer\OptimizerInterface` and must be tagged with `shopware_media.optimizer` in the dependency injection container.

If there is more than one optimizer available for a specific mime-type, Shopware uses the optimizer with the highest **priority** defined in the `<tag/>` attribute.

Here is a small example of how an API optimizer could work.

### API implementation

**HttpOptimizer.php**

```php
<?php

namespace SwagMyOptimizer;

use Shopware\Bundle\MediaBundle\Optimizer\OptimizerInterface;

class HttpOptimizer implements OptimizerInterface
{
    public function getName()
    {
        return 'my_http_optimizer';
    }

    public function run($filepath)
    {
        $postData = [
            'file' => '@' . $filepath
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://api.myhttpoptimizer.io/optimize');
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $optimizedImage = curl_exec($ch);

        file_put_contents($filepath, $optimizedImage);
    }

    public function getSupportedMimeTypes()
    {
        return ['image/jpeg', 'image/png'];
    }

    public function isRunnable()
    {
        // we assume that the API is always available
        return true;
    }
}
```

### Tag the optimizer 

The goal is to use our `HttpOptimizer` in first place, so the priority must be set to a higher value than the provided optimizer by Shopware.

```xml
<service id="SwagMyOptimizer.http_optimizer" class="SwagMyOptimizer\HttpOptimizer">
    <tag name="shopware_media.optimizer" priority="500" />
</service>
```

### Verify availability

You can verify that your optimizer is registered by running the command with the `--info` option.

```bash
$ bin/console sw:media:optimize --info
+-------------------+----------+-----------------------+
| Optimizer         | Runnable | Supported mime-types  |
+-------------------+----------+-----------------------+
| my_http_optimizer | Yes      | image/jpeg, image/png |
| pngout            | Yes      | image/png             |
| jpegoptim         | Yes      | image/jpeg, image/jpg |
| optipng           | Yes      | image/png             |
| jpegtran          | Yes      | image/jpeg, image/jpg |
| pngcrush          | Yes      | image/png             |
+-------------------+----------+-----------------------+
```