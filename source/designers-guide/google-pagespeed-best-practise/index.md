---
layout: default
shopware_version: 5.3.0
title: Google PageSpeed Best Practise
github_link: designers-guide/google-pagespeed-best-practise/index.md
indexed: true
group: Frontend Guides
subgroup: Tutorials
menu_title: Google PageSpeed
menu_order: 55
---

<div class="toc-list"></div>

## Introduction

PageSpeed is a tool by Google to indicate the performance of a website. It checks the site for some of the best practice techniques in web development. Google wants to ensure that every site offers a great experience for the user. It is not clear, if the ranking of the tool realy affects the visibility of the site in the Google search algorithm. Although the performance of your website has an affect at the bounce rate and the user experience. So a better ranking in the PageSpeed tool is always a good goal you should work on.

This guide will cover some key metrics which have a great impact on the score and how to optimize them.

## Images

The size and implementation of images have a great impact on the Google PageSpeed score. If they are too big in file size or to big in dimensions for the viewport, you'll get an error like `Compressing and resizing xx could save xxKiB (xx% reduction)`.

### Compression

Like the error already says, your images should be compressed either using external services or with the built-in image compression and optimization in Shopware. 

If your server has tools available like `optipng` or `jpegtran`, they already optimize your thumbnails using these tools. The compression of the tools is disabled by default as it can be managed in an album in the media manager.

![Media Manager thumbnail generation](../responsive-images/media-manager.png)

**Recommendation for the best score**

* Install `optipng` on your server
* Install `jpegtran` on your server
* *Optional:* Run `sw:media:optimize` to optimize all existing images in the Shopware media folder

### Thumbnails

Talking about thumbnails, you may want more thumbnail sizes to give the browser more options to decide from. 

**Example**

An image is shown with a width of `201px` and your thumbnail sizes are `200x200` and `600x600`. The browser will then fetch the bigger `600x600` image, because the `200x200` would be too small, even if it's just 1 pixel. Google PageSpeed then complains about the requested image is too big for the given viewport.

For this case, you should add more thumbnail sizes to provide the browser a more granular set of thumbnails to decide from.

Keep in mind, that the more thumbnail sizes you define, the longer it will take to upload a picture to the media manager. In addition, an uploaded image requires more disk space since more thumbnail files will be generated.

**Recommendation for the best score**

* Generate thumbnails every 100px - 150px. `100x100`, `200x200`, `300x300`, `400x400`, ... 
* All available thumbnail sizes are automatically provided in the view, so there is no need to modify template files. 

### Responsive images

Most images in Shopware provide the full range of the defined thumbnail sizes so the browser can choose which one to display. But to select the suitablein addition, the browser needs to know, how big the image that will be displayed is.

**Recommendation for the best score**

If you develop your own custom theme, you should follow our [Responsive images guide](/designers-guide/responsive-images/) to implement your images correctly.

## CSS

PageSpeed expects that your site shows useful content in the least amount of time. This means, that your website should be renderable without additional network round trips.

Shopware delivers all theme styles in a single CSS file which will be loaded on page load. This works well as we only have 1 request to fetch all styles, but it reduces the score as the rendering is blocked until the styles have been fully loaded.

**Recommendation for the best score**

Google explains the concept of **Critical CSS** where the smallest needed part of CSS will be rendered into the DOM inside your `<head/>` element. The content of the style should contain everything to render the first visible part your website without loading any other remote styles.

Open your website and locate all components which are finally visible for all viewports. Then, gather all needed styles to show this page and put the styles into the `<head/>` element.

You can test your changes by temporarily removing all remote styles from your theme and reload the website. The visible part should not have changed.

## JavaScript

Similar to the CSS section above, JavaScript is a blocking member too. Therefore JavaScript resources should be loaded asynchronously.

Shopware compiles all JavaScript files into a single file which will be loaded asynchronously. That also means, that libraries like jQuery are not instantly available on page load. 

**Recommendation for the best score**

If your script depends on Shopware modules or libraries, you should [add your files to the theme compile process](/designers-guide/css-and-js-files-usage/). It will be then included into the single JavaScript file and includes all dependencies.

In case you can't add your files to the compile process, you should include your files with the `async` attribute.

```
<script src="https://..." async>
```

## Further Resources

* [Responsive Images](/designers-guide/responsive-images/)
* [PageSpeed Rules and Recommendations](https://developers.google.com/web/fundamentals/performance/critical-rendering-path/page-speed-rules-and-recommendations)
* [Critical Rendering Path](https://developers.google.com/web/fundamentals/performance/critical-rendering-path/)
* [Render Blocking CSS](https://developers.google.com/web/fundamentals/performance/critical-rendering-path/render-blocking-css)