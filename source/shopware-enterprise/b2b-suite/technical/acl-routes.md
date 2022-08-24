---
layout: default
title: ACL & Routing
github_link: shopware-enterprise/b2b-suite/technical/acl-routes.md
indexed: true
menu_title: ACL & Routing
menu_order: 20
menu_style: numeric
menu_chapter: true
group: Shopware Enterprise
subgroup: B2B-Suite
subsubgroup: Technical Documentation
---

<div class="toc-list"></div>

## Introduction

The ACL Routing component allows you to block Controller Actions for B2B-Users. It relies on and extends the technologies 
already defined by the ACL component. To accomplish this, the component directly maps an `action` in a given `controller` 
to a `resource` (= entity type) and `privilege` (= class of actions). There are two core actions you should know about. 

## Registering routes

All routes that need access rights need to be stored in the database. The B2B-Suite provides a service too simplify this process. For the service to work correctly you need an array in a specific format. This array needs to be structured like this:

```php
$myAclConfig =  [
    'contingentgroup' => //resource name
    [
        'B2bContingentGroup' => // controller name
        [
            'index' => 'list', // action name => privilege name
            [...]
            'detail' => 'detail',
        ],
    ],
];
```

This configuration array can then be synced to the database by using this service during installation:

```php
Shopware\B2B\AclRoute\Framework\AclRoutingUpdateService::create()
    ->addConfig($myAclConfig);
```

This way you can easily create and store the resources. Of course in order to show a nice frontend you need to provide snippets for translation too. The snippets get automatically created from resource and privilege names and are prefixed with `_acl_`. So the resource `contingentgroup` needs a translation named `_acl_contingentgroup`.

## Privilege names
 
 The default privileges are:
 
| Privilege Name        | What it means                                                                                |
| :-------------------- |:-------------------------------------------------------------------------------------------- |
| list                  | entity listing, (e.g. indexActions, gridActions)                                             |
| detail                | disabled forms, lists of assignments, but only the inspection, not the modification          |
| create                | creation of new entities                                                                     |
| delete                | removal of existing entities                                                                 |
| update                | updating/Changing existing entities                                                          |
| assign                | changing the assignment of the entity                                                        |
| free                  | no restrictions                                                                              |
 
It is quite natural to map CRUD Actions like this. However, assignment is a little less intuitive. This should help:
  
* All assignment controllers belong to the resource of the right side of the assignment (e.g. `B2BContactRole` controller is part of the `role` resource).
* All assignment listings have the detail privilege (e.g. `B2BContactRole:indexAction` is part of the `detail` privilege).
* All actions writing the assignment are part of the assign privilege (e.g. `B2BContactRole:assignAction` is part of the `assign` privilege).
 
## Automatic generation
 
You can autogenerate this format with the `RoutingIndexer`. This service expects a format that is automatically created by the IndexerService. This could be a part of your deployment or testing workflow.
  
```php
require __DIR__ . '/../B2bContact.php';
$indexer = new Shopware\B2B\AclRoute\Framework\RoutingIndexer();
$indexer->generate(\Shopware_Controllers_Frontend_B2bContact::class, __DIR__ . '/my-acl-config.php');
```
 
The generated file looks like this:
  
```php
'NOT_MAPPED' => //resource name
      array(
          'B2bContingentGroup' => // controller name
              array(
                  'index' => 'NOT_MAPPED', // action name => privilege name
                  [...]
                  'detail' => 'NOT_MAPPED',
              ),
      ),
```
  
If you spot a privilege or resource that is called `NOT_MAPPED`, the action is new and you have to update the file to add the correct privilege name.
 
## Template extension
 
The ACL implementation is safe at the php level. Any route you have no access to will automatically be blocked but for a better user experience you should also extend the template to hide inaccessible actions.
 
 ```html
 <a href="{url action=assign}" class="{b2b_acl controller=b2broleaddress action=assign}">
 ```
 
This will add a few vital css classes:
 
Allowed actions:
```html
<a [...] class="is--b2b-acl is--b2b-acl-controller-b2broleaddress is--b2b-acl-action-assign is--b2b-acl-allowed"/>
```
Denied actions:
```html
<a [...] class="is--b2b-acl is--b2b-acl-controller-b2broleaddress is--b2b-acl-action-assign is--b2b-acl-forbidden"/>
```
  
The default behaviour is then just to hide the link by setting its display property to `display: none;`.
 
But there are certain specials to this:
 
* applied to a `form` tag it will remove the submit button and disable all form items.
* applied to a table row in the b2b default grid it will mute the applied ajax-panel action.
 
## Download
 A simple example plugin can be found <a href="{{ site.url }}/exampleplugins/B2bAcl.zip">here</a>
