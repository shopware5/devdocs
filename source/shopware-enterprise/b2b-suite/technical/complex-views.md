---
layout: default
title: Complex views
github_link: shopware-enterprise/b2b-suite/technical/complex-views.md
indexed: true
menu_title: Complex views
menu_order: 9
menu_style: numeric
menu_chapter: true
group: Shopware Enterprise
subgroup: B2B-Suite
subsubgroup: Technical Documentation
---

<div class="toc-list"></div>

## Description

The B2B-Suite comes with a whole User Interface providing administration like features in the frontend. The structure is reflected in the naming of the several controller classes. Each controller then uses a canonical naming scheme. The example below shows the *ContactController* with all its assignment controllers.

![image](/assets/img/b2b/contact-controller-complex-example.svg)

As you can see, every controller is associated with one specific component.


## Controller structure

The controller naming is very straightforward. It always looks like this:

```sh
B2bContact - contact listing
├── B2bContactRole - role <-> contact assignment
├── B2bContactAddress - address <-> contact assignment
├── B2bContactContingent - contingent <-> contact assignment
├── B2bContactRoute - route <-> contact assignment
```

We distinguish here between *root controller* and *sub controller*. A root controller does not require parameters to be passed to it, it provides a basic page layout and CRUD actions on a single entity. Contrary a sub controller depends on a context (usually a selected id) from requests and provides auxiliary actions, like assignments, in this context.

## Root controller

The root controller usually looks like this:

```php
<?php

class RootController
{
    /**
     * Provides the page layout and display a listing containing the entities
     */
    public function indexAction() { [...] }

    /**
     * Display an empty form or optionally errors and the invalid entries
     */
    public function newAction() { [...] }

    /**
     * Post only!
     *
     * Store new entity, if invalid input forward to `newAction`, if successful forward to `detailAction`
     */
    public function createAction() { [...] }

    /**
     * Provides the detail layout. Usually a modal box containing a navigation and initially selecting the `editAction`
     *
     */
    public function detailAction() { [...] }

    /**
     * Display the Form containing all stored data
     */
    public function editAction() { [...] }

    /**
     * Post only!
     *
     * Store updates to the entity, forward to `editAction`
     */
    public function updateAction() { [...] }

    /**
     * Post only!
     *
     * Removes a record, Forwards to `indexAction`
     */
    public function removeAction() { [...] }
}
```

As you can see there are a few `POST` only actions, these are solely for data processing and do not have a view of there own. This decision was made to provide smaller and easier to understand methods, easing the handling for extension developers. So actually there are less views then actions:

```sh
├── index.tpl - the listing grid
├── detail.tpl - the modal dialog layout with navigation and extends modal.tpl
├── edit.tpl - edit an existing entity and extends modal.tpl
├── _edit.tpl - extends modal-content.tpl
├── new.tpl - extends modal.tpl
├── _new.tpl - extends modal-content.tpl
├── _form.tpl - the internal usage only form for edit and new
```


## Sub controller

The sub controller depends on parameters to get the context it should act on. A typical assignment controller looks like this:

```php
<?php

class SubController
{
    /**
     * Provides the layout for the controller and contains the listing
     */
    public function indexAction() { [...] }

    /**
     * Post only!
     *
     * Assign two id's to each other
     */
    public function assignAction() { [...] }
}
```

Since `POST` only actions never have views, these controllers only have two views:

```sh
├── index.tpl - contains entity listing
```

## Tips for ajax panel views

* HTML is a data representation language, use it that way!
* Don't break the standard DOM
* Don't break the standard event flow. (e.g don't omit a submit on a form) 
* jQuery is a DOM manipulator, but the big stuff is already manipulated by `ajax-panel`. You should be able to keep it to the small stuff.
* Prefer real events, instead of delegated ones. By that your handlers will always be called before ajax-panel intercepts.
* Put them into small, tiny units of code. jQuery is very powerful, and already accomplishes very much in a single statement.
* if you need a *static context* for storage use `this.$el.closest('.b2b--ajax-panel').data('foo', 'bar')`. It represents the static context you are looking for.
* use events for communication... obviously.

## Modal Component

You can find more information about our modal component in this article: <a href="{{ site.url }}/shopware-enterprise/b2b-suite/technical/modal/">B2B-Suite Modal Component</a>
