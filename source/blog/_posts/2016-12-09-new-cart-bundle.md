---
title: New cart bundle concept
tags:
- cart
- refactoring

categories:
- dev

authors: [dr]

github_link: blog/_posts/2016-12-09-new-cart-bundle.md
---

Since the last Community Day, we've received several questions about Shopware's new shopping cart:
- *How far are you with the development*
- *Which new features come with the new shopping cart?*

At the Community Day, we also announced that we are striving for open development in the refactoring process in order to get as much feedback as possible and to be able to work more closely with other developers and partners.
We want to realize this now by sharing the first concept of the new shopping cart.
You can see the development process on <a href="https://github.com/shopwareArchive/shopware-cart-poc">Github</a>,
where we created a new repository which allows the Community to create pull requests and issues.
The new repository contains a new bundle in `/engine/Shopware/Bundle/CartBundle`, which contains a first proof of concept for a new cart process.
This first concept can change steadily due to growing requirements and that refactoring will be an ongoing process.
The first features we implemented are the following:
- Add, delete and change quantity of product line items
- Add and delete percentage-based vouchers
- First concept for partial delivery

There's currently no storefront integration. A view layer will follow after all calculation processes are tested.
For that reason, the classes are only used inside unit tests.
From a technical perspective, the cart already contains the following concepts/features:
- Percentage price calculation
- Gross and net price calculation
- Proportional tax calculation
- Exchangeable gateway for product prices and delivery information
- First proof of concept for a partial delivery to different addresses and delivery dates

If you want to get more information about the technical concept and current implementation, take a look at our <a href="/developers-guide/concept-cart-bundle/" target="_blank">new developers guide article</a>.
This article is created to document the current state of the implementation and will contain more content regarding progressive refactoring.
Due to the complexity and importance of the shopping cart inside Shopware, this will be a long-term project.
Short-term integration into the core product is therefore not to be expected.
