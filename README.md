# Shopware Development Documentation

## Install
This site is generated with [Sculpin][2], a PHP based static site generator.

First you have to [install Sculpin][3] and run the install command in the project directory.
This can be done via the `init.sh` shell script in the project root.

```
./init.sh
```

This will download sculpin and install the required dependencies.

## Running the website locally

```
./watch.sh
```

This will start a local webserver at <http://localhost:8000/>.
You can use a different port like so:
```
./watch.sh 8001
```

## Automatic Plugin Packaging

Foreach Plugin in the `exampleplugins` directory a corresponding ZIP package will be created that can be installed via the shopware plugin manager.
It is important to put the plugin into the proper Frontend/Backend/Core subdirectory.

### Example

`exampleplugins/Frontend/SwagSloganOfTheDay/` will result in `exampleplugins/SwagSloganOfTheDay.zip` in the generated output directory. Please not that the subdirectory is not part of the resulting directory/filename.

[2]: https://sculpin.io/
[3]: https://sculpin.io/download

## Table of contents in your markdown documents
You have the ability to generate a table of content list for your document. Simply place the following code into your document and a table of contents will appear there magically:

```
<div class="toc-list"></div>
```

If you want, you can customize the behavior of the toc list as well. You can define the text of the headline using the attribute `data-headline` and you can define the max depth of headlines you wanna include into the list with the attribute `data-depth`:

```
<div class="toc-list" data-depth="1" data-headline="Awesome table of contents"></div>
```

## Hiding blog posts from search engines

To add a `<meta name="robots" content="noindex, nofollow" />` to your blog post,
you have to add the following frontmatter entry to your blog post:

```yaml
robots:
    hide: true
```

## Version History
To create a version history table, you simply have to add a `history` array to your metadata.

Example:
```

---
layout: default
indexed: true
...
history:
  2015-11-16: creation
  2015-11-23: added frontend documentation
  2016-01-01: documented millenium bug
---

```

## Algolia Search Configuration

The search is powered by [Algolia](https://www.algolia.com).
Configuration is done via kernel Parameters in `app/config/sculpin_kernel.yml`:

```yaml
# app/config/sculpin_kernel.yml
sculpin_algolia:
  application_id: 'MYAPPLICATION'
  index_name:     'developers.shopware.com_prod'
```

The API Key should be provided via the environment variable `SYMFONY__ALGOLIA_API_KEY`.
To enable the also the environment variable `SYMFONY__ALGOLIA_ENABLED` must exist.

```bash
SYMFONY__ALGOLIA_ENABLED=1 SYMFONY__ALGOLIA_API_KEY=MYAPIKEY ./vendor/bin/sculpin generate
```

These variables are automatically exported during the Travis-CI build for every merge/commit on the `master` branch.


## CSS helper classes
We added a bunch of CSS helper classes which should help you to build simple layouts.

### Warning box

```
<div class="alert alert-warning">
This is a warning
</div>
```

### Centering of content

```
<div class="center">
</div>
```

Alternative:
```
<div class="is--center">
</div>
```

### Floating images

```
<div class="is-float-left">
    <img src="sample.png" alt="Sample image" />
</div>
```

```
<div class="is-float-right">
    <img src="sample.png" alt="Sample image" />
</div>
```
