---
layout: default
shopware_version: 5.3.0
title: Google Pagespeed Best Practise
github_link: designers-guide/google-pagespeed-best-practise/index.md
indexed: true
group: Frontend Guides
subgroup: General Resources
menu_title: Google Pagespeed
menu_order: 55
---

<div class="toc-list"></div>

## Introduction

Google Pagespeed is a great tool to get the best performance for your website. It analysis an url and gives your suggestion to speed up your website.

This guide will cover some key metrics which have a great impact on the score and how to optimize them.

## Images

The size and implementation of images have a great impact on the Google Pagespeed score. If they are too big in file size or to big in dimensions for the viewport, you'll get an error like `Compressing and resizing xx could save xxKiB (xx% reduction)`.

### Compression

Like the error already says, your images should be compressed either using external services or with the built-in image compression and optimization in Shopware. 

If your server has tools available like `optipng` or `jpegtran`, they already optimize your thumbnails using these tools. The compression of the tools is disabled by default as it can be managed in an album in the media manager.

**Recommendation for the best score**

* Install `optipng` on your server
* Install `jpegtran` on your server
* *Optional:* Run `sw:media:optimize` to optimize all existing images on the server

### Thumbnails

Talking about thumbnails, you may want more thumbnail sizes to give the browser more options to decide from. 

**Example**

An image is shown with a width of `201px` and your thumbnail sizes are `200x200` and `600x600`. The browser will then fetch the bigger `600x600` image, because the `200x200` would be too small, even if it's just 1 pixel. Google Pagespeed then complains about the requested image is too big for the given viewport.

For this case, you should add more thumbnail sizes to provide the browser a more granular set of thumbnails to decide from.

Keep in mind, that the more thumbnail sizes you define, the longer it will take to upload a picture to the media manager. In addition, an uploaded image requires more disk space since more thumbnail files will be generated.

**Recommendation for the best score**

* Generate thumbnails every 100px - 150px. `100x100`, `200x200`, `300x300`, `400x400`, ... 
* All available thumbnail sizes are automatically provided in the view, so there is no need to modify template files. 

### Responsive images

Now that there are more thumbnails to choose from, the browser needs to know, how big the image will be displayed.

**Recommendation for the best score**

There is a guide on how to work with responsive image including the attributes `srcset` and `sizes`.

Please refer to the [Responsive images](/designers-guide/responsive-images/) guide.

## CSS

Shopware delivers all theme styles in a single CSS file which will be loaded on page load. This works well as we only have 1 request to fetch all styles, but for Google Pagespeed, it is a nightmare as you will be faced with the `above-the-fold content` error message.

Google Pagespeed expects that your site is fully renderable without additional network round trips. That means that your fully rendered site should not differ much from the intial render.

**Recommendation for the best score**

Google explains the concept of **Critical CSS** where the smallest needed part of CSS will be rendered into the DOM inside your `<head/>` element. The content of the style should contain everything to render the first visible part your website without loading any other remote styles.

* Get the smallest needed part of your styles
* Insert your critical CSS into the `<head/>` element

## Javascript

async
nicht auf jQuery dependen da async geladen
in compiler reinpacken
