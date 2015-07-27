---
title: Never stop refactoring
tags: [tech]

categories:
- dev

authors: [dr]
github_link: blog/_posts/2015-05-05-never-stop-refactoring.md
---

Hi, my name is Oliver Skroblin, developer at shopware AG.

In this blog entry, I want to talk about the refactored sections in Shopware 5 and further refactorings coming in the near future.

## Bundles
With Shopware 5, we have taken the opportunity to refactor the core classes. However, we didn't just rewrite the classes using the latest technological standards, but also modernized the underlying architecture of the core. It was our goal to create an architecture that groups, in different namespaces, classes that are used together to perform related tasks - Shopware bundles. Each bundle defines a task pane within Shopware and includes all necessary classes. Shopware 5.0 includes, for the frontend refactoring, three bundles:

* StoreFrontBundle > Central interface for data determination in the frontend
* SearchBundle > Abstract product search definition for Shopware
* SearchBundleDBAL > DBAL implementation of the SearchBundle

By introducing the bundle structure, we aim at making these processes more understandable, and changing their behaviour or adding custom features easier and faster. Of course, we are open to your feedback and contributions to these bundles, as they are under continuous development and optimization. But the core refactoring does not end here. In future versions, bigger amd more interesting refactorings will be included, which I, of course, will not keep from you.
## Elasticsearch
Elasticsearch is an issue which has long been involved us and what we see of course very important.
We were often asked why it is not easily possible to load the product data for the storefront from an Elastic Search instead of MySQL database.


The answer is quite simple. There were too many places in the core that had to be adapted to support Elasticsearch integration. Also, there was just too much global state, which prevented a stable and reasonable Elasticsearch implementation from being possible. Finally, even if these two issues were addressed, 3rd party plugin compatibility needed to be ensured, which made the task even harder. Therefore, when refactoring the core classes, a future Elasticsearch integration was always kept in mind. With the StoreFrontBundle and SearchBundle, we have now made it possible to implement an Elasticsearch integration, which will be compatible with other plugins.

We are currently developing an Elasticsearch integration implementation, where we focus not only performance optimization for enterprise systems, but also on an architecture that can be easily extended by developers.
## Basket refactoring
Another target for refactoring is the basket process. The development of a refactoring concept is also already underway. Here we have set ourselves, from an architectural point of view, the following main objectives:

* Clear definition of the basket process and its calculations
* Adapt defined interfaces for plugins to the existing shopping cart calculation
* Connection for plugins, to define their own basket items and calculate them.

But we don't aim only at modernizing the architecture, but also at implementing completely new features.

## Plugin system
The plugin system is also constantly evolving. One of the key points is the connection to the dependency injection container (DIC), allowing plugin developer to decorate or overwrite existing DIC services, or define their own services. Another goal for the near future is to optimize existing plugin structures and methods, making it even easier to add adjustments to the data structures of Shopware. In the modernization of the plugin architecture, we want also to make it easier for developers to customize configurations on the core.

## Backend development
n addition to the optimization of the attributes extension system, we are constantly working to simplify the backend development, to allow faster entry for developers. We are already developing concepts to write backend modules based on HTML or allow plugin developers to easier extend ExtJS code over conventions.

I hope I could give you some insight into the upcoming refactorings. We still have some areas in the Shopware core which require refactoring, of which we are aware. We will continue working to optimize and modernize these areas, providing you with a faster and easier to understand Shopware.
