---
title: Bootstrapping Shopware: The dispatch loop
tags:
    - dispatch loop
    - controller
    - events
    - Shopware bootstrapping

categories:
- dev
indexed: true
github_link: blog/_posts/2015-08-26-dispatch-loop.md

authors: [dn]
---
When writing Shopware plugins, you will usually use events like `Enlight_Controller_Dispatcher_ControllerPath_Frontend_Test`
or `Enlight_Controller_Action_PreDispatch_Frontend_Listing`. But where do those names originate from and what is the
"big picture" behind it?
This post will explain what happens "behind the scenes", when Shopware boots up and handles a request.

# Dispatch Loop
Dispatching is the process of handling a `Request` object, extracting the relevant `Module`, `Controller` and `Action` from it,
instantiating the correct controller and making this controller handle the request.
As controllers can forward to other controllers, this is actually a loop - Shopware will run through that loop, until no
additional requests needs to be handled.

# Where it all begins: The front controller
Whenever a request hits your server, your web server will redirect it to the `shopware.php` file. There shopware will
instantiate the `\Shopware\Kernel`, which implements `\Symfony\Component\HttpKernel\HttpKernelInterface`. The `handle`
method of the kernel will then bootstrap the rest of Shopware (e.g. plugins) and start the dispatch loop.
The actual dispatch loop is handled by `\Enlight_Controller_Front::dispatch`.
This front controller will take care of the following tasks:

* run the router on the request object, so the router can figure out, which `Module`, `Controller` and `Action` needs to be requested
* run the dispatch loop, until there is nothing more to process
* within any loop iteration, trigger the `\Enlight_Controller_Dispatcher_Default::dispatch` which will take care of the rest

The front controller also emits some very early events, that might be useful in some cases:

* `Enlight_Controller_Front_StartDispatch`: At the very beginning, before the front controller will actually handle the request
* `Enlight_Controller_Front_RouteStartup`: Before the front controller will pass the `Request` object to the router in order to populate it
* `Enlight_Controller_Front_RouteShutdown`: Just after the router handled the `Request` object. Now the routing information is available
* `Enlight_Controller_Front_DispatchLoopStartup`: Before the dispatch loop
* `Enlight_Controller_Front_PreDispatch`: Before the current `Request` object is passed to the `Dispatcher`
* `Enlight_Controller_Front_PostDispatch`: After the current `Request` object is passed to the `Dispatcher
* `Enlight_Controller_Front_DispatchLoopShutdown`: After the dispatch loop

## Router
To get an impression of what the router does, let's look at this example: We open the URL `http://localhost/media/living/`
and look at the request object **before** routing:

```
object(Enlight_Controller_Request_RequestHttp)[379]
  private 'validDeviceTypes' =>
    array (size=3)
      0 => string 'desktop' (length=7)
      1 => string 'tablet' (length=6)
      2 => string 'mobile' (length=6)
  protected '_paramSources' =>
    array (size=2)
      0 => string '_GET' (length=4)
      1 => string '_POST' (length=5)
  protected '_requestUri' => string '/media/living/' (length=18)
  protected '_baseUrl' => string '/media' (length=6)
  protected '_basePath' => string '/media' (length=6)
  protected '_pathInfo' => string '/living/' (length=12)
  protected '_params' =>
    array (size=0)
      empty
  protected '_rawBody' => null
  protected '_aliases' =>
    array (size=0)
      empty
  protected '_dispatched' => boolean false
  protected '_module' => null
  protected '_moduleKey' => string 'module' (length=6)
  protected '_controller' => null
  protected '_controllerKey' => string 'controller' (length=10)
  protected '_action' => null
  protected '_actionKey' => string 'action' (length=6)
```

As you can see, `module`, `controller` and `action` are `null`. After the routing (the router can be found in `engine/Shopware/Components/Routing`)
the `request` object will look like this:

```
object(Enlight_Controller_Request_RequestHttp)[379]
  private 'validDeviceTypes' =>
    array (size=3)
      0 => string 'desktop' (length=7)
      1 => string 'tablet' (length=6)
      2 => string 'mobile' (length=6)
  protected '_paramSources' =>
    array (size=2)
      0 => string '_GET' (length=4)
      1 => string '_POST' (length=5)
  protected '_requestUri' => string '/media/living/' (length=18)
  protected '_baseUrl' => string '/media' (length=6)
  protected '_basePath' => string '/media' (length=6)
  protected '_pathInfo' => string '/living/' (length=12)
  protected '_params' =>
    array (size=0)
      empty
  protected '_rawBody' => null
  protected '_aliases' =>
    array (size=0)
      empty
  protected '_dispatched' => boolean false
  protected '_module' => string 'frontend' (length=8)
  protected '_moduleKey' => string 'module' (length=6)
  protected '_controller' => string 'cat' (length=3)
  protected '_controllerKey' => string 'controller' (length=10)
  protected '_action' => string 'index' (length=5)
  protected '_actionKey' => string 'action' (length=6)
```

This time, the relevant information is set, the router resolved the SEO URL `/media/living/` to the correct `module`, `controller`
and `aciton`. Now everything is prepared to handle the `request` object by the dispatcher.

## Dispatcher
The Shopware dispatcher will handle a request in `\Enlight_Controller_Dispatcher_Default::dispatch`. First of all, it will build
 a controller name from the request object, so e.g.

```
[
   'module' => 'frontend',
   'controller' => 'listing',
   'action' => 'index',
]
```

 will become `Shopware_Controllers_Frontend_Listing`. The controller dispatcher will then emit the event
 `Enlight_Controller_Dispatcher_ControllerPath_Frontend_Listing`, so that plugin developers can register this controller.
 If no plugin returns a result, the Dispatcher will try to find this controller in Shopware's controller directory `engine/Shopware/Controllers`.
Either way, the dispatcher will now have a path for the controller, e.g. `engine/Shopware/Controllers/Frontend/Listing.php`
which it now will include and instantiate. Furthermore it injects the `Request`, `Response`, `Container` and `Front` object into that controller.
As it uses a proxy object for the controller, all controllers are hookable by default.

Finally the dispatcher will call the `dispatch` method on the controller. As every controller in Shopware extends from
`Enlight_Controller_Action`, you might not have seen this method, yet - but it is always there.

## Controller dispatching
Now `\Enlight_Controller_Action::dispatch` will handle the actual dispatch for the current controller. First of all, an `\Enlight_Controller_ActionEventArgs`
object is created - it will be passed to all pre- and post dispatch events of that controller.
After that, the `PreDispatch` events are triggered, for the current controller:

```
Enlight_Application::Instance()->Events()->notify(
    __CLASS__ . '_PreDispatch',
    $args
);

Enlight_Application::Instance()->Events()->notify(
    __CLASS__ . '_PreDispatch_' . $moduleName,
    $args
);

Enlight_Application::Instance()->Events()->notify(
    __CLASS__ . '_PreDispatch_' . $this->controller_name,
    $args
);
```

This will generate events like:

* `Enlight_Controller_Action_PreDispatch`
* `Enlight_Controller_Action_PreDispatch_Frontend`
* `Enlight_Controller_Action_PreDispatch_Frontend_Listing`

depending of the current module and controller. As you see, the `Enlight_Controller_Action` prefix of the event name
directly derives from the `Enlight_Controller_Action` class, that emits the events. The first event does not have a module or
controller name in it - so every controller will emit it - that's why this event is always available.
The second event just appends the module name to the event name - that's the reason, why there is a `Enlight_Controller_Action_PreDispatch_Frontend`
event for every single controller in the frontend.

After emitting all the events, the controller dispatcher will call the `preDispatch` method of the controller itself. So if
you  have implemented the `preDispatch` method in your controller, it will now be executed.
Next the event `Enlight_Controller_Action_Frontend_Listing_Index` is emitted. This is the "action" event - if a plugin
subscribed to it, it can handle the controller action instead of the default action implementation. So instead of executing
`Shopware_Controllers_Frontend_Listing::indexAction` only the plugin subscriber will be executed.
If no plugin registered to that event, the controller dispatcher will execute the actual controller action, in our case
`Shopware_Controllers_Frontend_Listing::indexAction`. 

Now the `postDispatch` method of the controller is executed - just like the `preDispatch` method before. Finally the post dispatch events
are emitted, that you will know from most plugins:

```
if ($this->Request()->isDispatched() && !$this->Response()->isException() && $this->View()->hasTemplate()) {
    Enlight_Application::Instance()->Events()->notify(
        __CLASS__ . '_PostDispatchSecure_' . $this->controller_name,
        $args
    );

    Enlight_Application::Instance()->Events()->notify(
        __CLASS__ . '_PostDispatchSecure_' . $moduleName,
        $args
    );

    Enlight_Application::Instance()->Events()->notify(
        __CLASS__ . '_PostDispatchSecure',
        $args
    );
}

Enlight_Application::Instance()->Events()->notify(
    __CLASS__ . '_PostDispatch_' . $this->controller_name,
    $args
);

Enlight_Application::Instance()->Events()->notify(
    __CLASS__ . '_PostDispatch_' . $moduleName,
    $args
);

Enlight_Application::Instance()->Events()->notify(
    __CLASS__ . '_PostDispatch',
    $args
);
```

As you can see, the events

* Enlight_Controller_Action_PostDispatchSecure_Frontend_Listing
* Enlight_Controller_Action_PostDispatchSecure_Frontend
* Enlight_Controller_Action_PostDispatchSecure

will only be emitted, when no exception occurred, a template is available and the request has actually been handled ("dispatched").
This is, why we call them "secure", as you as a plugin developer can rely on this events indicating a "proper" dispatch and don't
need to check for e.g. exceptions by yourself. That is the reason, why we highly recommend this events over the non-secure events,
which will be emitted afterwards:

* Enlight_Controller_Action_PostDispatch_Frontend_Listing
* Enlight_Controller_Action_PostDispatch_Frontend
* Enlight_Controller_Action_PostDispatch

# Where does the template come from?
At this point, we have seen the whole dispatching in Shopware. Perhaps you will ask yourself, where the template comes into
play and when it is rendered. This is something `\Enlight_Controller_Plugins_ViewRenderer_Bootstrap` will take care of.
At the end of a request (PostDispatch), it will get the `\Enlight_View` object from the controller, render the template and
 append the result to the response object. The output of e.g. the rendered template will then happen in `shopware.php`, where it all started
 in the `\Symfony\Component\HttpFoundation\Response::send` method.

# Understanding the {action} plugin
As you might know, Shopware provides a nice Smarty plugin called `action`, that will allow you, to render results from other
controller calls into your template. If an HTTP cache is enabled, this will happen using [ESI tags](https://en.wikipedia.org/wiki/Edge_Side_Includes).
If no HTTP cache is enabled, Shopware is able to handle those requests without performing an additional HTTP request - it
will just enter a second dispatch loop and will make it handle the sub request. The resulting template of that sub request
 can then be just printed out, so that it is included in the surrounding main request.
This way, Shopware does not need to run a full bootstrapping process again - all resources like plugins, services in the DI container
or the database connection are shared.

# Symfony and Enlight
As you have seen, the actual bootstrapping of Shopware happens with a Symfony Kernel, that's why we are "Symfony http kernel compliant"
and can e.g. use the Symfony HTTP revers proxy for caching. Internally, Shopware will convert the Symfony request object
to an Enlight request object, handle it in the dispatch loop and convert the resulting Enlight response object back to a
Symfony response object.
As Enlight is basically a thin layer above the Zend framework, the dispatch loop is heavily inspired by zend framework,
the [Zend controller basics](http://framework.zend.com/manual/1.12/en/zend.controller.html) might provide you additional insights.
