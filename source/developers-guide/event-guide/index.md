---
layout: default
title: Shopware events
github_link: developers-guide/event-guide/index.md
indexed: true
menu_title: Events
menu_order: 50
group: Developer Guides
subgroup: Developing plugins
---

In order to extend Shopware or modify its behavior you will need some sort of extension system, that allows you to
hook onto Shopware. The following guide will give an overview of event systems in general and the Shopware event system
specifically

<div class="toc-list"></div>


## What is an "event system"?
Event systems also known as [publish subscribe pattern](https://en.wikipedia.org/wiki/Publish%E2%80%93subscribe_pattern)
are basically a pattern to layout software. They allow to emit an event at any point in the software - and let other pieces
of software react to that event. The main aspect is the fact, that the piece of software emitting the event does not need
to know the piece of software consuming the event. So very generally speaking, Shopware could emit an event `ORDER_FINISHED`
with the additional information `AMOUNT: 300; PRODUCTS: [SW-123, SW-456]` and a plugin developer could just subscribe to that very 
event and push those information to the ERP. 

### Important terms

* Event
    * A generic information that *something* happened. Usually a event consists of a *name* and a *payload*, in the example
    above the name was `ORDER_FINISHED` and the payload was `AMOUNT: 300; PRODUCTS: [SW-123, SW-456]`. Depending on the 
    event system, the payload can be an object, an array or any other data type.
* emit
    * Usually an event is *emitted* by telling the *event manager* that a certain event just occurred.   
* Subscribe
    * *Subscribing* is the process of telling the *event manager* that you want to be notified about certain events.  
* notify
    * When a certain event is emitted, the *event manager* will notify all subscribers, that have subscribed to that 
      event earlier. 
* event manager
    * The central instance that takes care of *emitting* events and notifies all subscribers about that event. The process
    of notifying the correct subscribers about a certain event is also called "event dispatching", as the event is "dispatched"
    to one or more subscribers.


### How does it work?
Generally when talking about events, three parties are involved:

* a subscriber registering to an event
* some code that *emits* the event
* the *event manager* dispatching the event

#### Registering an event

A very naive example could look like this: 

```
Shopware()->Events()->addListener(
    'ORDER_FINISHED',
    function($payload) {
        echo "ORDER_FINISHED just occurred with an order amount of: $payload['amount']";
    }
);
```

It will basically tell the Shopware event manager: If an event with the name 'ORDER_FINISHED' occurs, please execute the given callback. There are **more sophisticated** ways to register an event in Shopware - but this is the general mechanism.
Technically this could already be a very simple plugin, that runs some code, when ORDER_FINISHED is emitted. 

#### Emitting the event

Now how does Shopware emit those events? Consider this example of `Basket` class, which runs, when a customer finishes
an order:

```
class Basket
{
    public function buy()
    {
        // internal logic of the class
        $basket = new Basket($this->items);
        $this->em->persist($basket);
        $this->em->flush();
        
        // emitting the ORDER_FINISHED event
        Shopware()->Events()->notify('ORDER_FINISHED', ['amount' => 300,  'products' => $this->items]);
    }
}
```
In the first lines of the `buy` method the current basket is saved to the database. They are not relevant for the event.
The last line of the method will trigger the actual event:

```
Shopware()->Events()->notify('ORDER_FINISHED', ['amount' => 300,  'products' => $this->items])
```

This will emit the 'ORDER_FINISHED' event; the event manager will now call any subscriber, who registered to this event.
The second parameter is the *payload* of that event - some additional context information. In our example the basket amount
plus the items of the basket. Our event subscriber from the example above will print that amount.
 
## Events in Shopware
Events in Shopware do work pretty much as described above. There are some details, however, that will help you writing
plugins.

### Context object
The *payload* of an event is passed to the *subscriber* with a simple context object called `Enlight_Event_EventArgs`. This
is basically a container object, that will give you access to the payload. Given the example from above:  

```
Shopware()->Events()->notify('ORDER_FINISHED', ['amount' => 300,  'products' => $this->items]);
```

You can access the *payload* like this:

```
public function myEventSubscriber(\Enlight_Event_EventArgs $args)
{
    $amount = $args->get('amount'); // 300
    $products = $args->get('products'); // the items of the basket
    
}
```

### Event types
In the examples above, the `ORDER_FINISHED` event was emitted as a `notify` event: The subscriber was just informed about 
the event - but there was no way to modify something. Shopware knows 4 different event types: `notify`, `notifyUntil`,
`filter` and `collect`. These 4 types behave differently and are useful in different ways. 

#### notify
As described in the example above:

```
Shopware()->Events()->notify('ORDER_FINISHED', ['amount' => 300,  'products' => $this->items]);
```

This will emit the `ORDER_FINISHED` event and allow you to *read* `amount` and `products`. A modification is usually
not possible, except an object is passed (those could be modified by reference). 

#### notifyUntil
A `notifyUntil` event is usually used to allow you to *stop* Shopware from doing something:

```
if (Shopware()->Events()->notifyUntil('ALLOW_ORDER', ['amount' => 300,  'userId' => 3])) {
    echo 'failed';
    return false;
}
echo 'success';
```

Now imagine, you subscribed to the `ALLOW_ORDER` event with the following event callback:

```
public function myEventSubscriber(\Enlight_Event_EventArgs $args)
{
    $amount = $args->get('amount'); // 300
    $userId = $args->get('userId'); // 3
    
    if ($userId == 3 && $amount > 400) {
        return true;
    }
    
    return null;
    
}
```

In this case, we disallow orders for the user with id `3` if the order amount is greater 400. The general rule here: 
Return `null` if you want Shopware to proceed; return anything else to stop Shopware from proceeding. In the example above
Shopware will proceed and print `success`, as the the condition `$amount > 400` is false. 

Some real world examples for `notifyUntil` are:
 
* `Shopware_Modules_Order_SendMail_Send`: Prevent sending the order confirmation mail
* `Shopware_Modules_Basket_AddVoucher_Start`: Prevent adding a voucher
* `Shopware_Modules_Basket_AddArticle_Start`: Prevent adding an article to cart

#### filter
The `filter` event allows you to *modify* certain data. It is often used to allow you to modify a computed result set:

```
public function getArticlesForCategory($categoryId)
{
    $result = $this->categoryService->fetch($categoryId); // $result = ['sw-123', 'sw-456', 'sw-789']
    
    $result = Shopware()->Events()->filter('CATEGORY_ARTICLES', $result, ['categoryId' => $categoryId]);
    
    return $result;
}
```

In this example a list of articles for a given category has been loaded. The `CATEGORY_ARTICLES` event would allow you
to modify this list and return a modified set with your subscriber:

```
public function myEventSubscriber(\Enlight_Event_EventArgs $args)
{
    $amount = $args->get('categoryId'); 
    $result = $args->getReturn();
    
    $result[] = 'sw-abc';
    
    return $result;
    
}
```

In this example, the method `getArticlesForCategory` would return three article numbers for the given categoryId:
`sw-123`, `sw-456` and `sw-789`. The event subscriber reads the original result using the call `$args->get('return')` and adds
the article number `sw-abc` to the result set. This way, every call to that method would include the article `sw-abc`, 
no matter if it was included originally or not. 

Some real world examples for `filter` are:

* `Shopware_Modules_Basket_GetBasket_FilterResult`: Allows the modification of the `sGetBasket` result 
* `Shopware_Modules_Admin_GetUserData_FilterResult`: Allows modifying the result of the `sGetUserData` method
* `Shopware_Modules_RewriteTable_sCreateRewriteTableArticles_filterArticles`: Allows modifying the article context data used for the SEO engine

#### collect
The `collect` event is used in places, where Shopware wants to allow you to register e.g. handlers for certain situations.
The following example would (by default) just print `hello`:

```
$textPrinter = Shopware()->Events()->collect('TEXT_PRINTER', new ArrayCollection(['hello']));
foreach($textPrinter as $text) {
    echo $text . " ";
}

```

The following subscriber will add additional words to the `TEXT_PRINTER` event: 

```
public function myEventSubscriber(\Enlight_Event_EventArgs $args)
{
    return new ArrayCollection([
        'world',
        'how',
        'are',
        'you'
    ]);
    
}
```

Now the script would print `hello world how are you`. 
 
Some real world examples for `collect` are:
 
* `Shopware_Console_Add_Command`: Add one or more Symfony console commands
* `Shopware_SearchBundleDBAL_Collect_Facet_Handlers`: Register a facet handler for the DBAL gateway
* `Theme_Compiler_Collect_Plugin_Javascript`: Collect javascript files for the JS compiler

## Events and plugins
Usually you want to use events from within your plugins. There are generally two ways to do that:

### subscribeEvent
The default way (recommended for beginners) is the `subscribeEvent` method:

```
class Shopware_Plugins_Frontend_SwagMyPlugin_Bootstrap extends Shopware_Components_Plugin_Bootstrap
{
    public function install()
    {
        $this->subscribeEvent(
            'Enlight_Controller_Action_PostDispatchSecure_Frontend',
            'onFrontendPostDispatch'
        );
    
    
        return true;
    }
    
    public function onFrontendPostDispatch(Enlight_Event_EventArgs $args)
    {
        /** @var \Enlight_Controller_Action $controller */
        $controller = $args->get('subject');
        $view = $controller->View();
    
        $view->assign('day', date("l"));
    
    }
}
```

Using this approach, you can subscribe to events in the `install` method of your plugin using the `subscribeEvent` call.
For this call, you just pass the event name as first parameter and the name of your callback function as second parameter
(in this case it is `onFrontendPostDispatch`). The shopware event manager will now automatically call this callback
as soon as the event `Enlight_Controller_Action_PostDispatchSecure_Frontend` occurs.

### Subscribers
The above example is good for beginners - but bad for bigger plugins: It will bloat the `Bootstrap.php` file with
a lot of event callbacks - and makes it hard to separate concerns. From our experience it will result in plugins that 
are hard to maintain. For that reason we recommend the *subscriber pattern*: 

Subscribers are basically custom classes which register to Shopware events. This way, the Bootstrap doesn't know about the
events, which can, in turn, be encapsulated in corresponding classes. Let's have a look:

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

In the `install` method of the plugin an early Shopware event called `Enlight_Controller_Front_StartDispatch` is subscribed.
It is also necessary to always subscribe to the `Shopware_Console_Add_Command`.
So still these events are needed to bring my subscribers into play - but afterwards, most other events can be
registered using subscribers.

The event callback `onRegisterSubscriber` usually looks like this:

```
public function onRegisterSubscriber(Enlight_Event_EventArgs $args)
{
    Shopware()->Events()->addSubscriber(new \Shopware\MyPlugin\MySubscriber());
}

```

What do the subscribers now look like? The `MySubscriber` subscriber gives a good example:

```
<?php
namespace Shopware\MyPlugin\Subscriber;

class MySubscriber implements \Enlight\Event\SubscriberInterface
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

`getSubscribedEvents` will return an array that maps event names to callback methods within the subscriber.
It is basically the same mechanism that is used in the `Bootstrap.php`, but it replaces `subscribeEvent` calls with subscriber
classes.

This has two main benefits:

* Event callbacks can be sorted by domain / topic: There could be subscribers for controller path registration, for checkout extension,
for extension of the article backend module, etc. Thus subscribers will increase readability and maintainability of plugins.
* As subscribers are registered during runtime, there is *no need to reinstall* the plugin after new
events or subscribers have been added to the plugin. This makes development easier and faster.

By the way: not only events can be handled in this way: hooks can also be registered using subscribers.
In order to get started with subscribers, you can make use of the [Shopware plugin code generator](/blog/2015/09/01/generating-plugins-with-the-cli-tools/).

## Finding events
By the nature of events, you will always need to know the context in order to work with them properly. For that reason, there
is no generalized overview of all events. Instead of that, we suggest to step into the code, you want to extend.

### Finding application events
Application events are those events, that are explicitly emitted for a certain purpose, e.g. "stop you from buying this"
or "modify basket item price". Let's imagine, you want to stop a user from adding a certain item to the cart. The easiest
approach is to have a look which controller is responsible for the behavior you want to influence - in our case "adding
items to the cart". So in this example an item is added to the cart with an opened developer toolbar:

![inspecting](/developers-guide/event-guide/img/inspect.png)

Here you can see, that the "ajaxAddArticle" method of the "checkout" controller is called. That method will then call 
`\sBasket::sAddArticle` which takes care of the rest. Looking at that method, the following event is emitted:

```
$this->eventManager->notifyUntil(
    'Shopware_Modules_Basket_AddArticle_Start',
    array(
        'subject' => $this,
        'id' => $id,
        "quantity" => $quantity
    )
)
```

That event will finally allow us to reject certain products from being added to the basket. Stepping through the code
 this way is usually a good and fast way to find appropriate events.
 
### Finding global events
Global events are events that are emitted by our framework automatically. They are available in any request and useful,
if you cannot find a more specific application event. In order to understand the control flow, consider this diagram:

![flow](/developers-guide/event-guide/img/flow.png)

Generally speaking Shopware is about converting user requests into shop responses (green). In order to do so, the so called
"front controller" (red) will handle requests and subrequests, until there is nothing more to handle. The front controller will
call the dispatcher (red) which will figure out, which controller is responsible for the current request.
Finally a controller (blue) will be called which might a) return a response directly b) render a template / view or c) trigger
another subrequest by forwarding to another controller. 

In regards to events, this can be told apart in these steps:

* Routing: The process of figuring out, which shop resource was requested
* Dispatching: The process of handling the request
* Controller dispatching: Letting an actual controller handle the request

All these steps come with different events:

#### Routing:
The router mainly has the two events `Enlight_Controller_Front_RouteStartup` (before the routing) and
`Enlight_Controller_Front_RouteShutdown` (after the routing). During routing the event `Enlight_Controller_Router_Route`
 is emitted - it allows you to perform your own routings via plugin. See `\Shopware\Components\Routing\Router` for more details.
It is important to remember, that the full routing information (module, controller, action) is only passed with the
"RouteShutdown" event. Any event before will not have this information.

#### Dispatching:
The first dispatch event available is `Enlight_Controller_Front_StartDispatch` - it is also the first useful event in the
Shopware stack. As it is even triggered before the routing, you will not have routing information available. So choosing
one of the later dispatching events might be more suitable for some scenarios. In those cases, `Enlight_Controller_Front_DispatchLoopStartup`
might be an alternative: It is emitted just before the dispatcher enters the actual dispatch loop. Within that loop
`Enlight_Controller_Front_PreDispatch` and `Enlight_Controller_Front_PostDispatch` are emitted: Those two events
surround the controller dispatching. During that controller dispatching `Enlight_Controller_Dispatcher_ControllerPath_MODULE_CONTROLLER`
is emitted: It will allow you to return a path to the controller `MODULE_CONTROLLER`.
After all (sub)requests are handled, the event `Enlight_Controller_Front_DispatchLoopShutdown` is emitted. It is one of the latest events in the stack.

#### Controller dispatching:
Controller dispatching will happen between the `Enlight_Controller_Front_PreDispatch` and `Enlight_Controller_Front_PostDispatch`
events. As the controller dispatching is all about calling controller actions to handle a certain request (e.g. checkout/cart)
it is very useful to modify request parameters, view variables or even the view itself.

Before actually calling a controller action (e.g. checkout::cartAction), Shopware will automatically emit three
PreDispatch events for that controller: `Enlight_Controller_Action_PreDispatch` does not contain any specific namespacing,
so it is available for any request. `Enlight_Controller_Action_PreDispatch_MODULE` does contain the module name, e.g. `frontend`,
`backend`, `widgets` or `api`. It can be used to only subscribe to the PreDispatch event of a certain module. Finally
`Enlight_Controller_Action_PreDispatch_MODULE_CONTROLLER` will contain the module name as well as the controller name,
e.g. `Enlight_Controller_Action_PreDispatch_Backend_Article` - it can be used to subscribe to a specific controller in a specific module.

After the last PreDispatch event was emitted, Shopware will call the actual `preDispatch` method of the controller that was figured out by
the routing. In this case it would be `\Shopware_Controllers_Frontend_Checkout::preDispatch`. If the method is not implemented
by that controller, the base method of the base controller will be run. Now finally the `\Shopware_Controllers_Frontend_Checkout::cartAction`
from the example above would be called: From that controller method Shopware will usually call various methods
of the business logic and assign variables to the template.

After the actual controller method was run, the `postDispatch` method of the controller will be called, in this case
`\Shopware_Controllers_Frontend_Checkout::postDispatch`. Again: If the method is not implemented by the controller, the
`postDispatch` method of the base controller will be run. After that, various `PostDispatch` event are triggered:


* `Enlight_Controller_Action_PostDispatchSecure_MODULE_CONTROLLER`
* `Enlight_Controller_Action_PostDispatchSecure_MODULE`
* `Enlight_Controller_Action_PostDispatchSecure`
* `Enlight_Controller_Action_PostDispatch_MODULE_CONTROLLER`
* `Enlight_Controller_Action_PostDispatch_MODULE`
* `Enlight_Controller_Action_PostDispatch`

Basically the same mechanics as for the `PreDispatch` event apply (so there is a MODULE_CONTROLLER and a MODULE suffix)
as well as a "global" PostDispatch event without suffix. Notice that there are two types of PostDispatch event:
`Enlight_Controller_Action_PostDispatchSecure*` will only be emitted, if a *template* is available and *no exception*
occured before; the `Enlight_Controller_Action_PostDispatch` event (without *secure* as suffix) will also be emitted
when an exception occured or no template was rendered for some reason. Usually the `Enlight_Controller_Action_PostDispatchSecure*`
events are recommended - if you are not using them, you need to perform the checks by yourself.

### Container events
Technically container events are also global events - but they are specific to Shopware's integration of the Symfony
dependency injection container. `Enlight_Bootstrap_InitResource_SERVICE` will be emitted, if the service with the
name `SERVICE` was requested. In your event subscriber just return an instance of that class.
`Enlight_Bootstrap_AfterInitResource_SERIVCE` is emitted, when `SERVICE` was just loaded by the DI container. You can
use this event to decorate / replace that SERVICE with your own one.

## Some examples
The following examples should briefly show the usage of some common Shopware events.

### PostDispatch
```
class Shopware_Plugins_Frontend_SwagMyPlugin_Bootstrap extends Shopware_Components_Plugin_Bootstrap
{
    public function install()
    {
        $this->subscribeEvent(
            'Enlight_Controller_Action_PostDispatchSecure_Frontend_Checkout',
            'onFrontendPostDispatchSecure'
        );


        return true;
    }

    public function onFrontendPostDispatchSecure(Enlight_Event_EventArgs $args)
    {
        /** @var \Enlight_Controller_Action $controller */
        $controller = $args->get('subject');
        $view = $controller->View();

        $view->assign('hello', 'world');
        $view->addTemplateDir(__DIR__ . '/../Views');

    }
}
```

This controller will subscribe to the `PostDispatchSecure_Frontend_Checkout` event - so it will only be called *after*
the frontend checkout controller and only if there was *no exception* and a *template is available*. Notice, that the template
variable `hello` gets the value `world` and the template directory `Views` is registered. By registering the `Views` directory
this way, you can overwrite / extend core templates, if you create them with the same name as in the core. See
the [plugin quick start guide](/developers-guide/plugin-quick-start/#template-extension) for more
infos.

### Registering a controller
```
class Shopware_Plugins_Frontend_SwagMyPlugin_Bootstrap extends Shopware_Components_Plugin_Bootstrap
{
    public function install()
    {
        $this->subscribeEvent(
            'Enlight_Controller_Dispatcher_ControllerPath_Frontend_MyPlugin',
            'onRegisterMyPluginController'
        );


        return true;
    }

    public function onRegisterMyPluginController(Enlight_Event_EventArgs $args)
    {
        Shopware()->Template()->addTemplateDir(__DIR__ . '/../Views');
        return __DIR__ . '/../Controllers/Frontend/MyController.php';
    }
}
```

This will register a controller for the route `frontend/my_plugin`. Notice that in the event subscriber a template
directory is registered as well. This is useful, as it makes sure, that all your templates are available in the controller -
so the automatic template loading will work. Also notice, that the event subscriber needs to return the controller path.

For a more convenient way to register a controller, see [the controller documentation](/developers-guide/controller/).

### Registering a service
```
class Shopware_Plugins_Frontend_SwagMyPlugin_Bootstrap extends Shopware_Components_Plugin_Bootstrap
{
    public function install()
    {
        $this->subscribeEvent(
            'Enlight_Bootstrap_InitResource_swag_myplugin.my_service',
            'onLoadMyService'
        );


        return true;
    }

    public function onLoadMyService(Enlight_Event_EventArgs $args)
    {
        return new MyService();
    }
}
```

This example shows, how to register a service in Shopware. As soon as the service `swag_myplugin.my_service` is requested
from the DI container, Shopware will emit the event `Enlight_Bootstrap_InitResource_swag_myplugin.my_service` so you can
register to it and return an *instance* of your service. See [the service documentation](/developers-guide/services/) for
more details.


### Decorating / replacing a service
```
class Shopware_Plugins_Frontend_SwagMyPlugin_Bootstrap extends Shopware_Components_Plugin_Bootstrap
{
    public function install()
    {
        $this->subscribeEvent(
            'Enlight_Bootstrap_AfterInitResource_some_service',
            'onDecorateSomeService'
        );


        return true;
    }

    public function onDecorateSomeService(Enlight_Event_EventArgs $args)
    {
        return new MyService(
            Shopware()->Container()->get('some_service')
        );
    }
}
```
In this example the event `Enlight_Bootstrap_AfterInitResource_some_service` is used, to decorate the service `some_service`
with `MyService`. See the [core service extension documentation](/developers-guide/shopware-5-core-service-extensions/#your-extension-plugin)
for a more detailed explanation.




## Hooks
Hooks are the "last resort" for a programmer: If there is no good global or application event for your use case, hooks 
will allow you to literally "hook" into an existing function and execute your code before, afterwards or even instead the original function.

Hooks are very powerful - but also very tightly bound to our internal code. As such, events should always be used 
whenever possible, and hooks should only be used as a last resort. For that same reason, we don't allow hooks on every 
class, but only for controllers, core classes and repositories. Usually you will recognize hooks by the
`FQN::METHOD::TYPE` syntax, e.g. `sBasket::sGetBasket::after`. Valid types are `before`, `replace` and `after`.
There is a more detailed [blog post](https://developers.shopware.com/blog/2015/06/09/understanding-the-shopware-hook-system/)
about hooks in Shopware.

