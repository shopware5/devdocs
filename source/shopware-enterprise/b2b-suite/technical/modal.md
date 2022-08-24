---
layout: default
title: Modal Component
github_link: shopware-enterprise/b2b-suite/technical/modal.md
indexed: true
menu_title: Modal Component
menu_order: 17
menu_style: numeric
menu_chapter: true
group: Shopware Enterprise
subgroup: B2B-Suite
subsubgroup: Technical Documentation
---

<div class="toc-list"></div>

## Description

In this article we explain the B2B modal component. We are using the modal view for an entity detail information window
which holds additional content for the selected grid item. We use two different templates for this approach. 
The base modal template `(_base/modal.tpl)` is responsible for the base structure of the modal box. In this template you
can find multiple smarty blocks which are for the navigation inside the modal and the content area.

In the B2B-Suite the content block will be extended with the second modal template `(_base/modal-content.tpl)`. The content
template can be configured with different variables to improve the user experience with a fixed top and bottom bar. We are using
this bars for filtering, sorting and pagination. 

There are many advantages to extend both templates instead of building your own modal view.
* Responsive styling for all viewports
* Same experience for every view
* No additional CSS classes required
* Easy modal adaptions because every view using the same classes


The modal component comes with different states:

* Simple content holder
* Content delivered by an ajax panel
* Split view with sidebar navigation and an ajax ready content
* Fixed top and bottom bar for action buttons and pagination

## Modal with simple content

```
{namespace name=frontend/plugins/b2b_debtor_plugin}

{extends file="frontend/_base/modal.tpl"}

{block name="b2b_modal_base" prepend}
    {$modalSettings.navigation = false}
{/block}

{block name="b2b_modal_base_navigation_header"}
    Modal Title
{/block}

{block name="b2b_modal_base_content_inner"}
    Modal Content
{/block}
```
  
## Modal with Navigation

If you would like to have a navigation sidebar inside the modal window you can set the navigation variable to `true`.

```
{namespace name=frontend/plugins/b2b_debtor_plugin}

{extends file="frontend/_base/modal.tpl"}

{block name="b2b_modal_base" prepend}
    {$modalSettings.navigation = true}
{/block}

{block name="b2b_modal_base_navigation_header"}
    Modal Title
{/block}

{block name="b2b_modal_base_navigation_entries"}
    <li>
        <a class="b2b--tab-link">
            Navigation Link
        </a>
    </li>
{/block}

{block name="b2b_modal_base_content_inner"}
    Modal Content
{/block}
```

## Modal with Navigation and Ajax Panel Content

```
{namespace name=frontend/plugins/b2b_debtor_plugin}

{extends file="frontend/_base/modal.tpl"}

{block name="b2b_modal_base" prepend}
    {$modalSettings.navigation = true}
{/block}

{block name="b2b_modal_base_navigation_header"}
    Modal Title
{/block}

{block name="b2b_modal_base_navigation_entries"}
    <li>
        <a class="b2b--tab-link">
            Navigation Link
        </a>
    </li>
{/block}

{block name="b2b_modal_base_content_inner"}
    <div class="b2b--ajax-panel" data-id="example-panel" data-url="{url}"></div>
{/block}
```

### Ajax Panel template for modal content

The modal content template has different options for fixed inner containers. The top and bottom bar can be enabled or disabled.
The correct styling for each combination of settings will be applied automatically so u dont have to take care of styling.
We use the topbar always for action buttons like "Create element". The bottom bar could be used for pagination for example. 

```
{namespace name=frontend/plugins/b2b_debtor_plugin}

{extends file="parent:frontend/_base/modal-content.tpl"}

{block name="b2b_modal_base_settings"}
    {* Enables actions topbar inside the content area of a grid modal component *}
    {$modalSettings.actions = true}

    {* Enables content padding inside the inner content area of a grid modal component *}
    {$modalSettings.content.padding = true}

    {* Enables bottom actions inside the content area of a grid modal component *}
    {$modalSettings.bottom = true}
{/block}

{block name="b2b_modal_base_content_inner_topbar_headline"}
    Modal Content Headline
{/block}

{block name="b2b_modal_base_content_inner_scrollable_inner_actions_inner"}
    Modal Actions
{/block}

{block name="b2b_modal_base_content_inner_scrollable_inner_content_inner"}
    Modal Content
{/block}

{block name="b2b_modal_base_content_inner_scrollable_inner_bottom_inner"}
    Modal Bottom
{/block}
```
