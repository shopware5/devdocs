---
title: The Shopware SEO engine
tags:
    - SEO
    - search engine optimization
    - Shopware

categories:
- dev
indexed: true
github_link: blog/_posts/2015-08-11-seo-engine.md

authors: [dn]
---
The term "SEO" summarizes various features and functionalities which targeted at increasing the ranking of a given website
within search engines. This will usually include semantic URLs, rich snippets, meta tags and search engine optimized
HTML structures and texts.
While most of these techniques are not that interesting from a technical perspective, the handling of "speaking URLs"
("SEO URLs", "fanzy URLs") usually involves the routing of requests as well as the generation of the resulting HTML
code and also is common target of extensibility. For that reason, this blog post will cover Shopware's handling of
SEO urls regarding generation, routing and normalization of URLs.

<img alt="Overview of the SEO URL concept" style="float: left" src="/blog/img/seo-general.png">
# General concept
Usually there is some kind of technical representation of any given URL in a shop. So for example the SEO URL
`http://my-shop.com/living-world` will be internally mapped to a representation like `http://my-shop.com/frontend/listing/index/?sCategory=8`.
While the first example is optimized to be human readable and SEO, the second representation can be handled by Shopware's
internal routing mechanisms. It will tell the router, that the current request will go to the indexAction of the listing controller
in the frontend namespace and has a parameter called sCategory which is set to 8 in this case.
Once the controller in charge was determined, the controller will handle the request and generate some kind of response, which
then again should contain SEO URLs for the next requests.

# Requirements
While the mapping of the SEO representation and the internal representation could theoretically be generated on the fly
(e.g. the SEO name always derives from the name of the category), in practice there are some requirements which make the
topic a little more complex:

* History: Once a SEO URL changes, the old one should redirect with the correct status code to the new URL.
* Reduce duplicate content: The same content should not be available under different URLs, as this might reduce the
ranking of the corresponding result page. Instead of that, there should always be a canonical address of the content,
which other addresses will redirect to.
* Customization: The user wants to be able to customize the SEO URL to a high degree.

# How does Shopware handle SEO URLs?
## Storage
Shopware stores all SEO URLs into the table `s_core_rewrite_urls`.

|Column|Usage|
|-|-|
|id|Internal identifier|
|org_path|Internal / technical representation of the URL, e.g. `sViewport=blog&sCategory=17`|
|path|Human readable / SEO URL, e.g. `trends-news/`|
|main|(Bool) is this the canonical representation of the URL. Non-main URLs will always be redirected to the main URL|
|subshopID|Which subshop does this URL belong to (e.g. 1=german shop, 2=english shop)|

Depending on your configuration, there are "refresh strategies" to build this table:

* Live (during frontend requests, in a configurable interval called "Routing cache")
* Manual (by pressing the »**Rebuild SEO index**« button)
* Cronjob (By a cronjob in a specific interval)


![Shopware's seo settings](/blog/img/seo-settings.png)

## Configuring the URLs
When the table is built, Shopware will iterate all SEO relevant records (e.g. products, categories, cms and blog pages) and create
the SEO entries in the `s_core_rewrite_urls` table.
How an URL for e.g. a product will look, can be configured using smarty template strings:

![Shopware's seo settings](/blog/img/seo-base-settings.png)

In the screenshot above, the SEO URL templates are highlighted. The product template does look like this:

```{sCategoryPath articleID=$sArticle.id}/{$sArticle.id}/{$sArticle.name}```

It will tell Shopware, that a SEO URL for a product consists of three parts:

* the canonical category link of the product, e.g. /food/bread/
* the product ID e.g. 17
* the product's name, e.g. "white bread 300g"

All this together will create a URL like this:

```/food/bread/17/white-bread-300g```

The whitespaces will be automatically converted to dashes. Technically there is no reason, to have the productId in the SEO URL.
As the SEO URLs needs to be unique throughout the system, it might be helpful, to have such an id included, however.

In the Shopware wiki there are [several examples regarding the SEO template configuration](http://en.community.shopware.com/_detail_1143.html?_ga=1.92518557.1619813372.1429258252#Building_a_template_item), including an example for product
specific SEO URLs.

## Building the SEO URLs
In the sections above we handled how Shopware triggers the generation of SEO URLs and how those SEO URL templates can be configured.
But how / where does Shopware actually generate those URLs?

The relevant class here is `\sRewriteTable` which can be found in `engine/Shopware/Core/sRewriteTable.php` of your Shopware installation.
Let's have a quick look at the relevant logic of that class:

* `\sRewriteTable::$replaceRules`: Contains a translation list of letters in order to slugify special chars (like ö, ä or ü) correctly.
Most of this list is inspired by the [slugify project](https://github.com/cocur/slugify/blob/master/src/Slugify.php#L29).
During SEO generation, Shopware will call \sRewriteTable::sCleanupPath in order to replace the characters defined in the
sRewriteTable::$replaceRules property
* `\sRewriteTable::sCreateRewriteTable`: Is the main entry point of the SEO url generation and trigger all of the follwing methods
* `\sRewriteTable::baseSetup`: Will be called, before the actual SEO URL generation starts. Will initialize the smarty template
engine with e.g. the Shopware config object, register some additional smarty plugins and also try to set the memory_limit / max_execution_time
config vars
* `\sRewriteTable::sCreateRewriteTableCleanup`: This method will remove SEO URLs of products / categories / blogs / static pages etc
where the referenced entity (e.g. product) does not exist anymore. Shopware will never delete SEO URLs of items, that still exist.
* `\sRewriteTable::sCreateRewriteTableStatic`: Will generate the static URLs that can be created in the SEO config. Static
URLs will allow you to add SEO URLs for custom controllers and landing pages
* `\sRewriteTable::sCreateRewriteTableCategories`: Will iterate all Shopware categories, apply the category template to them and
write the resulting SEO URL to the database
* `\sRewriteTable::sCreateRewriteTableBlog`: Will iterate all Shopware blogs, apply the blog template to them and
write the resulting SEO URL to the database
* `\sRewriteTable::sCreateRewriteTableCampaigns`: Will iterate all Shopware shopping worlds, apply the emotion template to them
and write the resulting SEO URL to the database
* `\sRewriteTable::sCreateRewriteTableArticles`: Will iterate all Shopware products, apply the product template to them
and write the resulting SEO URL to the database
* `\sRewriteTable::sCreateRewriteTableContent`: Will iterate all Shopware forms and static pages, apply the corresponding
template to them and write the resulting SEO URL to the database
* `\sRewriteTable::sCreateRewriteTableSuppliers`: Will iterate all Shopware suppliers, apply the corresponding template to them
and write the resulting SEO URL to the database

## Canonicalize all the links
After building the SEO links as described above, Shopware will use those SEO links instead of the technical links
everywhere in the template.

The most common call to build a SEO URL from technical information is:

```
$query = array(
    'controller' => 'my-controller',
    'module' => 'frontend',
    'action' => 'my-action',
    'some-param' => 123456,
);

$url = Shopware()->Router()->assemble($query);
```

This will internally look up the SEO url for the route `http://my-shop.com/frontend/my-controller/my-action?some-param=123456`
and print it out, if it is available. When using smarty, you can also use the `url` plugin - it will also take care of proper
rewriting the URLs:

```
{url module=frontend controller=my-controller action=my-action some-param=123456}
```

As it is critical in terms of SEO to make sure that SEO routes are always shown, Shopware will also find all URLs in the
generated HTML code and rewrite those before returning the page to the user. For that reason, it is not necessary to rewrite
the URLs in your code - Shopware will take care of that automatically in the method `\Shopware_Plugins_Core_PostFilter_Bootstrap::filterUrls`
which will trigger `\Shopware\Components\Routing\Router::generateList`. The whole process of building SEO URLs from within
the Shopware stack is called "assembling".

The actual handling of the URL generation will happen in `\Shopware\Components\Routing\Router::assemble` - `\Shopware\Components\Routing\PreFilterInterface`
will normalize the input, `\Shopware\Components\Routing\GeneratorInterface` will generate the URL and `\Shopware\Components\Routing\PostFilterInterface`
will normalize the output.

## Routing
We already discussed the **generation** of SEO URLs as well as the **output** of those URLs in the template. How does
Shopware handle such URLs when a request hits the server, e.g. `http://my-shop.com/trends-news`?

Since Shopware 5 the central component for this is `\Shopware\Components\Routing\Router`. It has an internal collection of
`\Shopware\Components\Routing\MatcherInterface` which match a given URL to an internal URL. If a given matcher can route a
given URL, it will return a result array such as:

```
Array
(
    [module] => widgets
    [controller] => emotion
    [action] => index
    [emotionId] => 1
    [controllerName] => index
)
```

If a matcher cannot handle the request, it will return the original URL. The matchers are executed in the following order,
if one matcher does return a valid result, the other matchers will not be called at all.

* `\Shopware\Components\Routing\Matchers\RewriteMatcher`: Will look up an URL in the `s_core_rewrite_urls` table.
* `\Shopware\Components\Routing\Matchers\EventMatcher`: Will emit an event, so e.g. plugin developers can handle the request:
```
$event = $this->eventManager->notifyUntil('Enlight_Controller_Router_Route', [
    'request' => $request,
    'context' => $context
]);
```
* `\Shopware\Components\Routing\Matchers\DefaultMatcher`: Will handle technical URLs like `/frontend/listing/sCategory=3`

# Migration and customization
## Migrating URLs
Migrating from existing shops to Shopware will often raise the question, how to import the existing URLs. Generally there
are four ways to handle this:

* Import the old SEO URLs into the `s_core_rewrite_urls` table. As Shopware will not delete those SEO URLs as long as the
 corresponding products exist, those URLs will remain even if you regenerate your SEO URLs from within Shopware. Usually
 you will set `main` to 0 in order to make Shopware redirect the old SEO URLs to the new Shopware-styled SEO URLs.
* Using the event `Enlight_Controller_Router_Route`: As described above, the `\Shopware\Components\Routing\Matchers\EventMatcher` will
be used, if Shopware cannot handle the URL on its own, so a plugin could handle those URLs.
* \Shopware\Components\Routing\Router::setMatchers: If you prefer a more OOP oriented approach over the event, you can also
add your own matcher to the internal matcher collection of the router and let your matcher handle the old SEO URLs.
* PreDispatch / Dispatch loop startup: If your old URLs contain a unique identifier - e.g. the product Id or the order number - you could also
write a plugin which detects such URLs in a early event, does a dynamic lookup of the new URL and redirects to that URL using the corresponding
 status code. The solutions described are more obvious, however, and should be preferred.

## Customize replace rules
The `replaceRules` in the `sRewriteTable` class cannot be modified directly, you can create a `before` or `after` hook
for the `\sRewriteTable::sCleanupPath` method, to modify the resulting URL to your needs.

## Additional variables for the smarty SEO template
Looking at the SEO generation methods described above, you will see, that Shopware usually calls a repository function,
that will e.g. return all blog entities with all the information needed.

```
/** @var $repository \Shopware\Models\Blog\Repository */
$blogArticlesQuery = $this->modelManager->getRepository('Shopware\Models\Blog\Blog')
    ->getListQuery($blogCategoryIds, $offset, $limit);
```

Or:

```
$suppliers = $this->modelManager->getRepository('Shopware\Models\Article\Supplier')
    ->getFriendlyUrlSuppliersQuery($offset, $limit)->getArrayResult();
```

Hooking those methods in the corresponding repository will allow you, to extend the fields being available for e.g.
the blog template. For the products there is a custom SEO context query, you can hook: `\sRewriteTable::getSeoArticleQuery`,
but there is even a more convenient filter event, you can use, to manipulate the fields and variables for any product:

```
$result = Shopware()->Events()->filter(
    'Shopware_Modules_RewriteTable_sCreateRewriteTableArticles_filterArticles',
    $result,
    array(
        'shop' => Shopware()->Shop()->getId()
    )
);
```
So generally speaking, hooking the repositories or the `sCreateRewriteTable*` methods will give your pretty good
access to the SEO generation, you should prefer the corresponding events, where available, of course.
