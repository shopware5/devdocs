---
layout: default
title: HTML / Smarty coding guidelines
github_link: designers-guide/html-smarty-coding-guidelines/index.md
indexed: true
---


## Parent child naming scheme

As mentioned in the ["CSS coding guidelines" blog post](/blog/2016/08/26/css-coding-guidelines/) we're using a Parent-child naming scheme. A good example is the header of the Shopware storefront, especially the main category navigation. Let us take a closer look here on this simplified version:

```html
<ul class="navigation--list">
    <li class="navigation--entry">
        ....
    </li>
</ul>
```

We're using a component based design and the goal was to create components which can be used throughout the application without modifying them every time you want to use them. The components are encapsulated in itself. The parent child naming scheme substantiate this idea.

## Naming Smarty blocks correctly

Definiting the names of Smarty block seems harder than it is. We're using a pretty straight forward pattern here:

```
[module]_[controller]_[action]_[functionality / purpose]
```
*Syntax on how to define Smarty blocks*

The module always defines the area the block is defined e.g. `backend`, `frontend` or `widget`. The controller name matches the folder the template is defined, which is the controller name actually. The action is the action name in the controller which renders the template.

Let me give you an example. We're right now in the template file `frontend/detail/index.tpl` and we want to create a new Smarty block for the product name. The controller `Detail` uses the action `index` to serves the template.

The block would look like this:

```
{block name="frontend_detail_index_product_name"}
    ...
{/block}
```

## Smarty conditions to add a certain class to an element
You're adding classes to HTML elements all the time. A good example is here the `active` class to give an element an active state for example in navigation bars, tab panels or form elements. What we want to prevent is a space inside the `class` attribute, therefor we came up with the convention to put the space between the classes inside the condition.

```html
<input type="text" name="name" class="input--field{if $sErrorFlag.name} has--error{/if}" />
```

## Defining namespaces for templates
It is possible to define a namespace for the whole template calling the Smarty function `{namespace}`. The function can easily be used in a not intended way, so we defined that the `{namespace}` call will be right under the `{extends}`:

```
{extends file="parent:frontend/index/index.tpl"}
{namespace name="frontend/something/else"}

...
```

## Spaces instead of tabs
Last but not least we're using 4 spaces instead of a tabulator character.


