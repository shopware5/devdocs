---
title: On action tags
tags:
- cache
- http

categories:
- dev

authors: [dn]
indexed: true

github_link: blog/_posts/2016-07-11-on-action-tags.md
---

The Shopware HTTP cache is a full page cache - it caches the whole output for a given route such as `www.my-shop.example.org/fancy-product`.
There are mechanisms, however, to modify this behaviour by providing [e.g. cache cookies or no-cache tags](/developers-guide/http-cache/#live-caching).
An additional mechanisms are the so called [action tags](/developers-guide/http-cache/#action-tag). This blog post will discuss the technical
background of the action tags - and the way they are handled in various scenarios.

## The action tag
The `{action}` tag is a Smarty tag, that can be used to call other routes from within a page. Consider this example:

```
<h1>Welcome to {$shopName}</h1>

<p>These are the products you recently purchased:
    {action controller=user action=recentlyPurchased}
<p>
```

In this example there is a HTML template, which will greet a user and show his recently purchased products. Generally
this whole page could be cached: The `{$shopName}` Smarty variable will be the same for all users, so it has no implications
for caching. The list of recently purchased products, however, is heavily user dependent - it cannot be the same for every
user, therefore it cannot be cached generally.

One way to solve this kind of issue, is to include a `{action}` tag. It will force Shopware to internally call another
route and render that output into the HTML template. This way the surrounding HTML template can be cached for all
users and just the recent products are fetched from the system.

Oversimplified one could say, the action tag works like an iframe - it will show a separate page into another page. Of
course no iframes are used here - there are more sophisticated mechanisms for those kind of problems.

## How action tags are handled
The handling of the action tag differs depending on if your are using the HTTP cache and which HTTP cache you are using.

### Without caching
If Shopware is used without the HTTP cache, the Shopware's default implementation of the action tag will take effect.
It can be found in `engine/Library/Enlight/Template/Plugins/function.action.php`. In here Shopware will create
a separate request / response object and pass it to the [dispatcher](/blog/2015/08/26/bootstrapping-shopware-the-dispatch-loop/#dispatcher).

The dispatcher will then handle the request and dispatch it to the corresponding controller - in the example above
the controller `user` with the action `recentlyPurchased` is affected. This controller will handle the request as every
other controller, render a template and assign that to the response body. The action block will then just return that
response, so it can be rendered into the calling page.

In other words: If no caching is used, Shopware is able to handle action tags in the very same call then the main request.
The DI container and all instantiated services can be reused and there is no additional overhead by HTTP requests.
Even though action tags are handled very efficiently, the overall system response time will suffer from the fact, that
every single requests needs to be handled by the server as there is no caching involved.

### With Shopware's built in HTTP cache
If you enable the Shopware HTTP cache (e.g. in production mode), Shopware will use the Symfony reverse proxy by default.
The Shopware HTTP cache will then replace the default action plugin with another implementation in `\Shopware_Plugins_Core_HttpCache_Bootstrap::registerEsiRenderer`.
This implementation will not resolve subrequests directly but render an so called ESI tag instead. The action tag
`{action controller=user action=recentlyPurchased}` will then become `<esi:include src="user/recentlyPurchased" alt="" onerror="continue"/>`
This will instruct the HTTP cache (in this case the symfony reverse proxy) to perform an additional request to Shopware
and render the result of that request into the template.

However, the Symfony reverse proxy is still able to handle those requests without re-booting the whole Shopware stack:
The first request actually hitting the Shopware stack (cache miss) will boot up the Shopware Kernel and all relevant
dependencies such as DI container, plugin system and required services. Every ESI request can then be handled in this
stack. In `\Shopware\Kernel::handle` this looks like this:

```
$request = $this->transformSymfonyRequestToEnlightRequest($request);

if ($front->Request() === null) {
    $front->setRequest($request);
    $response = $front->dispatch();
} else {
    $dispatcher = clone $front->Dispatcher();
    $response   = clone $front->Response();

    $response->clearHeaders()
        ->clearRawHeaders()
        ->clearBody();

    $response->setHttpResponseCode(200);
    $request->setDispatched(true);
    $dispatcher->dispatch($request, $response);
}

$response = $this->transformEnlightResponseToSymfonyResponse($response);

return $response;
```

If there was no request before, the main request is handled in the first branch; if this is a subsequent request (e.g.
an ESI request), those requests will be handled in the second branch similar to the way subrequests are handled
by the default action tag implementation (see above).

You can even see this mechanism in the cached files: Searching for `surrogate->handle` in the cache folder will bring
up results such as

```
<?php echo $this->surrogate->handle($this, '/?module=frontend&controller=user&action=productsPurchased', '', false) ?>
```

These snippets will forward the request to the Shopware kernel and render the result to the response. For that reason
Shopware without any cache and Shopware with the Symfony reverse proxy are both able to handle action tags without booting
the Shopware kernel multiple times.

### With Varnish
The last variant is to use Shopware with an external cache capable of handing ESI tags such as [varnish](/sysadmins-guide/varnish-setup/).
Generally this works the same way as the variant with the builtin HTTP cache: Shopware will replace the action tags
with ESI tags - and the cache will take care of requesting those parts of the page separately.
However, as external caches cannot access the Shopware stack directly, every cold ESI request will result in a full
call to the Shopware stack.

When ESI tags are handled sequentially (e.g. when using varnish), every ESI to cold pages (cache miss) will slow down
the overall return time of the page. So even if the main page is a cache hit, calls to cold ESI routes might have
an impact on the response time.

## Slow ESI tags
Let's consider a page like this:

```
<body>
    <h1>My fancy homepage</h1>
    <esi:include src="/test/firstEsi"/>
    <esi:include src="/test/secondEsi"/>
    <esi:include src="/test/thirdEsi"/>
</body>
```

The page itself is cachable and includes three ESI tags.

<img class="is-float-left" style="width:40%;" src="/blog/img/esi_download.png">
This image shows a call to a page using varnish: The TTFB (time to first byte) is 86ms, so the cache
is able to deliver an answer after 86ms. The "Content Download", however, take a lot longer. In this case the slow content download
does not indicate a bandwith issue - it is varnish asking the appserver to resolve ESI tags. In this example, every
ESI call does take one second.

So after 86ms the cache started delivering the (cached) page. After delivering some parts of the HTTP code, however,
the cache encounters an ESI tag. If that route is not cached, yet, varnish will need to wait for the appserver to process
that request - in this case one second. After the appserver returned the result, the cache is able to continue delivering
the original page with the result of the ESI call inside. Then it will encounter the second and third ESI tags, and will
need to look those two up as well one after the other.
That's the reason, why the browser indicates a slow "content download":
After the initial response started after a few milliseconds, the rest of the response was slowed down by the uncached ESI tags.

<img alt="shopping carts" class="is-float-right" style="width:60%;" src="/blog/img/esi_animation.gif">

Generally this behaviour is the same for varnish caching and Shopware's builtin cache. However: Due to the fact, that
the builtin cache does not need to boot the Shopware kernel for every single ESI tag, a page with many uncached ESI
routed might profit from using the builtin cache. On the other hand, varnish is way faster in returning cached pages -
so this is highly dependent on the number of (uncached) ESI tags.

It's also important to notice, that older versions of varnish are only able to fetch ESI tags sequentially (one after another).
So if 3 cold ESI tags needs to be fetched with 1000ms each as in the example above, this will take 3000ms in total. This
is illustrated in the animated image: Loading the page is interrupted three times for a second when the ESi tags are resolved.
So even if ESI tags are great for having parts of the page cached with varying cache times -
looking up multiple uncached ESI tags might have quite some performance implications.

Generally we recommend to *reduce the number of ESI tags* as far as possible in high performance setups. If ESI tags
are inevitable, make sure that they *are cached as well* or that they are *not used "above the fold"*.

## Ajax ESI
Some time ago, I was wondering, if we couldn't just handle ESI tags with Ajax queries. As those queries could be handled
all at once, we might be able to handle 3 queries with 1000ms each in (theoretically) 1000ms - instead of 3000 as in the example before.

The proof of concept plugin I wrote will basically subscribe to PostDsipatch events and remote the "Surrogate-Control"
header. This way, caches will ignore the ESI tags and deliver those tags to the client. A simple jQuery plugin will then
find those tags and perform the ESI request via Ajax:

```
$.plugin('ajaxEsi', {

    init: function () {
        var me = this;

        me.applyDataAttributes();

        $('esi\\:include').each(function (i, d) {
            me.loadSingleEsi($(d).attr('src'), function (data) {
                $(d).replaceWith(data);
                window.StateManager.updatePlugin('select:not([data-no-fancy-select="true"])', 'swSelectboxReplacement');
            });
        });

    },

    loadSingleEsi: function (url, callback) {
        $.ajax({
            url: url
        }).done(function (data) {
            callback(data);
        });
    },

    destroy: function () {
        var me = this;

        me.$el.removeClass(me.opts.activeCls);

        me._destroy();
    }
});
(function ($) {

    $('body').ajaxEsi();

})(jQuery);
```

As you can see, all elements matching `esi\\:include` are searched. In the `src` attribute there is the URL we want
to call using Ajax, e.g. `http://my-shop.example.org/user/recentlyPurchased`. The result of those Ajax-Queries
is then simply placed into the DOM using `$(d).replaceWith(data);`.

This approach will still require Shopware to run the Stack for each single ESI tag - but the requests are performed
simultaneously and the main request is not slowed down any more by ESI requests.

## One step further: Batch requests
In the `multi-esi` branch of the plugin, I also evaluated another solution: Theoretically you can collect all
ESI tags first and then write a custom controller, that will process all ESI URLs at once. This way, we never need more
than one request to the Shopware stack for all ESI tags.

There are two downsides of this approach, however:

1. This approach will require a custom controller, that is able to dispatch multiple URLs. This is doable - but increases
the complexity of the system.
2. When multiple ESI tags are requested in one HTTP request, the handling of the cache time also becomes more complex:
You will need to figure out the lowest common cache time of all routes and set that for the request. But if the overal
cache time is defined by the lowest cache time - what is the point in having varying cache times in the first place?

So even though this approach looks promising on a first glance, I assume that it would introduce too much complexity to
solve a problem that should better be handled in your application's logic: If you need e.g. many ESI requests for prices
or instock info, having a distinct Ajax requests that will fetch this info in one Ajax call is a way better solutions then
to introduce the "batch request" ESI handling discussed here.

## Conclusion
ESI tags will allow you to handle pages with multiple, separately cached sections in an easy manner. You should be aware,
however, that (uncached) ESI tags are handled in a different way in different situations:

* No cache: Subrequests within the main request, no additional kernel boots
* Symfony reverse proxy: Within stack, at most one additional kernel boot
* Varnish or other external caches: One full Shopware stack for each ESI tag

Furthermore you should be aware, that ESI tags are usually handled in a synchronous manner - so if you have many uncached
ESI requests, the page will be slower by the sum of all ESI tags.

For that reason, ESI tags should usually be cached as well - they are not the ideal solution to present many live sections
within a cached page - but a good solutions for a page with many different cache sections. Requesting ESI tags with the
"AjaxESI" plugin discussed above will allow you, to fetch multiple ESI tags at once - but it will still require Shopware
to boot the kernel for any single ESI request.

Depending on the use case, there are other alternatives such as [web sockets](https://en.wikipedia.org/wiki/WebSocket)
or techniques like [bigpipe](https://www.facebook.com/note.php?note_id=389414033919) that also try to minimize the
number of requests needed to populate a page with customized content.
