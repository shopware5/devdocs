---
layout: default
title: Demo Environment
github_link: shopware-enterprise/b2b-suite/component-guide/demo-environment.md
indexed: true
menu_title: Demo Environment
menu_order: 1
menu_chapter: true
group: Shopware Enterprise
subgroup: B2B-Suite
subsubgroup: Component Guide
---

<div class="toc-list"></div>

## Description

In our demo environment, we have several demo accounts to test the B2B-Suite. In the following tables all described users
have the password "shopware". The debtor is the only user which have up to ten entries, so there is no pagination in the frontend. 
<br />contact1@example.com is the user with all possible rights
<br />contact9@example.com is an inactive contact with no rights.


Debtor | Contact | Roles | Contingent-Groups
---|---|---|---
debtor@example.com | contact1@example.com | 10.000 € each month | 10 orders each month
 | contact2@example.com | 1/2 Billing Addresses | 10.000 € each month
 | contact3@example.com | All Billing Addresses | 2 items, 2 orders and 2.000 € per day
 | contact4@example.com | All Shipping Addresses | 
 | contact5@example.com | All listings, details, updates and assignments |
 | contact6@example.com | All routes | 
 | contact7@example.com | All listings, details and updates |
 | contact8@example.com | All listings and detail routes | 
 | contact9@example.com | All listing routes | 

## contact1@example.com
### Roles
* All Billing Addresses
* All Shipping Addresses
* All routes
* 10.000 € each month
### Orders
*  3 Orders with Status open, Clearance open and Clearance denied

## contact2@example.com
### Roles
* All listing and detail routes
* 1/2 Billing Addresses
### Shipping-Address
* Musterstr. 2
### Contingent-Group
* 2 items, 2 orders and 2.000 € per day

## contact3@example.com
* no rights


The debtor 2 is the only user which have more than ten entries, so there is a pagination in the frontend.
<br />contact11@example.com is the user with all possible rights
<br />contact21@example.com is an inactive contact without any permissions.

Debtor | Contact | Roles | Contingent-Groups
---|---|---|---
debtor@example.com | contact11@example.com | All Day Contingents | 15.000 € | 15.000 € per quarter
 | contact12@example.com | 1/2 Billing Addresses | 15 orders | 15 orders per quarter
 | contact13@example.com | 1/2 Shipping Addresses | 15 items | 15 items per quarter
 | contact14@example.com | All Billing Addresses | 5.000 € | 5.000 € per week
 | contact15@example.com | All Shipping Addresses | 5 orders | 5 orders per week
 | contact16@example.com | All routes | 5 items | 5 items per week
 | contact17@example.com | All listings, details and updates | 1337 € | 1337 € per day
 | contact18@example.com | All listings and detail routes | 2 orders per day | 2 orders per day
 | contact19@example.com | All listing routes | 2 items | 2 items per day
 | contact20@example.com | All Month Contingents | 10.000 € each month | 
 | contact21@example.com | All Quarter Contingents | 2 items, 2 orders and 2.000 € per day | 
 |  |  All listings, details, updates and assigns |   | 

## contact11@example.com
### Roles
* All Billing Addresses
* All Shipping Addresses
* All Routes
### Contingent-Group
* 15.000 € per quarter
* 15 orders per quarter
* 15 items per quarter

## contact12@example.com
### Roles
* All listing and detail routes
* 1/2 Billing Addresses
* 1/2 Shipping Addresses
### Contingent-Group
* 1337 € per day
* 2 orders per day
* 2 items per day


## contact13@example.com
### Roles
* All listing and detail routes
v1/2 Billing Addresses
* 1/2 Shipping Addresses
* All Quarter Contingents
* All Day Contingents
### Contingent-Group
* 1337 € per day
* 2 orders per day
* 2 items per day
* 15.000 € per quarter
* 15 orders per quarter
* 15 items per quarter
