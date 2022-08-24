---
layout: default
title: Ajax panel
github_link: shopware-enterprise/b2b-suite/technical/ajax-panel.md
indexed: true
menu_title: Ajax panel
menu_order: 8
menu_style: numeric
menu_chapter: true
group: Shopware Enterprise
subgroup: B2B-Suite
subsubgroup: Technical Documentation
---

<div class="alert alert-info">
You can download a plugin showcasing the topic <a href="{{ site.url }}/exampleplugins/B2bAjaxPanel.zip">here</a>. 
</div>

<div class="toc-list"></div>

## Description

`AjaxPanel` is a jQuery based extension to the Shopware Frontend Framework. It mimics `iFrame` behaviour by integrating content from different controller actions through ajax into a single view and intercepting, usually page changing, events and transforming them into XHR-Requests.

The diagram below shows how this schematically behaves:

![image](/assets/img/b2b/ajax-panel-abstract.svg)

## Basic usage

The `AjaxPanel` plugin is part of the b2b frontend and will scan your page automatically for the trigger class `b2b--ajax-panel`. The most basic ajax panel looks like this:

```html
<div
    class="b2b--ajax-panel"
    data-url="{url controller="b2bcontact" action="grid"}"
>
    <!-- will load content here -->
</div>
```

After `$(document).ready()` is triggered it will trigger a XHR GET-Request and replace it's inner html with the responses content. Now all clicks on links and form submits inside the container will be changed to XHR-Requests. A streamlined example of this behaviour can be found in the [B2BAjaxPanel Example Plugin](/exampleplugins/B2bAjaxPanel.zip), but it is used across the B2B-Suite.

## Extended usage

### Make links clickable

Any HTML element can be used to trigger a location change in a ajax panel, just add a class and set a destination:

```html
<p class="ajax-panel-link" data-href="{url controller="b2bcontact" action="grid"}">Click</p>
```

### Ignore links

It might be necessary that certain links in a panel really trigger the default behaviour, you just have to add a class to the link or form:

```html
<a href="http://www.shopware.com" class="ignore--b2b-ajax-panel">Go to Shopware Home</a>

<form class="ignore--b2b-ajax-panel">
    [...]
</form>
```

### Link to a different panel

One panel can influence another one by defining and linking to an id.

```html
 <div ... data-id="foreign"></div>
 <a [...] data-target="foreign">Open in another component</a>
```


## Ajax Panel Plugins

The B2B-Suite comes with a whole library of simple helper plugins to add behaviour the ajax panels.

![image](/assets/img/b2b/ajax-panel-structure.svg)

As you can see, there is the `AjaxPanelPluginLoader` responsible for initializing and reinitializing plugins inside b2b-panels. Let's take our last example and extend it with a form plugin.

```html
<div
    class="b2b--ajax-panel"
    data-url="{url controller="b2bcontact" action="grid"}"
    data-plugins="b2bAjaxPanelFormDisable"
>
    <!-- will load content here -->
</div>
```

This will disable all form elements inside the panel during panel reload.

While few of them add very specific behaviour to the grid or tab's views. There are also a few more commonly interesting plugins.

### Modal

The `b2bAjaxPanelModal` plugin helps opening ajax panel content in a modal dialog box. Let's extend our initial example:

```html
<div
    class="b2b--ajax-panel b2b-modal-panel"
    data-url="{url controller="b2bcontact" action="grid"}"
    data-plugins="b2bAjaxPanelFormDisable"
>
    <!-- will load content here -->
</div>
```

This will open the content in a modal box.

### TriggerReload

Sometimes change in one panel needs to trigger a reload in another panel. This might be the case if you are editing in a dialog and displaying a grid behind it. In this case you can just trigger a reload on other panel id's, just like that:

```html
<div class="b2b--ajax-panel" data-url="{url controller="b2bcontact" action="grid"}" data-id="grid">
    <!-- grid -->
</div>

<div class="b2b--ajax-panel" data-url="{url controller="b2bcontact" action="edit"}" data-ajax-panel-trigger-reload="grid">
    <!-- form -->
</div>
```

Now every change in the form view will trigger a reload in the grid view.

### TreeSelect

This `TreeSelect` plugin allows to display a tree view with enabled drag and drop. In the view the `div`-element needs the class `is--b2b-tree-select-container` and the data attribute `data-move-url="{url action=move}"`. The controller have to implement a move action, which accepts the `roleId`, `relatedRoleId` and the `type`.

Possible types:
* prev-sibling
* last-child
* next-sibling

