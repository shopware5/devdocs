---
layout: default
title: Responsive Images in Shopware 5
github_link: designers-guide/responsive-images/index.md
indexed: true
---

<div class="toc-list"></div>

## Introduction

The Shopware 5 default Responsive theme is a fully responsive cross-browser compatible high definition ready theme. This quick tip will introduce you to the "responsive" way of working with images in the Shopware 5 Responsive theme. Working with static widths is a thing of the past. Therefore, the optimal size of the images is often a question. Shopware's solution for this problem is the thumbnail generator in combination with new added template functionality as the `picture` element.

## Thumbnail generation

![Media Manager thumbnail generation](media-manager.png)

The Shopware 5 media manager allows you to upload pictures with large file sizes without having to worry about downsizing them manually. The media manager has a thumbnail generator service that creates thumbnails of your original pictures in sizes you are able to configure beforehand. This ensures a good performance and usability on the different device types. There is also the possibility to create thumbnails that have doubled pixel density, to improve the experience for high dpi display device users. Alternatively you can generate the thumbnails by using the `php bin/console sw:thumbnail:generate` Shopware CLI command. Make sure to always upload the biggest possible version of your original image, as the thumbnail generator is only able to downscale from the original images' size.

### Default product thumbnail sizes:
+ `800 x 800 px`
+ `1280 x 1280px`
+ `1920 x 1920px`

## Usage in the frontend

### Basic image
This example is the Shopware 5 equivalent to the basic `<img src="">` tag. Instead of applying the `src` attribute, we now have the opportunity to use the `srcset`, which allows us to specify the original thumbnail and its high dpi optimized version in the same attribute. Depending on the screen density, one of the two pictures is displayed. In convention the high dpi display image names are suffixed with a `@2x`.

```html
<img srcset="product.jpg, product@2x.jpg 2x" alt="Produktfoto">
```

### Picture element
The `picture` element takes the term "responsive" even a little step further. It is part of the [official W3C standard](http://www.w3.org/html/wg/drafts/html/master/semantics.html#the-picture-element "W3C picture element specifications") and is supported by most of the latest browsers. To ensure support for outdated browsers we are using the `picturefill.js` polyfill. Inside the `picture` element we can define multiple image sources and determine their visibility using a media query like syntax in the `media` attribute. In other words, we can choose which image is displayed at which viewport size. This fits perfectly well with the thumbnails that the thumbnail generator is able to create. The `picture` element also contains a default `img` tag, in cases the `media` attributes do not match the browser viewport.

```html
<picture>
    <source media="(min-width: 64em)" srcset="product-large.jpg, product-large@2x.jpg 2px">
    <source media="(min-width: 48em)" srcset="product-medium.jpg, product-medium@2px.jpg 2px">
    <img srcset="product-small.jpg, product-small@2x.jpg 2x" alt="Produktfoto">
</picture>
```