---
title: New is Always Better: Mastering Legacy Software Reengineering
tags:
- legacy software
- architecture
- layered applications
- rest
- restful interface
- spa
- single page application
- angularjs
- symfony2

categories:
- dev

authors: [hd]
github_link: blog/_posts/2015-02-17-mastering-legacy-software-reengineering.md
---

[before]: /blog/img/sbp_before.png "The patient"
[after]: /blog/img/sbp_after.png "The result"

## Introduction

A company's business environment underlies continuous changes either triggered from the outside or from the inside of the company itself. Such changes may sneak up within years whereas others emerge abruptly. Obviously some if not all of these changes have immediate effects on the company's daily routine.

Business software systems have to adapt to both the changes of the business environment and the changes of it's user's daily work routine. A software systems lack of flexibility and adaptability threatens to lead to historically developed legacy software and negative business impacts.

This blog post shall serve as the first part of a series approaching the problem of reengineering such legacy software in order to create a system that fulfills established software quality criteria and meets the company's individual needs. It is based on true a shopware project that started end of 2013 and will continue for at least two more years. This is to present our lessons learned.

## The patient
On the Shopware Community Day 2014 Stefan Hamann gave an overview of the history of Shopware - rapidly evolving to a national player in e-business. In the early stages of this company essential business processes were fulfilled by more or less simple scripts, simple single-purpose applications and manual processes. At some point, the "Dashboard" was born. It was the (good) idea of a platform centralizing important business processes - technically a simple ExtJs client application bound to a Zend server application. Over the years business processes changed and new processes emerged - the existing system grew. New systems were called on stage, such as the Shopware Plugin Store, the Shopware Account and several other (internal) platforms. The overall system grew more and more complex as well as diverse in used technology and techniques. Some of you might relate to individual software developed and deployed in your organization.

The picture below depicts the overall architecture of the patient.

![alt text][before]

### Architectural problems
The architecture was developed over the years and grew in an unwanted manner. These are the most striking issues concerning architecture:

* Six de-synchronized databases resulting in redundant data sets, complex historically developed data structures, high error-proneness and low traceability
* Four client-server-applications fulfilled subsets of end user requirements, yet failing to define a central repository of business process logic. Moreover, two of these user interfaces were to serve as one user experience although technically separate, which makes things *demanding*
* There is no central point of truth; business logic is quite evenly spread all over the components and layers of the system - of cause (in some cases) hard coded
* The communication infrastructure between the components is of a bidirectional and unnecessarily complex nature. Of course, one might say the components are highly interactive and flexible, which others might call euphemism.
* Each component is built within a different framework - sometimes a shopware installation. If you can work here, you can work everywhere.
* The existing consolidation and test environment - which exists in parts - differs from the production system although some components are used by both. However, setting up another test system is commonly entitled impossible.

### Implicated down sides
Besides the ones stated above and others, these are the implicated issues of the system:

* Simple changes are bound to excessive effort
* Feature and bug fix testing is - let's just say - complex by itself
* Existing test environments lack in consistency and setting up new test environments is said to be impossible
* Business process changes have heavy impact on the overall system, leading to enormous effort
* Programming feels useless and unappreciative
* Weakening system performance

## The challenge
Luckily, shopware identified the problems (quite last minute) and initiated the "Shopware Business Platform" (SBP) team which by then consisted of three people working in close contact to Stefan Hamann (founder of shopware) and concerned business units. The main challenge of the project was to re-implement all business processes without any data loss and without business units or customers to perceive negative effects. In parallel, evolving business changes had to be implemented in the old and/or new system. Disabling parts of the system or redundant work routine was not an option. Shopware daily work routine, which is highly dependent on the described legacy system, had to keep on going. To be clear, we are dealing with an open heart surgery here - with a roundish cyan heart, probably shown somewhere on this site.

Within the first weeks and guided by external consultants, the base lines of the project were set:

* The most striking problems of the existing system were identified in high detail
* The soon to be used techniques, technologies and processes were presented and then selected, learned and adapted by the team
* The development, deployment and testing process was defined in high detail
* The basic architecture of the new system was build using prototyping
* A strict pattern for application layering was defined
* The desired system growth behavior under requirement change was defined
* A set of migration strategies was defined - each applying on different levels of the application within the trade off between effort/cost and sustainable benefit

A subset of these topics may be discussed in later blog entries within this series over the next weeks.

## The result (sneak preview)
The initially defined overall architecture is depicted in the figure below.

![alt text][after]

After 15 months of development this architecture remains almost unchanged! These are some of the key features of our approach:

* The *SBP Core* contains the full set of business process implementation
* Only unidirectional communication is allowed
* There is one master database
* Required data replication is achieved using a standard master-slave replication mechanism
* Client-server communication is based on the REST standard
* User interfaces are single-page rich-client applications
* Every business process may be triggered using HTTP REST requests or CLI commands

## Follow-up topics
In the last 15 months we found many interesting topics to blog about. These are some

* REST API with Symfony2
* Single-Page-Applications with Angular
* Reverse proxy caching with Nginx
* Working on the open heart: Data and UI migration approaches
* Lasagna architecture and it's benefits
* Master data replication with our *Updater* component

If you are interested in something else please feel free to leave a comment in the box below.
