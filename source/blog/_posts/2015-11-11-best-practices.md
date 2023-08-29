---
title: Best practices of Shopware plugin development
tags:
    - plugin development
    - best practices

categories:
- dev
indexed: true
authors: [dn]
---
Developing a Shopware plugin is quite easy: Create some directories and a `Bootstrap.php` file, register some events, add some
templates - done.
Of course it's not always that easy in reality. A naive approach and not thinking about the structure of the plugin too much
will lead to bad maintainability and testability and make your work a lot harder. Especially in bigger projects, you
need to make sure that the quality of the plugins allows changing requirements, adding new features in later steps of
the project or even a change of developers.

This article will summarize some best practices regarding plugin development that I found to be helpful in the daily
plugin business. I will not pretend that these tips and experiences are true for every project and every developer:
but I think they should be helpful, if you are wondering which way to go.

<div class="toc-list" data-depth="3"></div>


## Cleaning up the Bootstrap.php
The `Bootstrap.php` file, as the main entry point of every plugin, tends to be bloated in many plugins I've seen. There isn't
actually a good reason for this. The fact that there is a `Bootstrap.php` doesn't mean that you have to put all
your plugin's logic into it.


### Install / update
Over time, the `install` and `update` methods might become quite bloated. For this reason,
I recommend to have e.g. a `Bootstrap\Form`, a `Bootstrap\Database` and a `Bootstrap\Emotion` class, that have a `install`
and `update` method you call correspondingly.

You will find more information about how to create those classes in the [Services](/blog/2015/11/11/best-practice-of-shopware-plugin-development#services)
section of this document. Long story short: move your install / update logic to helper classes, if things become
too bloated.

### Event registration and callbacks
Using the `subscribeEvent` method is a quick way to subscribe to an event. As the callbacks are usually defined in the
`Bootstrap.php` file, those event subscribers tend to bloat the Bootstrap file. For that reason, the **`subscribeEvent` method
is the recommended way to register events for Shopware beginners** - once you are more familiar with Shopware, you can
make use of Subscribers.
Subscribers are basically custom classes which register to Shopware events. This way, the Bootstrap doesn't know about the
events, which can, in turn, be encapsulated in corresponding classes. Let's have a quick look:

```
class Shopware_Plugins_Frontend_MyPlugin_Bootstrap extends Shopware_Components_Plugin_Bootstrap
{
    public function install()
    {
        $this->subscribeEvent('Enlight_Controller_Front_StartDispatch', 'onRegisterSubscriber');
        $this->subscribeEvent('Shopware_Console_Add_Command', 'onRegisterSubscriber');

        return true;
    }
}
```

In the install method of the plugin I register to an early Shopware event called `Enlight_Controller_Front_StartDispatch`.
So I will still need this one event to bring my subscribers into play - but afterwards, most other events can be
registered using subscribers.

The event callback `onRegisterSubscriber` usually looks like this in my plugins:

```
public function onRegisterSubscriber(Enlight_Event_EventArgs $args)
{
    // setting everything up
    $this->registerMyTemplateDir();
    $this->registerMyComponents();
    $this->registerCustomModels();
    $this->registerNamespaces();
    $this->registerMySnippets();

    $subscribers = array(
        new \Shopware\MyPlugin\Subscriber\ControllerPath(),
        new \Shopware\MyPlugin\Subscriber\Container(),
        // as many subscribers as you like…
    );

    foreach ($subscribers as $subscriber) {
        $this->Application()->Events()->addSubscriber($subscriber);
    }
}
```

In the event callback there are mainly two things to be done:

* registering template dirs, namespace, models etc - so everything you might need later on
* create instances of the subscriber classes and pass them to the event managers `addSubscriber` method

What do the subscribers now look like? The `ControllerPath` subscriber gives a good example:

```
<?php
namespace Shopware\MyPlugin\Subscriber;

class ControllerPath implements \Enlight\Event\SubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return array(
            'Enlight_Controller_Dispatcher_ControllerPath_Frontend_MyPlugin' => 'onGetControllerPromotionFrontend',
            'Enlight_Controller_Dispatcher_ControllerPath_Api_Promotion' => 'getApiControllerPromotion',
        );
    }

    public function onGetControllerPromotionFrontend()
    {
        return __DIR__ . '/../Controllers/Frontend/MyPlugin.php';

    }

    public function getApiControllerPromotion(\Enlight_Event_EventArgs $args)
    {
        return __DIR__ . '/../Controllers/Api/Promotion.php';
    }
}
```

As you can see, `getSubscribedEvents` will just return an array that maps event names to callback methods within the subscriber.
It is basically the same mechanism that is used in the `Bootstrap.php`, but it replaces `subscribeEvent` calls with subscriber
classes.

This has two main benefits:

* You can sort your subscribers by domain / topic: You can have subscribers for controller path registration, for checkout extension,
for extension of the article backend module, etc. Thus subscribers will increase readability and maintainability of your plugin.
* As subscribers are registered during runtime, there is *no need to reinstall* the plugin after you added a new
event or subscriber to your plugin. This makes development easier and faster.

By the way: not only events can be handled in this way: hooks can also be registered using subscribers.
In order to get started with subscribers, I highly recommend the [Shopware plugin code generator](/blog/2015/09/01/generating-plugins-with-the-cli-tools/).

Be aware that the `Enlight_Controller_Front_StartDispatch` event, which we used to initialize the subscribers, will
not be triggered when Shopware is used in command line mode. In this case you might need another base event.

## Services

Subscribers are not the solution to another problem you'll find in many plugins: Too much logic in the event callbacks.
Events / event callbacks / subscribers do link the Shopware logic with your plugin's logic - they are not actually a part
of the business logic.
Let's look at a simple example: Imagine you want to block users from logging in using some custom logic. A quick way to do it could be this one:
```
public function install()
{
    $this->subscribeEvent('Shopware_Modules_Admin_Login_FilterResult', 'filterUserLogin');
    return true;
}

public function filterUserLogin(\Enlight_Event_EventArgs $args)
{
    $email = $args->get('email');
    $originalResult = $args->getReturn();

    $id = Shopware()->Db()->fetchOne('SELECT id FROM s_user WHERE email = ?', [$email]);
    $openOrders = Shopware()->Db()->fetchOne('SELECT SUM(invoice_amount) as total, COUNT(id) FROM s_order WHERE cleared = 17 AND userID = ?', [$id]);

    if (count($openOrders) > 3 || $openOrders['total'] > 100) {
        $originalResult[0][] = 'You cannot have more then three open orders or more then an open total of 100€. PAY YOUR BILL, MAN!';
        return $originalResult;
    }
}
```

As you can see, this is not a long callback - but it has quite some logic in it, if you think about it:

* How to find a user by email - actually there is even an error in here, as a user might have multiple account with the same
email address using guest accounts
* What is our criteria for blocking? The open orders (cleared=17) and open order total
* What is the amount for blocking? More then three open orders OR a total bigger then 100€.

From my perspective, one could easily make two services from it, depending on how you actually model your classes. Many
developers are not aware of how easily a service can be created in Shopware, so let's have a look at this:

In the subscriber example above, a function `registerNamespaces` was called. This might look like this:

```
public function registerNamespaces()
{
    $this->Application()->Loader()->registerNamespace(
        'Shopware\Plugins\MyPlugin',
        $this->Path()
    );
}
```

It will register the namespace `Shopware\Plugins\MyPlugin` to the current plugin directory. Instead of calling this
from the `onStartDispatch` callback, you can also call it in the `afterInit` method of your plugin. Either way: once you have
this call in place, creating a service is really easy:

```
namespace Shopware\Plugins\MyPlugin;

class MyService
{
    public function say($what)
    {
       echo $what;
    }
}
```

This class can be easily instantiated from everywhere in your plugin:

```
$myService = new Shopware\Plugins\MyPlugin\MyService();
$myService->say('hi');
```

Please notice, that `Shopware()->Db()` isn't recommended for services. Either inject it into your service - or inject
the DBAL connection into your service - this is a handy PDO wrapper from the doctrine project (see [DBAL connection](#dbal-connection)).

## Dependency Injection

If your plugin has more complex services with many dependencies, you should to put them in the dependency injection container (DIC).
This container will take care of resolving the dependencies and will, by default, create only one instance of your service,
even if it is requested multiple times.

Currently new services need to be added to the DI container using events - but it is quite easy, if you use subscribers:

```
namespace Shopware\Plugins\MyPlugin\Subscriber;

class Container implements \Enlight\Event\SubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return array(
            'Enlight_Bootstrap_InitResource_myplugin.my_service' => 'onCreateMyService',
            'Enlight_Bootstrap_InitResource_myplugin.my_other_service' => 'onCreateMyOtherService'
        );
    }

    public function onCreateMyService()
    {
        return new \Shopware\Plugins\MyPlugin\MyService(
            Shopware()->Container()->get('myplugin.my_other_service')
        );
    }

    public function onCreateMyOtherService()
    {
        return new \Shopware\Plugins\MyPlugin\MyOtherService(
            Shopware()->Container()->get('dbal_connection')
        );
    }
}
```

This will register two services, `myplugin.my_service` and `myplugin.my_other_service` in the DIC. Please be aware that this
is done *lazy*, so the services will only be created if and when they are actually requested. A service is registered using
the `Enlight_Bootstrap_InitResource_` event and append the service name to it. I suggest the convention
`dev prefix + plugin name + "." + service name`. This will make sure that your service is unique in the DIC.

Ok, let's see what happens if a service is called in e.g. a controller:

```
class Shopware_Controllers_Frontend_MyController extends \Enlight_Controller_Action
{
    public function indexAction()
    {
        $this->get('myplugin.my_service')->say('hi');
    }
}
```

In the moment we ask the DIC for the `myplugin.my_service` service, it will check if that service has already been created.
If this is not the case, it will try to create an instance of it and fire the
`Enlight_Bootstrap_InitResource_myplugin.my_service` event, which we have registered to. So our `onCreateMyService` method
will be called. In this method, we are requesting another service using `Shopware()->Container()->get('myplugin.my_other_service')`.
So the DI container will fire the `Enlight_Bootstrap_InitResource_myplugin.my_other_service` event, which we also registered to,
and our `onCreateMyOtherService` method will be executed. In this method, we request the service `dbal_connection`. This will
be handled by the Shopware core - it has that service. Now that all the dependencies have been resolved, the DIC will
create the `MyOtherService` instance with a `dbal_connection` and will then create `MyService` instance with the `MyOtherService` in it.

The next time someone requests the `my_service` using `$this->get('myplugin.my_service')`, the DI container will just
return the already created instance.

I highly recommend this pattern, keeping in mind the following basic rules:

* class creation may only happen in the container subscriber
* no service is allowed to call the `Shopware()` singleton - all dependencies must be injected using the `__construct`
method.

Of course there might be exceptions - but I found it useful to try to avoid any call to the `Shopware()` singleton
in my service components. It is fine for subscribers - but should be avoided elsewhere.

If the creation of services using events still feels a bit cumbersome, please try the `LazySubscriber` which can be
found in my [Github repo](https://github.com/dnoegel/lazy-subscriber/). It allows you to define dependencies like this:

```
namespace YourPlugin\Subscriber;

class ContainerSubscriber extends LazySubscriber
{
    public function define()
    {
        return [
            'my_plugin.cart' => function() {
                return new Cart();
            },
            'my_plugin.persister' => function(DIC $c) {
                return new Persister($c->get('connection'));
            }
        ];
    }
}
```

Please keep in mind, that this is an unofficial extension - there might be issues and rough edges.

## Using composer
By default plugins do not support `composer` - the main reason for this is the fact that installing composer dependencies
during runtime on the customer's system is quite error prone. But this shouldn't stop you from using composer for your
development and for updating dependencies easily.

Having a `composer.json` in the top level of your plugin will allow you to install the dependencies from composer and
also to use the composer autoloader:

```
{
    "require": {
        "some/dependency": "^4.0",
        "another/dependency": "^4.0"
    },
    "require-dev": {
        "php5-sqlite": "*"
    },
    "autoload": {
        "psr-4": {
            "Shopware\\Plugins\\MyPlugin\\": "src/"
        }
    }
}

```

After a `composer install` or `composer dump-autoload` you can simply require the `autoload.php` in your plugin bootstrap:

```
public function registerMyComponents()
{
    require_once $this->Path() . '/vendor/autoload.php';
}
```

This will not only make the required PHP packages available in the autoloader - it will also make your plugin's namespace
available. Please notice that we highly discourage using `require*` and `include*` otherwise - but for this particular usecase it's quite helpful.

## Testing
Unit testing plugins is not as hard as many developers seem to believe. The [Shopware plugin code generator](/blog/2015/09/01/generating-plugins-with-the-cli-tools/)
will automatically create the basics for your. Generally you will will need a `phpunit.xml[.dist]` file that might look
like this:

```
<?xml version="1.0" encoding="UTF-8"?>
<phpunit bootstrap="./tests/Bootstrap.php">
<testsuite name="MyPlugin Test Suite">
    <directory>tests</directory>
</testsuite>
</phpunit>
```

The `tests/Bootstrap.php` will take care to set everything up properly. It might look like this:

```
<?php
// setup Shopware properly, so we can access e.g. Shopware()->Db()
// the directory traversal will include the TestHelper class from Shopware's
// builtin testsuite
require "./../../../../../../tests/Shopware/TestHelper.php";

// Create an instance of the Shopware-TestHelper object, run the loader
$helper = \TestHelper::Instance();
$loader = $helper->Loader();

// If you are using subscribers, you might need to trigger the `onStartDispatch` on your own
Shopware()->Plugins()->Frontend()->MyPlugin()->onStartDispatch();

// Depending on your tests, you might also need to set a request object
Shopware()->Front()->setRequest(new Enlight_Controller_Request_RequestHttp());,
```

Finally a test might look like this:

```
<?php

class CallculatorTest extends Shopware\Components\Test\Plugin\TestCase
{
    protected static $ensureLoadedPlugins = array(
        'MyPlugin' => [
            'some_config' => 'foo'
        ]
    );

    public function testMyService()
    {
        $service = new MyService();
        $result = $service->add(1, 1);

        $this->assertEquals(2, $result);
    }
}
```

You can run this test by just typing `phpunit` from your plugin directory. If you don't have `phpunit` installed globally,
just read the [Using composer](#using-composer) section and add `phpunit` as a plugin dependency by calling `composer require --dev phpunit/phpunit`.
You will then be able to run `php vendor/bin/phpunit`.

The section will ensure that the `MyPlugin` plugin is installed and activated for the current test. You can even set
the configuration of the plugin per test.

Generally you might not need to set `$ensureLoadedPlugins` for every test - actually the better your unit tests are, the
less you will need all the Shopware stack in your tests. But don't worry about this too much. As a basic rule of thumb
for "getting started with unit tests" I would suggest that, if you want to make sure that the class you just wrote works,
do not try it by testing the functionality in the browser: write a test for your service and make that test work.

You will find it very hard to test an event callback - it is. But for that reason, we move the logic to small classes and
services. This way, we can test those instead of the `Bootstrap.php` or the `Subscriber` classes. There is a lot more too say
and learn about testing - but I think the most important thing is to get started with it and write code that is testable.
This is not only good for testability - but also for the code quality.

## plugin.json
Some time ago, we started to move all the meta data from the plugin's `Boostrap.php` to a file called `plugin.json`.
An example can be found in the [Paypal plugin on github](https://github.com/shopware5/SwagPaymentPaypalPlus/blob/master/plugin.json).

I find it very convenient to use this file - it will give you a good overview regarding Shopware version compatibility,
change logs and other relevant information. If we release our Shopware account API at some point in the future, the
`plugin.json` format will allow you to upload plugins to the store by just entering one command in the command line.
And even without it, it's just handy.

## Use the code generator
I mentioned it a few times before: use the [Shopware plugin code generator](/blog/2015/09/01/generating-plugins-with-the-cli-tools/).
At the current point it allows you to generate plugins having

* Backend widgets
* Backend modules
* API endpoints
* Frontend filter
* Subscribers
* CLI commands
* Tests

The examples in the plugin generator reflect our current "state of the art" - so having a look at it is a good addition
to reading this blog post.

## Writing bigger plugins
In some projects, I found people having literally dozens of plugins in their system. Even though Shopware is capable of
this, I found that it does not help maintainability to spread all the logic across so many plugins, especially if these
plugins are specific for that very project. Using the services and subscriber mentioned above, you are completely
free to have bigger plugins with a more sophisticated directory hierarchy:

```
MyPlugin/
├── Bootstrap.php
├── Models
│   └── MyPlugin
│       ├── StockManagement
│       │   ├── Location.php
│       │   └── StockManagement.php
│       └── Voucher
│           ├── VoucherCode.php
│           └── Voucher.php
├── Services
│   ├── StockManagement
│   │   ├── Locations.php
│   │   ├── Repository.php
│   │   └── Validator.php
│   └── Voucher
│       ├── Discount.php
│       ├── Repository.php
│       └── Validator.php
└── Subscribers
    ├── StockManagement
    │   ├── ArticleDetail.php
    │   └── Checkout.php
    └── Voucher
        ├── Checkout.php
        └── Listing.php
```

In this example, the plugin was split into two main domain entities: `Voucher` and `StockManagement`. The services as well
as the models and subscribers do reflect this separation. Event though this might not be suitable in every case - having
*one* plugin which creates *multiple* new API endpoints seems to be perfectly fine. I think it shows how too much plugin
fragmentation could be avoided.

## How to find extension points?

A very common request is how to know how to extend a certain behaviour. There is no definitive "all the events"
overview - and I think it wouldn't help you much anyway. Usually, it's much more helpful to just
have a look at the program flow:
In Shopware, every request will hit a controller - generally the URL will tell you which controller is involved.
As soon as a controller is involved, there
are `PreDispatch` and `PostDispatch` events available. These extension points are quite generic and usually they are
best point for assigning template variables or adding additional template overwrites. You can also replace
any existing template variable from here.
As controllers are hookable, usually there are also controller hooks available. In most cases, these will
offer you the same possibilities as `PreDispatch` and `PostDispatch` events, but in some cases, they might give you
access to protected and public controller methods, which are not controller actions.

In most cases, a frontend controller will then trigger a core module (sArticles, sOrder etc). As all the core modules are
hookable, any protected and public methods in there are, again, extensible. In addition to that,
many core methods will offer some application events, which can be identified by the `notify`, `notifyUntil`, `filter`
and `collect` method calls. Again, all these are extension points.

Finally the core modules might call one or multiple services in the DI container. These might be service like
`shopware_storefront.list_product_service`. Usually it is worth to have a look at those classes too. As soon
as they have an `interface` defined, you will be able to [decorate](/developers-guide/shopware-5-core-service-extensions/) them.

As a rule of thumb, I suggest this priority of extension points:

* Application events (if available)
* Decoration (if available)
* Global events (such as `PreDispatch` and `PostDispatch`)
* Hooks (such as `sArticle::sAddArticle::after`)

## Shopware()
Since Shopware 4.2.0 we are moving towards a more service oriented style of programming. But still you'll
find many plugins and core modules using the `Shopware()` singleton. Technically the `Shopware()` singleton
is not too much different from a container. In fact, both can be used to form a `service registry` pattern
which will usually "pollute" all your code with unclear dependencies. So it doesn't make much
of a difference, if you use `Shopware()` in a service or inject the whole DI into it - both should be
avoided.

As mentioned in the [dependency injection](#dependency-injection) section,
you should always make sure that the immediate dependencies of your service are injected
using constructor or setter injection.
Usually you will have one place where all the services are
created - I called it `ContainerSubscriber` in the examples above. I consider all the Subscribers
being a "bridge" between your application and the Shopware core. In the subscribers, don't bother
too much about not using `Shopware()` or not using `Shopware()->Container()`. Subscribers are bound
to Shopware anyway - by events, hooks, decorators, request, response and other kind of context object.
Using `Shopware()` or `Shopware()->Container()` here is not a big code smell - but really take care to
not have it in your service layer.

## Doctrine

Shopware uses Doctrine a lot, the backend and the API make massive use of Doctrine ORM, the frontend uses the Doctrine DBAL query builder.
Generally you have to keep in mind that an ORM adds quite some complexity to the system - and that it might have
unexpected performance implications when e.g. querying many entities with `n:m` relations.
As a rule of thumb I recommend using Doctrine ORM for backend modules - in combination with the
[shopware backend components](/developers-guide/backend-components/basics/) it will make writing backend modules
a whole lot easier - and usually the backend does not require high performance in the way the frontend does.

In the frontend, however, my recommendation is to not use Doctrine ORM too extensively, especially on performance
critical pages such as listing, detail page and checkout / basket. If you don't want to end up writing plain SQL queries
again, Doctrine DBAL query builder is a nice tool, which provides a fluent interface for building SQL queries.

## DBAL connection
The DBAL query builder is a part of the DBAL library from doctrine. We found it very useful, as it provides a nice
API to build queries and can be used e.g. to allow plugins to modify a query easily. Also you can still use
plain SQL, if you need to.

The DBAL connection is available in the Shopware DI container as `dbal_connection`:

```
$queryBuilder = $container->get('dbal_connection')->createQueryBuilder();
```

So you can easily inject the query builder into your services. In those you could create queries like this:

```
$this->queryBuilder->select('category.path')
    ->from('s_categories', 'category')
    ->where('category.id = :id')
    ->setParameter(':id', $categoryId);

$path = $this->queryBuilder->execute()->fetch(PDO::FETCH_COLUMN);
```

As you can see, the plain table names and columns are used here - not the doctrine models. So it can also be used
for entities, which do not have doctrine models at all. So whenever you want to avoid Doctrine ORM, the DBAL connection
is worth a look. We also use it e.g. for the new store front bundles a lot.


## Structs
PHP arrays are quite powerful, as they can be used as lists as well as hash maps / dictionaries. This, however, is also
criticised a lot, as this will lead to undocumented data structures you can neither type hint nor rely on. A typical
 action when dealing with arrays is: Let's print it out and see, what's in it.

Doctrine models seem to be a solution at the first look - but despite the fact that you might not want to use them in any
case (see above), Doctrine models are representation of database records. This will not be true for all your objects and
can lead to problems regarding persisting / flushing them accidentally.

In Shopware 5 we introduced `structs` - this is how we call simple value objects which are used instead of just arrays.
They might look like this:

```
namespace Shopware\Bundle\StoreFrontBundle\Struct;

class BaseProduct extends Extendable implements \JsonSerializable
{
    protected $id;

    protected $variantId;

    protected $number;

    public function __construct($id, $variantId, $number)
    {
        $this->id = $id;
        $this->variantId = $variantId;
        $this->number = $number;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getNumber()
    {
        return $this->number;
    }

    public function getVariantId()
    {
        return $this->variantId;
    }
}
```

This simple struct identifies a product in Shopware - by `id`, `variantId` or `number`. You can always rely on all values
being there - and you can properly type hint it. Shopware has a struct base class
`\Shopware\Bundle\StoreFrontBundle\Struct\Struct` - which you can extend from, if you want to.

Generally using this kind of "value object" / "struct" is considered good practice if you need to pass data structures
to other methods or if classes rely on such data structures. Try to avoid arrays for this.

## Plugin updates

If you maintain a plugin for longer time, the update method might become quite messy. Please remember that Shopware
will give you the version number of the old plugin version as a parameter in the `update` method, so you can do
something like this:

```
public function update($existingVersion)
{
    if (version_compare($existingVersion, '1.1.0', '<=')) {
        $this->fixThis();
    }
    if (version_compare($existingVersion, '1.2.1', '<=')) {
        $this->fixThat();
    }
    if (version_compare($existingVersion, '1.2.3', '<=')) {
        $this->dontForgetAboutThis();
    }
    if (version_compare($existingVersion, '2.1.0', '<=')) {
        $this->thisOneThing();
    }
}
```

Some developers also use fallthrough switch cases for this:

```
public function update($existingVersion)
{

    switch (true) {
        case version_compare($existingVersion, '1.1.0', '<='):
            $this->fixThis();
        case version_compare($existingVersion, '1.2.1', '<='):
            $this->fixThat();
        case version_compare($existingVersion, '1.2.3', '<='):
            $this->dontForgetAboutThis();
        case version_compare($existingVersion, '2.1.0', '<='):
            $this->thisOneThing();
    }
}
```

The general recommendation is: You will probably not need to use the update method for every plugin version you
release, as some plugin versions just have code changes. So you will probably never know which exact versions are in existence -
for this reason your update logic should be written in a way, that it applies to all versions e.g. "being smaller than 2.1.0".

Also keep in mind that moving the update logic to separate classes (for big plugins, consider per-version migration files)
might help keeping the `Bootstrap.php` file small.
