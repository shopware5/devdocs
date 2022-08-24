---
layout: default
title: Roles
github_link: shopware-enterprise/b2b-suite/component-guide/roles.md
indexed: true
menu_title: Roles
menu_order: 6
menu_chapter: true
group: B2B-Suite
subgroup: Component Guide
---

This component is managed in the company module, direct link to the company module: `my-shop.de/b2bcompany`.

The role module allows to define different roles for specific contact types. E.g. for different
departments. In contrast to contacts roles have the possibility to inherit from other roles.

<img src="{{ site.url }}/assets/img/b2b-suite/v2/role-index.png" style="width: 100%"/>

#### Feature overview
* Create / Edit / Delete roles
* Edit master data
* Manage permissions
* Change billing address
* Change shipping address
* Manage Budgets

### Add and edit a role

To add a role you can click on the "Create role" button which will open a new form.
After submitting the creation form you will be forwarded to the role configuration.

<img src="{{ site.url }}/assets/img/b2b-suite/v2/role-detail-master.png" style="width: 100%"/>

### Role detail

After you created the role you can access multiple settings for the created role:
* Master data
* Manage permissions
* Manage contact visibility
* Manage role visibility
* Billing addresses
* Shipping addresses
* Manage contingents
* Manage order lists
* Manage budgets

<img src="{{ site.url }}/assets/img/b2b-suite/v2/role-detail-billing.png" style="width: 100%"/>

### Role deletions
To remove roles you can use the trash button to delete the role which is no longer required.

### Inheritance

<img src="{{ site.url }}/assets/img/b2b-suite/v2/role-inheritance.png" style="width: 100%"/>

The permissions of a role will be implicit inherit from other roles.
E.g. (compared to the screenshot) The Role "Research & Development" inherits implicit the permissions of the roles
"IT-Administration", "Technical Engineering" and "Product Development". If the "Technical Engineering" has the
permission to edit order lists, the "Research & Development" role has no need to get these permission explicit.
It is possible to drag'n'drop a role before, after or as a child role. 
