---
layout: labs
title: API
github_link: labs/api/index.md
shopware_version: X
indexed: true
group: Labs
menu_title: API
menu_order: 400
---

One of the most fundamental research projects we are currently working on is the new shopware API. With a completely 
reworked and optimized architecture, the new API will be the foundation of both the front- and backend. Instead of being
an extension to the existing infrastructure, the API will be regarded as a first-class citizen in shopware. We strive to
make every single aspect of the system available over the API. As a direct abstraction of the underlying database it 
will feature considerable increases in performance compared to the current architecture. To achieve this, we have decided 
to remove Doctrine as an ORM - instead, all database operations will be grouped together in a new Repository layer that
integrates with the database directly using a dbal-queries. Topics like ACLs and API versioning are still being evaulated
and will be described in more detail in the future.