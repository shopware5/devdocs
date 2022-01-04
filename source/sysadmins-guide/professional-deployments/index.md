---
layout: default
title: Professional Deployments
github_link: sysadmins-guide/professional-deployments/index.md
indexed: true
group: System Guides
menu_title: Professional Deployments
menu_order: 40
---

## Professional Deployments

This guide is intended as a list of best practices to follow when professionally deploying Shopware shops.

### Development best practices

Professional deployment begins with professional development. The time when everybody edited files on a central
FTP server are long gone.

You should be using a version control system (VCS), preferably [git](https://git-scm.com/) to allow parallel development
on local machines while integrating these parts in a central point. See the <a href="{{ site.url }}/developers-guide/plugin-quick-start">developers guide</a>.

If you find yourself reusing plugins, themes or configuration, think about using [Composer](https://getcomposer.org/) and
the [Shopware Composer Project](https://github.com/shopware/composer-project) for your new projects. It will help you
to require plugins or libraries you repeatedly use.  

### Development, staging and production systems

Even if some feature works perfectly on the developers own machine, there is always a chance that some setting or the
integration with some feature of another developer breaks the shop. So it is a good idea to have one or more stages of
integrations; a common pattern is a three level system of development, staging and production systems. Of course all
these are just a recommendation - all of this can and should be modified to your situation, use cases
and workflows.

- The development system is where a first check is possible or where internal stakeholders can get a preview or glimpse
  of how a feature is about to be developed.

- The staging system is where a thoroughly tested feature is being shown to an external customer and features are combined
  into releases.

- The production system is what the end customer gets to see. If a staging system contains all relevant features
  for e.g. a certain milestone, these can be deployed together to this system.

### Deployments

Deployments should be as automatic as possible to prevent any chance of human error. It is generally a good idea to
not simply replace or update your webroot on the production system. Rather create a fresh clone of the latest production
release from your VCS, maybe run some automated tests to make sure no basic errors occurred and just switch the webroot
of your webserver to point to your new release. Shared resources (like images, documents etc.) can be offloaded to a
CDN or themselves be included in your webroot by file system links.

Should anything be wrong with this release, you can still switch back to the old version (given the database wasn't
modified in a destructive way). This way you can minimize the downtime, or even eliminate it all together if you're
working in a cluster setup and are able to update machine by machine.

### Blue-Green-Deployments

The ability to upgrade/downgrade your production environment is commonly known as "Blue-Green Deployment". Starting with
v5.4 forward, Shopware commits itself to be able to support this kind of deployment out-of-the-box for succeeding
minor versions. This means that you can confidently deploy a coming v5.5 release, knowing you can always switch back to
your current proven v5.4. Of course this presumes that all installed plugins support this as well.

So any deprecations to the database that were announced in v5.4 will only be applied in a v5.6 release. That way the
intermediate v5.5 database can and must be usable with both a v5.4 and a v5.5. Database deprecations in v5.5 will be
applied in a v5.7 and so on.

### Best practices

Try to not store credentials on disk to minimize the information an attacker might gain in case of file system access.
Rather use environment variable set e.g. in the webserver. The [Shopware Composer Project](https://github.com/shopware/composer-project)
supports environment variable for the setup of e.g. the database out of the box. 

You could start your automated deployments with some simple shell scripts to e.g. checkout a specific tag on the
server or switch the currently productive version by changing the target of the webroot-link. You could re-utilize these
scripts later by integrating them into a CI/CD system like Jenkins, Bamboo or others.

It is often also a good idea to redefine basic settings which could be changed by users to sane defaults with 
each deploy. Examples could be the mailer configuration, thumbnail sizes or controller caching times for the HTTP cache.

In Shopware 5.5.2, there is a new config value which allows you to have the plugins loaded in a predictable way during the DI container build process. This is important if you use multiple app servers because the current behavior is based on the underlying file system and might differ between machines. Add the following code snippet to your config.php file and the plugins are loaded alphabetical:   

```php
    'backward_compatibility' => [
        'predictable_plugin_order' => true
    ],
``` 
