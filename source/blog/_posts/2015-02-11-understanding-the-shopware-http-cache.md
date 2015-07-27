---
title: Understanding the shopware http-Cache
tags:
- cache
- development

categories:
- dev

authors: [dn]
indexed: true
---

The shopware HTTP cache is available for production since shopware 4.1.0. It allows you to cache responses of the shop system, so that the next time the same page is requested, the answer can be returned much faster.
While the general concept of a cache is quite simple, there are many details to think of in a complex system like a shopping cart. For that reason, the following overview might come in handy for you.

# Enabling the cache

If you want to configure the HTTP cache, follow this link to our  [wiki documentation](http://en.wiki.shopware.com/_detail_855.html#HTTP_cache)

# HTTP cache setup

If you think about a simple web page, you will usually have a setup like this:

 * an user that requests a page
 * the web application generates a result

So whenever an user requests a page, the web application (e.g. shopware) will create a result page individually. If you have many users requesting the same pages, it makes sense to have an additional instance in between:

 * an user that requests a page
 * a reverse proxy cache
 * the web application generates a result

![General setup overview](/blog/img/reverse-proxy.svg)

The reverse proxy sits between user and web application and will take care of any request to the web application. If a user requests a page that has been requested before, chances are, that the reverse proxy just can hand out the same result as before - so the web application will not even been asked.

So a reverse proxy is basically a thin layer between user and web application that will try to avoid load on the web application by caching the results. Whenever the the web application generates a response for a request, the reverse proxy will save the request and the response to a `cache storage`. Next time the same requests comes in, the response will most probably be the same.


# How does it work?

Caching is always about questions like:

 * did I return the same page before?
 * did the content of the page changed meanwhile?
 * is this page the same for all customers - or will the current customer get another result (e.g. price).

The shopware HTTP cache has a variety of mechanisms to answer these questions:

## Whitelisted controllers
First of all controllers needs to be whitelisted to be cached. In the cache configuration you'll find a list of controllers which are being cached by default (e.g. listing, detail, index). Some other controllers - like the basket or account - are never cached: Every checkout and every account section is very individual so caching is not reasonable. If you implement custom controllers, they won't be cached unless you decide otherwise.


## Cache keys
A second aspect of the cache is the cache keys: Usually the cache tells apart the different pages by URL, so any URL is a own cached page. In addition to that, the shopId and currencyId belong to the cacheId. So technically the **page http://example.org/X** with **currency Y** and **shop Z** will result in the cacheId **http://example.org/X&__shop=Y&__currency=Z**


## Cache invalidation IDs
A third mechanism is the automated invalidation of pages. When rendering a listing or detail page, the HTTP cache will automatically check, which products and categories are shown on the current page and stores this information with the cached page (`x-shopware-cache-id` header). The cache plugin will then monitor products and categories for changes: When you change a product, the corresponding cache pages will be invalidated, so for the next request an uncached page will be returned. This way e.g. price changes will immediately reflect in the frontend. This automatization applies for any change which happens through doctrine models and can also be triggered by event.

The actual invalidation is done by so called "BAN" requests: So once the article with ID 713 is changed, shopware's cache plugin will send a HTTP BAN request with the header `x-shopware-invalidates: a713` (`a` for articles, `c` for categories). The reverse proxy (shopware's build in one or varnish) will then search through all cached pages and delete all pages which have `a713` in their `x-shopware-cache-id` header.

## Nocache tags
The fourth mechanism is the (often misunderstood) nocache-tag system. A tag is basically a certain "state" a user session can have. When an unknown customer visits your shop, he will be in the customer group "EK" and see cached pages with default prices. If the customer now logs in, he might become a member of your merchant group "M". Merchants pay other prices - so from now one, all price-aware pages should be live.
This can be achieved with the tag system: Once the customer logged in, the prices needs to be live - so we "mark" the session to be price-sensitive by setting a tag with the name "price". The price tag **does not** enforce live prices - but it allows you to define controllers, which will come live, once the price tag is set. So "price" is basically a name for a certain state of the session. These "tag awareness" for controllers is defined in the cache backend module.
The tag system intersects at two points in the shopware caching system. First of all, controllers being aware of a certain tag, will not be cached, when the tag is set. So you prevent "private" pages from being cached for all other customers. Second the HTTP reverse proxy will not return cached results for customers having a tag set and accessing a controller which reacts to that tag. So even if the listing X is available in the cache: a user with the price tag set will still get the live page.

In practice this is done with a combination of headers and cookies: Whenever returning a page, shopware will set the header `x-shopware-allow-nocache` with the tag the page is configured for - e.g. `price` if the page reacts to the price tag. Once a user sessions gets the price tag, the user will get the `nocache` cookie set to the value `price`.
Now whenever a customer with the nocache cookie being set to `price` requests a page, the reverse proxy can check, if the desired page in the cache storage may be delivered to that customer. If the page contains the header `x-shopware-allow-nocache: price`, that page will not be delivered to the customer and the customer will get a page from the live server instead.


![Cache flow chart](/blog/img/http-cache.svg)

# ESI-Tags

Shopware's HTTP cache is not a simple full-page cache: As it supports ESI-tags, so you are even able to embed non-cached sections within a cached page. That's the reason why we are able to show the current basket content within cached pages.

ESI-tags are basically sections within your (cached) page, which are let through to the shop system. While the rest of the page comes from the cache, the section from the ESI-tag will be returned from the shop - so it can be live. In shopware you don't need to take care of ESI-tags. Just use our smarty "action" plugin to render so called "widgets" onto your page. Just imagine this template code

```html
<h1>Hello world</h1>
<b>Some data which was returned after expensive database lookup</b>
{action controller=my-controller action=test name=peter}
```

The first two lines will be returned from the cache in no time. The last line will replaced by an ESI-tag, so that it will actually perform a subrequest to `http://example.org/my-controller/test?name=peter`. The result of this call will be rendered to the page. This way you are able to e.g. show a live instock value on a cached article detail page.

# HTTP stack
If you want to take a deeper look at the HTTP cache in shopware, there are basically two places to go for:

## engine/Shopware/Plugins/Default/Core/HttpCache/Bootstrap.php
This plugin is responsible for marking responses for the HTTP cache. It will mark the controllers with the correct cache time and nocache tags and will automatically detect, which products and categories might influence the current page. Also it will take care of the automated cache invalidation and tell the reverse proxy to delete a cached page, when e.g. a product's price changed.

## engine/Shopware/Components/HttpCache/*
The reverse proxy: By default shopware comes with a PHP reverse proxy which will quickly return the cached pages. Of course you can also run own reverse proxies like varnish - but as long as you don't explicitly configure another reverse proxy, our default symfony-based reverse proxy will respond.
 If you take a look at our shopware.php file, you'll find, that the PHP reverse proxy decorates the default symfony HTTP kernel:

```
$kernel = new Kernel($environment, $environment !== 'production');
if ($kernel->isHttpCacheEnabled()) {
    $kernel = new AppCache($kernel, $kernel->getHttpCacheConfig());
}

$request = Request::createFromGlobals();
$kernel->handle($request)
       ->send();
```

So once the cache is enabled, every request will go through the reverse proxy; the proxy will check, if the current page is

* allowed for caching
* actually cached
* not tagged for the current user (nocache-tag)

Only if all these conditions apply, the user will get a cached result. If the user did not get a cached result, the reverse proxy will forward the request to shopware and cache the result if the current page

* is allowed for caching
* not tagged for the current user (nocache-tag)

# Cache for plugin developers

There are several ways to manipulate the cache as a plugin developer

* `$this->HttpCache()->disableControllerCache()`
(From a plugin bootstrap) Do now allow the current page to be cached. This way you could stop shopware from caching e.g. the listing controller if your plugin adds some live information to it. You should use it with caution, however, as this is basically turning off the cache for that page.

* `Shopware_Plugins_HttpCache_ClearCache`
If you emit this event from your plugin, the whole HTTP cache will be cleared. This should not be done to often, of course.

* `Shopware_Plugins_HttpCache_InvalidateCacheId`
Invalidate the cache for a certain category or article as described above. In order to invalidate pages which contain the article with ID 14, just emit this event:

    ```php
    Shopware()->Events()->notify(
        'Shopware_Plugins_HttpCache_InvalidateCacheId',
        array(
            'cacheId' => 'a14',
        )
    );
    ```

    Note that depending on your reverse proxy (php reverse proxy or varnish) this might have a massive performance impact and should not be used in batch operations.

* `Shopware\Components\Api\Resource\Cache`
This cache resource of API not only handles the HTTP cache but many other caches in shopware, too. So if you need to clear the HTTP, proxy, template or object cache, you can use this resource - even with a REST client

# Summary

Even though caching is not the answer to any performance issue - in production environments you usually want your pages to be cached in some way, especially during marketing campaigns.
When using the HTTP cache or writing compatible plugins, you should keep in mind the basic principles of the HTTP cache to avoid problems and misconceptions beforehand.
