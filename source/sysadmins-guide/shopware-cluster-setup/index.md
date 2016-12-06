---
layout: default
title: Shopware cluster setup
github_link: sysadmins-guide/shopware-cluster-setup/index.md
indexed: true
group: System Guides
menu_title: Cluster setup
menu_order: 80
---
Installing and running Shopware on a single server LAMP stack is easy to accomplish and a common solution for small and
mid size customers. When it comes to high performance and high reliability, however, having a clustered, redundant
setup is inevitable.

The following document will describe ways to cluster Shopware as well as considerations related to running Shopware
in a clustered setup.

<div class="alert alert-warning">
Clustering is always highly individual and depends on the customer's requirements as well as the functional scope of the shop. For this reason every project will need to adjust the suggestions of this document for the actual hosting circumstances and customer / deploment workflows.
</div>

<div class="toc-list"></div>

## What is clustering?
Generally speaking, clustering is a way to link multiple computers for a certain purpose. Usually this purpose is to increase
availability and / or performance of the setup and has several benefits:

* you can introduce redundancy for any single component (e.g. cache, appserver or database). Even if a component
fails, the shop will still work, as there is no "single point of failure"
* the load (i.e. the users) can be distributed across the cluster. So there is not a single appserver that will
need to handle all users - but all users are distributed across all available appservers.
* scaling the setup becomes easier: As any component is layed out in a redundant manner, you can easily add another
varnish server or appserver on the fly once your shop is confronted with more traffic (e.g. after an TV advertisement).

## Shopware cluster setup
The following schema shows a simplified cluster setup. The components will be discussed in detail below.
![server setup overview](/sysadmins-guide/shopware-cluster-setup/img/setup.svg)

### Load Balancer (LB)
The load balancer is the foremost instance in every cluster setup. It will handle all customer requests and dispatch
them to one of the varnish cache instances.

Responsibility:
* SSL offloading
* equal distribution of the traffic across all caches / appservers

Software to run:
* Nginx (TLS offloading as well as load balancing)

Scaling:
* second load balancer as failover
* possibly floating IP and health checks for automated failover

### Varnish servers
If stand alone caches are required, Shopware recommends Varnish, as there is a [varnish configuration](/sysadmins-guide/varnish-setup/)
available.

Responsibility:
* caching - reduce load on database and appserver

Software to run:
* Varnish 4

Scaling:
* scales horizontally (numerous cache server possible)

### Appserver
An appserver runs the actual Shopware application and handles all requests which could not be handled by the cache
before:

Responsibility:
* handle user requests
* the shop itself

Software to run:

See: [system requirements](/sysadmins-guide/system-requirements/)
* Apache
* PHP
* latest version of Shopware (synced from the admin server)

Scaling:
* scales horizontally (numerous appserver possible)

### Admin server
The admin server is an appserver dedicated to the Shopware back office. It is also the leading appserver - all code changes
(e.g. deployment) happen on the admin server and are synced to the appservers. Furthermore all periodic tasks should be
run here.

Responsibility:
* serving of /backend (the back office)
* Cronjobs
* Jumphost
* Deployment

Software to run:

See: [system requirements](/sysadmins-guide/system-requirements/)
* Apache
* PHP
* latest version of Shopware

### Database
The database is the central persistent storage of all shop related data.

Responsibility:
* Holds all persistent data, e.g. articles, orders, customers

Software to run:
* MariaDB >= 10 (MySQL compatible database engine)

Scaling:
* replication master/slave
* cluster like percona / galera

### Memcache
For high performance setups, we recommend to store the sessions in memcache instead of the database (which is Shopware's
default behaviour). See [setup description here](/sysadmins-guide/memcached-as-session-handler/).

Responsibility:
* Store sessions

Scaling:
* memcache.session_redundancy
* solutions like repcache available

### Elasticsearch
Elasticsearch is a so called "no sql" storage, a non relational database engine, which is very efficient in searching
and filtering big catalogues. For that reason it can optionally be used, if you have many articles in your shop
or if there are special requirements for filtering and searching. Shopware generally recommends using Elasticsearch
if more then ~140000 articles are in place. Even with less articles your overall system performance might profit from
using Elasticsearch, as filtering and search queries will usually generate quite some load on the database and
have a low cache hit rate.
Additional information regarding [Elasticsearch are available here](/sysadmins-guide/elasticsearch-setup/).

Responsibility:
* Search and filter articles quickly
* reduce load on database engine

Software to run:
* Elasticsearch >= 2.0

Scaling:
* scales horizontally (numerous ES server possible)

### Images
Images are usually uploaded on the admin server - but need to be available on all appservers as well. Usually syncing
(duplicating) the images is not to be recommended for larger setups, so there are alternatives in place:

* Shopware media service: The [shopware media services](/developers-guide/shopware-5-media-service/) enables you, to use
external storages like Amazon S3. A proof of concept implementation for S3 is
[available on github](https://github.com/ShopwareLabs/SwagMediaS3). Other backend are possible through the filesystem
 abstraction layer "[flysystem](http://flysystem.thephpleague.com/)", e.g. the [SFTP adapter](https://github.com/shopwareLabs/SwagMediaSftp).
* Network storages: Network storages like NFS are still very common to share images and other files across multiple
appservers. Please be aware, that NFS can have a massive performance impact, so Shopware does not recommend to use NFS
other than for images.

## Variations
There is not "the one and only" way to build a cluster. The layout of the cluster should be adjusted to the needs and
budget of the customer. For that reason there are a lot of variations in the setup that should be considered:

### Caching on the appserver
In some cases you might want to consider moving the cache layer to the appservers themselves. In that case the
appserver would include varnish, apache and the Shopware application. This will keep the overal infrastructure
smaller, but will force you to optimize the appserver for varnish *and* the webserver / application.

As an alternative you could also remove the varnish cache entirely and only rely on Shopware's built in HTTP cache.
If you are using many uncached ESI tags or uncached pages in general, this might even be beneficial, as Shopware
has some optimizations regarding the handling of ESI tags from within the built in HTTP cache. Please notice, that
this will force you to optimize for appserver *and* built in cache.

### Sessions
Shopware handles sessions in the database by default. As this is not recommended for frontend sessions in a
high performance setup, using memcached instead was discussed before. In some cases, the memcached instance can
run alongside with other services like the load balancer, varnish or elastic search, if used.
If memcached is not an option in your case, using redis or even dedicated databases are possible by providing
custom session backends.

## Additional topics to be aware of
Clustering usually has quite some implications regarding the "single source of truth" in the setup. This especially applies
for local caches (e.g. APCu, proxy caches, generated attribute models), locally generated files (e.g. user upload)
and the sourcecode itself.

### Invalidating caches
Since Shopware 5.2.0 you are able to configure multiple HTTP reverse proxies (e.g. varnish). So whenever the HTTP cache
is cleared or certain pages needs to be invalidated, Shopware will distribute this BAN and PURGE requests to all configured
caches.

This does not yet apply for clearing caches like attribute cache, proxy caches, object caches etc. You can clear those caches
using the [cache endpoint of the Shopware REST API](/developers-guide/rest-api/api-resource-cache/).

### Uploads
Usually content is generated in and distributed by the admin server. The configured file syncs and media shares will
ensure, that all appservers to have access to this information. If you are using plugins that allow customers
to upload files in the shop frontend, the uploaded files will not be available for all other appservers automatically.
In those cases, you need to make sure, that the uploaded files are shared using CDN, shared storages or other syncing
mechanisms.

### Adminserver
Usually the shopware backoffice is available at `http://my-shop.com/backend`. Many customers reason, that introducing
a rule on the load balancer, which redirects all `backend/*` traffic to the admin server will be sufficient.
This is not always the case: The shopware backoffice will dynamically load files from other locations such as
`vendor/*` or `themes/*`. If your regular appservers are down for maintanance or other reasons, these requests will
fail if your load balancer rule is too naive.
For that reason we recommend, having a separate virtual host for your backoffice, such as `http://admin.my-shop.com`.
This way the backoffice will fetch all required files from that specific virtual host - and you can easily configure
 your load balancer correspondingly.
 
### Syncing
After deploying Shopware from VCS, installing plugins or generating themes from the admin panel the file base of the
admin server should be synced to all the appservers. `rsync` is a commonly used tool for this kind of task -
but you could also consider using [lsyncd](https://github.com/axkibe/lsyncd), which is an extension to `rsync` and
watches and syncs directories automatically.
As most shop setups are quite individual and will include custom plugins, there is no finite list of directories that needs
to be synced. Generally all files / directories of the Shopware setup should be synced across the appservers.
The following directories, however, need special treatment:

**No syncing**:
* `/var/cache`: Handled individually on every appserver, no syncing
* `/web`: Handled individually on every appserver, no syncing needed as of Shopware 5.2

**Larger directories**:
* `/files`: Synced to each appserver or shared storage. Depends on installed plugins  and used Shopware featured such as ESD etc.
* `/media`: see [above](#images)

**Directories that might change during runtime**:
* `/engine/Shopware/Plugins`: Changed when plugins are installed from the admin panel
* `/themes/Frontend`: Changed when new themes are created from the admin panel
* `/media`: Changed when new images / videos / media are uploaded in the admin panel
* `/files`: Changed when ESD items are uploaded or order documents are generated

## Additional resources
* [Shopware system requirements](/sysadmins-guide/system-requirements/)
* [Performance tipps for sysadmins](/sysadmins-guide/shopware-5-performance-for-sysadmins/)
* [Elasticsearch configuration](/sysadmins-guide/elasticsearch-setup/)
* [Varnish configuration](/sysadmins-guide/varnish-setup/)
* [Memcached configuration](/sysadmins-guide/memcached-as-session-handler/)
* [Using Amazon S3 for images](/developers-guide/shopware-5-media-service/#example-migrating-all-media-file)
