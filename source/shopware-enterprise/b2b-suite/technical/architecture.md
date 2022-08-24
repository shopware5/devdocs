---
layout: default
title: System architecture
github_link: shopware-enterprise/b2b-suite/technical/architecture.md
indexed: true
menu_title: System architecture
menu_order: 1
menu_style: numeric
menu_chapter: true
group: Shopware Enterprise
subgroup: B2B-Suite
subsubgroup: Technical Documentation
---

<div class="toc-list"></div>

## Description

The B2B-Suite is a collection of loosely coupled mostly uniform **components** packaged with a small example plugin and a common library.

## Component layering

A single component with all layers and the maximum of allowed dependencies looks like this:

![image](/assets/img/b2b/architecture-component.svg)

The responsibilities from bottom to top:

  Layer        | Description
 :------------ | -----------
  Shop-Bridge  | Bridges the broad Shopware interfaces to the specific framework requirements <ul><li>Implements interfaces provided by the framework</li><li>Subscribes to shopware events and calls framework services</li><ul>
  Framework    | Contains the B2B specific Domain Requirements <ul><li>CRUD and assignment service logic</li><li>The specific use cases of the component</li></ul>
  REST-API     | REST access to the services
  Frontend     | Controller as a service for frontend access
  B2B-Plugin   | Store front access to the services

> Please notice: Apart from framework all other layers and dependencies are optional.

## Component dependencies

At the time of this writing there are 18 different components, all build with the same structure. We sorted these components into four different complexes:

#### Common - The one Exception

There is a small library of shared functionality. It contains a few commonly used **technical** implementations that are shared between most components like exception classes, repository helpers, a dependency manager, or a REST-API router.

#### User-Management

The user management is based on the `StoreFrontAuthentication` component and then provides `Contact` and `Debtor` entities which have `Address`es and `Role`s. These entities are mostly informational and CRUD based. Other parts of the system only depend on the `StoreFrontAuthentication` component but not the specific implementations as debtor or contact.

![image](/assets/img/b2b/architecture-users.svg)

#### ACL

The `Acl` implementation is connected to most other entities provided by the B2B-Suite.

![image](/assets/img/b2b/architecture-acl.svg)

#### Order and Contingent Management

`ContingentGroups`s are connected to `Debtor`s and can have `Acl` settings based on `Role`s or `Contact`s. `Order`s are personalized through the `StoreFrontAuthentication`.

![image](/assets/img/b2b/architecture-order.svg)

#### The whole picture

Most dependencies are directly derived from requirements. So, the dependency flow of the components should follow the basic business needs. There are a few exception, mainly the M:N assignment components each representing a reset in complexity where a complex feature just resolves itself into a context object for another use case. You can think of it like that.

* A Debtor has can be created and updated through a service **=>** _The debtor is an **entity**_
* A Debtor may be an entity connected to many workflows by it's id **=>** _The Debtor is just the **context**_

So - for the sake of completeness - this is the whole picture:

![image](/assets/img/b2b/architecture-components-complete.svg)

Everything you should get from that is, that there is a left to right propagation of dependencies. The components on the left side can be used and even useful entirely without the components on the right side.
