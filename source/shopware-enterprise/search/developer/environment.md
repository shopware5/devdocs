---
layout: default
title: The Development Environment
github_link: search/developer/environment.md
indexed: true
menu_title: Development Environment
group: Shopware Enterprise
subgroup: Enterprise Search
subsubgroup: Developer
menu_order: 2
---

## The development environment
The [Github repo of SES](https://gitlab.com/shopware/shopware/enterprise/swagenterprisesearch) contains the full development stack for SES. This
is useful for developing SES itself - or just for trying it out quickly.

After checking out the repository, step into the SES directory:

```
git clone git@github.com:shopware/SwagEnterpriseSearch.git
cd swagenterprisesearch
```

Now setup the docker environment using the build script:

```./psh.phar docker:start```

This process might take several minutes and will setup docker containers for shopware, MySQL and ElasticSearch. In
order to install shopware with SES in docker, you need to SSH into the container and run the corrsponding build script:

```
./psh.phar docker:ssh
./psh.phar init
```

After this, populate the ES index with `./psh.phar es-index`. After doing so the shop can be reached at `10.100.150.46`
on your local machine.

### Unit tests
In order to run unit tests, execute `./psh.phar unit-fast`. The coverage is built using `./psh.phar coverage`.
