---
layout: default
title: Using the Responsive theme default components
github_link: designers-guide/responsive-theme-default-components/index.md
indexed: true
---

##Introduction
Within the new Shopware 5 Responsive Theme we provide you many reusable components for easier template and plugin development. The default components let you create e.g. buttons. If you are already familiar with front-end-frameworks like Bootstrap you will understand Shopware's components even more quickly. This guide gives you a quick overview of the usage from the most important components. 

## Examples
-   [Buttons](#buttons)
-   [Panels](#panels)
-   [Icons](#icons)

### Buttons
Creates a styled button
```
<a class="btn">Button</a>
<a class="btn is--primary">Primary Button</a>
<a class="btn is--secondary">Secondary Button</a>
```
Additional classes: `is--large` for a larger button, `is--small` for a smaller button

### Panels
Creates a Panel with a title bar
```
<div class="panel has--border is--rounded">
	<div class="panel--title>"Panel Title</div>
	<div class="panel--body">Panel Content</div>
</div>
```

### Icons
The Shopware 5 Responsive theme provides you a large amount of webfont icons. You can use them by adding an `<i>`-Element with a class prefixed with `icon--`
```
<i class="icon--star"></i>
```