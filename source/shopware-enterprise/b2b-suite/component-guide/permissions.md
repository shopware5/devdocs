---
layout: default
title: Permissions
github_link: shopware-enterprise/b2b-suite/component-guide/permissions.md
indexed: true
menu_title: Permission Management
menu_order: 10
menu_chapter: true
group: Shopware Enterprise
subgroup: B2B-Suite
subsubgroup: Component Guide
---

Direct-Link to the module: `my-shop.de/b2bcontact`

The contact module also allows to define permissions for contacts. The permissions can be set by component.
Notice: The Debtor account does not experience any ACL behaviour. A Debtor is always the Superadmin.

The default behaviour is to block all access. Which means that all access control panels are 
whitelisting additional resources or actions.

To grant privileges you have to assign a role or a permission to a contact. Important to know is, that
direct granted privileges to a contact ranked higher than role privileges.

<img src="{{ site.url }}/assets/img/b2b-suite/v2/contact-detail-permission.png" style="width: 100%"/>

## Assignment Types
* Grant privilege
* Inherit privilege
 
We decide between two types of privilege. To grant access to a specific component there is the checkbox
which is flagged by a checkmark. 
The second option allows the user to grant his privilege to other users. This option ist flagged by the
forward icon.
