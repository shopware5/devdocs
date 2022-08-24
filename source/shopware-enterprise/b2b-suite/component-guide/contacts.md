---
layout: default
title: Contacts
github_link: shopware-enterprise/b2b-suite/component-guide/contacts.md
indexed: true
menu_title: Contacts
menu_order: 4
menu_chapter: true
group: Shopware Enterprise
subgroup: B2B-Suite
subsubgroup: Component Guide
---

This component is managed in the company module, direct link to the company module: `my-shop.de/b2bcompany`.

The contact module allows to manage your contacts. Contacts are allowed to login to the shop 
frontend with their given credentials. Users with permissions can assign roles to the contact 
which grant or revoke defined permissions. All role permissions can be extended by direct 
contact permissions, but it is recommended to set the permissions via roles.

<img src="{{ site.url }}/assets/img/b2b-suite/v2/contact-index.png" style="width: 100%"/>

#### Feature Overview
* Create / Edit / Delete contacts
* Edit master data
* Change billing address
* Set default billing address
* Change shipping address
* Set default shipping address
* Manage roles
* Manage permissions
* Manage contingents
* Manage order lists
* Manage budgets

### Add and edit a Contact
To create a new contact you can press on the *Create contact* button. In the modal box you
have to fill in the form. All necessary fields are marked with a star.
After submitting the form you will be forwarded to the 

<img src="{{ site.url }}/assets/img/b2b-suite/v2/contact-detail-master.png" style="width: 100%"/>

#### Change Contact Password
To send a change password mail to a contact, you have to confirm the password activation checkbox and save the data.

<img src="{{ site.url }}/assets/img/b2b-suite/v2/contact-detail-master-passsword-mail.png" style="width: 100%"/>

After saving, a mail with a password activation link will be send.

You can change the `b2bPasswordActivation` mail template in the Shopware Backend.

Following the link in the mail, the customer could change the password of his account.

<img src="{{ site.url }}/assets/img/b2b-suite/v2/contact-password-activation.png" style="width: 100%"/>

### Contact details
To configure the contact settings you can press somewhere on the contact row.
In the detail window you can 
* Edit master data
* Change billing address
* Change shipping address
* Manage contact visibility
* Manage role visibility
* Manage roles
* Manage permissions
* Manage contingents
* Manage Order lists
* Manage Budgets

### Contact Deletions
To remove contacts you can use the trash button to delete the contact which is no longer required.
Since **version 3.0.5** and **version 4.2.1** of the B2B-Suite, removing the contact will also remove the shopware user data and transfer all offers and orders from the contact to the debtor.

### Shopware Backend
The contacts are **not available** in the Backend.
