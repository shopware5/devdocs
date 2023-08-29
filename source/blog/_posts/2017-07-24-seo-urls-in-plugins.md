---
title: SEO URLs in plugins
tags:
- seo
- plugins

categories:
- dev

authors: [ps]
github_link: blog/_posts/2017-07-24-seo-urls-in-plugins.md

---

In the world of eCommerce, SEO is a very important and recurrent topic.
Thus, Shopware offers some tools to create a SEO friendly shop by default, including SEO friendly URLs.
Make sure to have a look at the following SEO blog post, covering detailed information for the Shopware SEO engine: [The Shopware SEO engine](/blog/2015/08/11/the-shopware-seo-engine/)

But for now, how do we actually create proper SEO URLs for our custom plugins?

It must have been about a year ago when I stumbled across the same issue while reworking our premium plugin
[Shopping advisor](http://store.shopware.com/en/swagproductadvisor/shopping-advisor.html).

In this blog post, I want to provide a short tutorial on how to implement custom SEO URLs for your plugins.
I'll also attach an example plugin for both Shopware 5.3 and 5.2 at the end of the tutorial.

## Generating a SEO URL for a custom controller

For this short tutorial I will use a very basic plugin based on the new plugin system.

### The scenario

As an example, I would like to create a glossary plugin.

Using the glossary plugin, the shop owner should be able to create a description for a word.
The plugin will provide an overview, showing all given words and their description.

To store those, I created a table with the name `s_glossary` and the columns `id`, `word` and `description`.

Later in this tutorial we also want to add a detail page, only showing a given word and its respective description.

Let's assume the basic plugin structure contains a registered Frontend controller called `Glossary`, as well as the mentioned
database table.

### Let's get started

For the glossary overview, we would implement an `indexAction` in our `Glossary` controller to handle the overview.
In order to call our action now, we'd open the following URL: `http://myShop.com/glossary/`

That URL looks smooth and SEO friendly already, doesn't it?

What happens, if we want the glossary page to be internationally available?
For your german customers, you would want the glossary to be available using `http://myShop.com/glossar/` as well.

This can and should be done using SEO URLs.

First of all, SEO URLs in Shopware are stored in the database table `s_core_rewrite_urls`.
We could just create a new entry in that table during the installation process of the plugin.
That would actually work for now.

Yet, we want to create those SEO URLs depending on the 'refresh strategies' configuration.

This configuration can be found in the backend: `Configuration > Cache/performance > Settings > SEO > Refresh strategy`.
Our SEO URLs are generated in three different ways, being configurable in the backend.

Available options are:
- Manually
- Via cronjob
- Live

Again, refer to this blog post to get more detailed information on how those work: [The Shopware SEO engine](/blog/2015/08/11/the-shopware-seo-engine/)

As each of the options mentioned above requires slightly different plugin logic, I'll explain them step by step.

### Implement logic for 'Via cronjob'

<div class="alert alert-danger" role="error">
    The following code is only compatible with Shopware version 5.3 or higher.
</div>

In **Shopware 5.3** we implemented a new event to SEO URL generation using the cronjob. <br />
Everytime the cronjob `RefreshSeoIndex` is triggered, the method `onRefreshSeoIndex` in [engine/Shopware/Plugins/Default/Core/RebuildIndex/Bootstrap.php](https://github.com/shopware5/shopware/blob/5.3/engine/Shopware/Plugins/Default/Core/RebuildIndex/Bootstrap.php#L134) is called. <br />
It now contains a new notify event called `Shopware_CronJob_RefreshSeoIndex_CreateRewriteTable`, which we will use to add our own SEO URL generation process.
The event is called once for each shop after every other SEO URL (e.g. Products, Categories, ...) has been generated for this shop.

```
public static function getSubscribedEvents()
{
    return [
        'Shopware_CronJob_RefreshSeoIndex_CreateRewriteTable' => 'createGlossaryRewriteTable'
    ];
}

public function createGlossaryRewriteTable()
{
    /** @var \sRewriteTable $rewriteTableModule */
    $rewriteTableModule = Shopware()->Container()->get('modules')->sRewriteTable();
    
    // Insert new rewrite URL for our custom controller
    $rewriteTableModule->sInsertUrl('sViewport=glossary', 'glossary/');
}
```

In the example mentioned above, we would create a new rewrite URL for each shop.
Of course, in this code we could and should now build our logic to create the translated rewrite URLs, e.g. `http://myShop.com/glossar`, which would be the german translation for it. 

### Implement logic for 'Live'

This option does **not** mean, that with each and every request the SEO URLs are re-generated.
You can configure the refresh interval in the backend under `Configuration > Cache/performance > Settings > SEO > Refresh strategy`.

Basically, whenever a request is sent to the shop and the response is about to be sent back, Shopware checks if it's time to re-generate the SEO URLs.
In only that case (refresh strategy is 'live' AND the interval has passed), the method `sCreateRewriteTable` from our core module [sRewriteTable](https://github.com/shopware5/shopware/blob/5.3/engine/Shopware/Core/sRewriteTable.php#L220) is called.

This method only generates the SEO URLs for the **currently** active shop.

Therefore we could use an after hook on the method mentioned above.
The code to actually insert our URL into the database is the same, so we can just re-use the same code with a different event.

```
public static function getSubscribedEvents()
{
    return [
        'Shopware_CronJob_RefreshSeoIndex_CreateRewriteTable' => 'createGlossaryRewriteTable',
        'sRewriteTable::sCreateRewriteTable::after' => 'createGlossaryRewriteTable',
    ];
}

public function createGlossaryRewriteTable()
{
    /** @var \sRewriteTable $rewriteTableModule */
    $rewriteTableModule = Shopware()->Container()->get('modules')->sRewriteTable();
    $rewriteTableModule->sInsertUrl('sViewport=glossary', 'glossary/');
}
```

That's it for the live mode.

### Implement logic for 'Manual'

<div class="alert alert-danger" role="error">
    The following code is only compatible with Shopware version 5.3 or higher.
</div>
<div class="is-float-right">
    <img alt="Overview of the SEO URL concept" src="/blog/img/manual-seo-generation-win.png">
    <div><em>The manual SEO URL generation window</em></div>
</div>

This is where things become a little tricky.
The manual URL generation is actually handled in ExtJs, generating the URLs in a batch mode.

You can choose a batch size, which defines how many URLs should be generated with each request.

We want to have our own progress bar at the bottom of the window now to generate our SEO URLs for the currently selected shop in batch mode.

First of all we have to extend the file [themes/Backend/ExtJs/backend/performance/view/main/multi_request_tasks.js](https://github.com/shopware5/shopware/blob/5.3/themes/Backend/ExtJs/backend/performance/view/main/multi_request_tasks.js#L83).
We have to extend the property 'seo', which contains all progress bars, their snippets and, most important, the request URL to be called for each batch call to generate the SEO URLs.

So, let's overwrite the ExtJs window.
I won't go into detail on how to extend an ExtJs file. Refer to this guide about [extending the backend](/developers-guide/backend-extension/#example-#1:-simple-extension) instead.

*Register new event:*
```
public static function getSubscribedEvents()
{
    return [
        ...
        'Enlight_Controller_Action_PostDispatch_Backend_Performance' => 'loadPerformanceExtension'
    ];
}
```

<br />

*The respective listener:*
```
public function loadPerformanceExtension(\Enlight_Controller_ActionEventArgs $args)
{
    $subject = $args->getSubject();
    $request = $subject->Request();

    if ($request->getActionName() !== 'load') {
        return;
    }

    $subject->View()->addTemplateDir(__DIR__ . '/Resources/views/');
    $subject->View()->extendsTemplate('backend/performance/view/glossary.js');
}
```

With Shopware 5.3 we implemented a new method called `addProgressBar` to `multi_request_tasks.js`.
As the first parameter you have to provide an object containing an 'initialText' to be shown initially, a 'progressText' to be shown while generating the SEO URLs
and a 'requestUrl' to be called with each step in the batch processing.
The second parameter has to be a name for the new progress bar - we need this one later.
The third parameter should be the target. Possible values are 'seo' and 'httpCache'. As we want to create a new progress bar to the SEO window, we'll use 'seo' here obviously.

```
//{block name="backend/performance/view/main/multi_request_tasks" append}
Ext.define('Shopware.apps.Performance.view.main.Glossary', {
    override: 'Shopware.apps.Performance.view.main.MultiRequestTasks',

    initComponent: function() {
        this.addProgressBar(
            {
                initialText: 'Glossary URLs',
                progressText: '[0] of [1] glossary URLs',
                requestUrl: '{url controller=glossary action=generateSeoUrl}'
            },
            'glossary',
            'seo'
        );

        this.callParent(arguments);
    }
});
//{/block}
```

Once we refresh the backend and probably clear the cache, the SEO window should now contain our new progress bar.
Now we need to create our backend controller and a `generateSeoUrlAction`.

With each AJAX request for the batch processing, we'll get a shopId, an offset and a limit to properly generate our SEO URLs.
We can ignore offset and the limit **for the moment**, since there is only a single URL to be generated for each shop.
Just remember them for later in this tutorial.


*Controllers/Backend/Glossary.php*
```
<?php

class Shopware_Controllers_Backend_Glossary extends Shopware_Controllers_Backend_ExtJs
{
    public function generateSeoUrlAction()
    {
        $shopId = $this->Request()->getParam('shopId');

        /** @var Shopware_Components_SeoIndex $seoIndex */
        $seoIndex = $this->container->get('seoindex');
        $seoIndex->registerShop($shopId);

        /** @var sRewriteTable $rewriteTableModule */
        $rewriteTableModule = $this->container->get('modules')->RewriteTable();
        $rewriteTableModule->baseSetup();
        $rewriteTableModule->sInsertUrl('sViewport=glossary', 'glossary/');

        $this->View()->assign(['success' => true]);
    }
}
```

We're fetching the shopId, register a shop using the given shopId and then simply insert our rewrite URL again.

Now there's one more thing missing.
Once we select a shop in the backend SEO module, an AJAX call is sent to collect the total counts of URLs to be created with each progress bar.
Our glossary URLs are not collected yet, so the module can't handle our glossary URLs properly yet.

To collect the URLs, the `getCountAction` of the [SEO controller](https://github.com/shopware5/shopware/blob/5.3/engine/Shopware/Plugins/Default/Core/RebuildIndex/Controllers/Seo.php#L72) is called.
Thankfully it provides a filter event `Shopware_Controllers_Seo_filterCounts` to properly add our own counts. For this we need to use the name we used earlier for the progress bar.

```
public static function getSubscribedEvents()
{
    return [
        ...
        'Shopware_Controllers_Seo_filterCounts' => 'addGlossaryCount'
    ];
}

public function addGlossaryCount(\Enlight_Event_EventArgs $args)
{
    $counts = $args->getReturn();

    $counts['glossary'] = 1;

    return $counts;
}
```

Currently, there's only a single URL to be generated for each shop, so we'll just return a static 1.

So, now let's try it.
If you've implemented everything properly, it should work perfectly now.

## Custom parameters in SEO URL

Now we've implemented a simple SEO URL generation for our glossary plugin.
The overview is now supported by SEO friendly URLs and we even generate those URLs in a proper way, depending on the given configuration.

Now we want to have some kind of "detail" page for each word.
When calling this detailed page, we only see a single word with its related description.

For this we need a new action in our **Frontend** Controller, e.g. "detailAction".
We could call this action by using an URL like `http://myShop.com/glossary/detail`.
In this case though, we would have to attach an ID for the word we want to show now.

Sounds easy, let's just attach it to the URL:
`http://myShop.com/glossary/detail?wordId=1`

This link would now display the word with the ID 1.
Wouldn't it be cooler to have the word itself as a part of the URL now?
E.g. you'd want to explain the word 'recursion', then the URL could look like this: `http://myShop.com/glossary/recursion`

Way better, isn't it?

Now this already requires several changes in our code.
First of all, every time we generate our SEO URLs, we have to iterate through all words in our database.

```
public function createGlossaryRewriteTable()
{
    /** @var \sRewriteTable $rewriteTableModule */
    $rewriteTableModule = Shopware()->Container()->get('modules')->sRewriteTable();
    $rewriteTableModule->sInsertUrl('sViewport=glossary', 'glossary/');

    /** @var QueryBuilder $dbalQueryBuilder */
    $dbalQueryBuilder = $this->container->get('dbal_connection')->createQueryBuilder();

    $words = $dbalQueryBuilder->select('glossary.id, glossary.word')
        ->from('s_glossary', 'glossary')
        ->execute()
        ->fetchAll(\PDO::FETCH_KEY_PAIR);

    foreach ($words as $wordId => $word) {
        $rewriteTableModule->sInsertUrl('sViewport=glossary&sAction=detail&wordId=' . $wordId, 'glossary/' . $word);
    }
}
```

Also, we need to adjust the URL counts for the backend now.

```
public function addGlossaryCount(\Enlight_Event_EventArgs $args)
{
    $counts = $args->getReturn();

    /** @var QueryBuilder $dbalQueryBuilder */
    $dbalQueryBuilder = $this->container->get('dbal_connection')->createQueryBuilder();
    $wordsCount = $dbalQueryBuilder->select('COUNT(glossary.id)')
        ->from('s_glossary', 'glossary')
        ->execute()
        ->fetchAll(\PDO::FETCH_COLUMN);

    $counts['glossary'] = $wordsCount;

    return $counts;
}
```

Do you still remember the `offset` and the `limit` parameter from the batch processing for the SEO URLs?
Now we do have to implement those, to only generate as many SEO URLs as configured in the batch process.

```
public function generateSeoUrlAction()
{
    ...

    /** @var QueryBuilder $dbalQueryBuilder */
    $dbalQueryBuilder = $this->container->get('dbal_connection')->createQueryBuilder();
    $words = $dbalQueryBuilder->select('glossary.id, glossary.word')
        ->from('s_glossary', 'glossary')
        ->setMaxResults($limit)
        ->setFirstResult($offset)
        ->execute()
        ->fetchAll(\PDO::FETCH_KEY_PAIR);

    foreach ($words as $wordId => $word) {
        $rewriteTableModule->sInsertUrl('sViewport=glossary&sAction=detail&wordId=' . $wordId, 'glossary/' . $word);
    }

    $this->View()->assign(['success' => true]);
}
```

### Add foreign parameters

While this already looks good, there's one more thing to do.
Shopware needs to know our custom parameter "**wordId**" first.
Otherwise our parameter would just get stripped and our SEO URL wouldn't work.

The possible cases for parameters are handled in the [RewriteGenerator](https://github.com/shopware5/shopware/blob/5.3/engine/Shopware/Components/Routing/Generators/RewriteGenerator.php#L166).
It has a whole lot of cases, e.g. the parameter "**sArticle**" is only allowed when used with the **detail** controller.

Thankfully, since Shopware 5.2, this method provides an event to add custom parameters.

So, let's add the event and implement our custom parameter.

```
public static function getSubscribedEvents()
{
    return [
        ...
        'Shopware_Components_RewriteGenerator_FilterQuery' => 'filterParameterQuery'
    ];
}
```

```
public function filterParameterQuery(\Enlight_Event_EventArgs $args)
{
    $orgQuery = $args->getReturn();
    $query = $args->getQuery();

    if ($query['controller'] === 'glossary' && isset($query['wordId'])) {
        $orgQuery['wordId'] = $query['wordId'];
    }

    return $orgQuery;
}
```

So, what did I do here?
First of all, Shopware doesn't know things like "controllers" or "actions" like that.
Due to legacy reasons, Shopware still needs them to be handled as 'sViewport', which would be the controller, and 'sAction',
which obviously represents action.
That's what `$orgQuery` contains: The controller mapped to 'sViewport' and the action mapped to the array element 'sAction'.
Since `$orgQuery` will be used for assembling our SEO URL later, we need to add our parameter to it.

Meanwhile, `$query` contains the actual request parameters as we know them.

We only need to add our custom parameter `wordId` if both the controller equals 'glossary' and the parameter itself is set.
In that case, we add `wordId` to `$orgQuery` and return it afterwards.


## Example plugin
You can find the example plugin for **Shopware 5.3** <a href="{{ site.url }}/exampleplugins/SeoExample.zip">here</a>.

Just to make sure: **This is not a fully functional plugin as it is only supposed to be an example.**
It will create the necessary plugin table *s_glossary* with a few example words.
This plugin does not provide a backend module to work with and the frontend templates are very slim to show the basic functionality.

### Shopware 5.2 plugin
We've also created an example plugin for **Shopware 5.2**, which can be found <a href="{{ site.url }}/exampleplugins/SeoExample52.zip">here</a>.

There's several differences, e.g. the whole *Resources/views/backend* directory is different.
Additional to that, the logic to count the available glossary URLs had to be changed, since the event we used above was implemented with 5.3.
