---
title: MeetNext - a recap
tags:
- community
- next
- meetnext
- shopware

categories:
- dev

authors: [nd]
github_link: blog/_posts/2017-10-23-meetnext-recap.md

---

# Introduction

[![Army of Codys](/blog/img/2017-10-23-meetnext-recap/cody_army_thumb.jpg)](/blog/img/2017-10-23-meetnext-recap/cody_army.jpg)

## When

&#35;MeetNext took place from October 18th to 20th

## Where

[![Germania Campus](/blog/img/2017-10-23-meetnext-recap/campus_view_thumb.jpg)](/blog/img/2017-10-23-meetnext-recap/campus_view.jpg)

Münster, Germany

## What

The participants were informed about the current state of the Shopware research.

So, what is the `Next` in &#35;MeetNext about? In a nutshell, it is the next big thing. A completely new product different to 5.x which will be developed simultaneously to Shopware 5. They will both receive patches and new features. Which leads us to the next question:

## Why

Shopware is a well established, mature product. As the people who are working with Shopware know, there are a lot of things in the software that work in a certain way because it is our legacy. And we can't change it without effectively breaking the functionality of almost every plugin out there. Example: Ever wondered why products are called `articles` in shopware? Because the german word for a product is `Artikel`. Ever wondered why there are multiple terms for manufacturer? Legacy. So yeah, that's not what we want and of course there's much more, especially regarding the architecture.

So the main reason is: We want to continue to make Shopware better, faster, ready for the future and easier to develop for. To achieve this, we have to break a lot of stuff, and we don't want to throw away all the work we and you guys out there already invested. We're always telling you, like a mantra, that we love our community. Guess what, we do this because we mean it. You're part of Shopware, and we wouldn't want to have a different community.

All that said, we want to create a second version of Shopware which only carries the good part of our legacy. We don't want to do this in the dark, doing the jack-in-the-box at some time and presenting the new and shiny brother of Shopware 5. We want to have you aboard as soon as possible. We want to hear from you if the things we are planning and implementing are what you need in the end. We want to hear what you think is a mistake, what we forgot and what is even more awesome than before.

The &#35;MeetNext was the first feedback event and it was, marketing speak aside, a huge success. Before we dive in, always remember you can see for yourself what is happening, either on [github](https://github.com/shopware/shopware/tree/labs) or in the [documentation](https://developers.shopware.com/labs/).

# A brief summary

[![Room overview](/blog/img/2017-10-23-meetnext-recap/room_overview_thumb.jpg)](/blog/img/2017-10-23-meetnext-recap/room_overview.jpg)

There were five topics:

- [housekeeping](https://developers.shopware.com/labs/housekeeping/)
- [API](https://developers.shopware.com/labs/api/)
- [i18n](https://developers.shopware.com/labs/internationalization/)
- [administration (former backend)](https://developers.shopware.com/labs/new-administration/)
- [basket & order process](https://developers.shopware.com/labs/shopping-cart-and-ordering-processes/)

The main topics that were discussed at #MeetNext were *API*, *Administration* and *Basket*.

If you follow the links above you can find the updated documentation.

# The results

Now for the results. And by saying results we are talking about the feedback the participants gave us, sorted by topic.

These things are the "top feedback", of course there were a lot more valuable ideas, and we heard them all and wrote them down.

## API

| Good things            | Bad things, improvements & new ideas                 |
|------------------------|------------------------------------------------------|
| UUIDs                  | REST API should not be used as ERP API               |
| Foreign Keys           | Plugin support                                       |
| Error Handling         | No attribute support                                 |
| SwagQL                 | A lot of class changes needed for structural changes |
| Single Source Of Truth | Use HTTP status codes                                |
| Performance            | ACL                                                  |
| Upsert/Sync            | Audit-Log                                            |

## Basket

| Good things             | Bad things, improvements & new ideas                 |
|-------------------------|------------------------------------------------------|
| Stateless               | Dynamic processor should be splitted                 |
| Price & tax calculation | part-delivery system needs to be highly configurable |
| Concept for deliveries  | Use money objects instead of floats                  |
| Highly modular          | Priority of processors should be a dependency system |
| Concept of processors   | Persist the calculations alongside an order          |
| Expandability           |                                                      |
| Upsert/Sync             |                                                      |

## Administration

> **Note:** We're creating a new "backend" without extJS and we're calling it "administration"

| Good things                 | Bad things, improvements & new ideas            |
|-----------------------------|-------------------------------------------------|
| UX concept                  | Vue.js could be too complex                      |
| Routing                     | Client-side twig rendering                      |
| Less boilerpate code needed | View-layer abstraction                          |
| No more client-side models  | No Typescript                                   |
| Saving of changesets        | Huge bootstrapping process                      |
| Hot-reloading               | Better accessibility for people with disability |
| Upsert/Sync                 |                                                 |

# Hacking

After the sessions ended, a lot of the participants started hacking and tried to break things.

Since we didn't manage to memorize (or write down) the names of all hackers and their projects, here is a list of examples in no particular order and without credit. But the list shows two things, first how awesome Shopware next will be (short time, great projects) and how awesome the Shopware community is. Especially you guys who managed to attend #MeetNext. ;-)

## Some of the "hack projects"

- Implement reactive window management in vue.js
- Tracing library for class performance analysis
- Port an existing cart-related plugin to Next
- Revive the old ExtJS backend and let it speak to the new API
- Create a different concept of component extension for vue.js

# Visual Impressions

[![Visual impressions](/blog/img/2017-10-23-meetnext-recap/impressions_00_thumb.jpg)](/blog/img/2017-10-23-meetnext-recap/impressions_00.jpg)

[![Visual impressions](/blog/img/2017-10-23-meetnext-recap/impressions_01_thumb.jpg)](/blog/img/2017-10-23-meetnext-recap/impressions_01.jpg)

[![Visual impressions](/blog/img/2017-10-23-meetnext-recap/impressions_02_thumb.jpg)](/blog/img/2017-10-23-meetnext-recap/impressions_02.jpg)

[![Visual impressions](/blog/img/2017-10-23-meetnext-recap/impressions_03_thumb.jpg)](/blog/img/2017-10-23-meetnext-recap/impressions_03.jpg)

[![Visual impressions](/blog/img/2017-10-23-meetnext-recap/impressions_04_thumb.jpg)](/blog/img/2017-10-23-meetnext-recap/impressions_04.jpg)

[![Visual impressions](/blog/img/2017-10-23-meetnext-recap/impressions_05_thumb.jpg)](/blog/img/2017-10-23-meetnext-recap/impressions_05.jpg)

[![Visual impressions](/blog/img/2017-10-23-meetnext-recap/impressions_06_thumb.jpg)](/blog/img/2017-10-23-meetnext-recap/impressions_06.jpg)

[![Visual impressions](/blog/img/2017-10-23-meetnext-recap/impressions_07_thumb.jpg)](/blog/img/2017-10-23-meetnext-recap/impressions_07.jpg)
