---
layout: default
title: Event guide
github_link: developers-guide/event-guide/index.md
indexed: true
---

<div class="toc-list"></div>

## Introduction
This guide will lead you through the Shopware event system. You can use this as a reference for the different event
types and you can find useful tips how to find the event you want to use.

## What is an event system?
An event system will be used to extend an application with your code or reacting on an action which was triggered by
an user. For example you want to change the behaviour of a button, in that case you can use an event which will be
fired every time the button was clicked. 

## Example
To get a better idea of how events are working we will take a look at a example.

For our use case we will register on a pseudo event and try to cancel the application execution.

### Definition of an event
Events are defined points in the source code. Some events will be fired dynamically, for example:

```php
Enlight_Application::Instance()->Events()->notify(
	__CLASS__ . '_PostDispatch_' . $this->controller_name,
	$args
);
```

In our case the event is not a dynamically generated event because it will only triggered one time in the source code
when the application starts. The definition of this event could look like this in Shopware:
```php
$this->eventManager->notify(
	'Application_Start',
	$eventArgs
);
```

### Registration on an event
At first you need to register on an event in your plugin `Bootstrap.php`. In this example we will use the pseudo event `Application_Start`.

The registration would look like this:
```php
$this->subscribeEvent(
	'Application_Start',
	'onApplicationStart'
);
```
The first input parameter is the event name on which we want to register our `method`. In our example it is the `Application_Start`
event. Finally we only need to define our method name in the second input parameter, for example `onApplicationStart`.

After we registered on an event we can implement our ` callback method` which will be executed when the event was triggered.
```php
public function onApplicationStart(Enlight_Event_EventArgs $args) {
	die("This is only a test");
}
```

If you want a detailed guide of how to use an event you can take a look at the [plugin quick start guide](https://developers.shopware.com/developers-guide/plugin-quick-start/#hooking-into-the-system).

## Global lifecycle events
`Global lifecycle events` are events which will be fired with every lifecycle of a request. The `global lifecycle events` 
are defined in the class `Enlight_Controller_Front` which is located in `engine/Library/Enlight/Controller/Front.php`.

Usually these are quite technical events like
* Starting the routing
* Routing ended
* Starting dispatch loop

This list will give you an overview of all global lifecycle events in the order they will be triggered. 

* `Enlight_Controller_Front_StartDispatch`
* `Enlight_Controller_Router_Default`
* `Enlight_Controller_Dispatcher_Default`
* `Enlight_Controller_Request_RequestHttp`
* `Enlight_Controller_Response_ResponseHttp`
* `Enlight_Controller_Front_RouteStartup`
* `Enlight_Controller_Front_RouteShutdown`
* `Enlight_Controller_Front_DispatchLoopStartup`
* `Enlight_Controller_Front_PreDispatch`
* `Enlight_Controller_Front_PostDispatch`
* `Enlight_Controller_Front_DispatchLoopShutdown`


## Controller events
The `controller events` are, as the name implies, linked to the controllers. Each controller has certain basic methods
which are already explained in the [controllers article](https://developers.shopware.com/developers-guide/controller/). 
`Controller events` are defined in the `Enlight_Controller_Action` class which is located in `engine/Library/Enlight/Controller/Action.php`.
As you can see in that file the events are generated dynamically so you are able to register on every controller's `action`, 
`preDispatch` or `postDispatchSecure`.

### preDispatch
The `preDispatch` event will be executed before the dispatch process of a controller starts processing. 

### postDispatch
The `postDispatch` is executed after the dispatch process of the controller is finished. It is recommended 
to use the `postDispatchSecure` event because you can be sure that this is a valid request. This event will only fired
if a template is given. For example ajax requests and exceptions will not trigger this event.

### actionEvents
`actionEvents` will be fired everytime an action will be called. For example if the `indexAction` in the `Account` controller
was called the event `Enlight_Controller_Action_Frontend_Account_Index` will be fired.

### Event names
The event names of the `controller events` are generated dynamically. We will use in this list placeholders for the event
names so you are able to build the event name you need by yourself.

This list is also sorted according to the time in which the events are fired. 

* `Enlight_Controller_Action_PreDispatch`
* `Enlight_Controller_Action_PreDispatch_MODULE`
* `Enlight_Controller_Action_PreDispatch_MODULE_CONTROLLER`
* `Enlight_Controller_Action_MODULE_CONTROLLER_ACTION`
* `Enlight_Controller_Action_PostDispatchSecure_MODULE_CONTROLLER`
* `Enlight_Controller_Action_PostDispatchSecure_MODULE`
* `Enlight_Controller_Action_PostDispatchSecure`
* `Enlight_Controller_Action_PostDispatch_CONTROLLER`
* `Enlight_Controller_Action_PostDispatch_MODULE`
* `Enlight_Controller_Action_PostDispatch`



## Further events
`Further events` will be fired in Shopware for example when a new resource will be registered by using the dependency 
injection service.

* `Enlight_Bootstrap_InitResource_CLASS`
* `Enlight_Bootstrap_AfterInitResource_CLASS`
* `Enlight_Controller_Dispatcher_ControllerPath_MODULE_CONTROLLER`

The `Enlight_Bootstrap_InitResource` events will be used to register new namespaces and components.
`Enlight_Controller_Dispatcher_ControllerPath_MODULE_CONTROLLER` will be used to register Plugin controllers.

## Application events
`Application events` are events that are explicitly emitted in order to allow you certain modifications. In contrast to 
the very generic global events, application events have certain use cases in mind. For example:
* do not allow this customer to log in
* do not allow this product to be put in the cart
* modify the result of the current product query
* invalidate the cache for a certain prodcut id
* register a new command line tool

### Notify events
The `notify event` is a purely informative event which, unlike the other two application events, offers no possibility for 
data modification or termination of the process.

### Notifyuntil events
The `notifyuntil event` is an event which can be used to prevent certain processes. The event is always triggered in an 
`if` condition. Whenever the plugin registers an event and returns `true` the following process is no longer executed.

### Notify filter events
Shopware's `notify filter events` allow you to filter determined data or modify `SQL` statements prior to execution.

### How can I find these events?
You can find those events by using the search functionality of your IDE. After wards you will find a list with the 
search queries you can use to find those events.

**Notify:**
`Enlight()->Events()->notify(`

**Notifyuntil:**
`Enlight()->Events()->notifyUntil(`

**Notify filter:**
`Enlight()->Events()->filter(`

## Example usage in a plugin
[Here](https://developers.shopware.com/developers-guide/plugin-quick-start/#hooking-into-the-system) you can find an 
example of how an event can be used in Shopware to modify you Shop.

## Hooks
Hooks are the "last resort" for a programmer: If there is no good global or application event for your use case, hooks 
will allow you to literally "hook" into an existing function and execute your code before, afterwards or even instead the original function.

Hooks are very powerful - but also very tightly bound to our internal code. As such, events should always be used 
whenever possible, and hooks should only be used as a last resort. For that same reason, we don't allow hooks on every 
class, but only for controllers, core classes and repositories.

### Blog - Understanding the Shopware hook system
[Here](https://developers.shopware.com/blog/2015/06/09/understanding-the-shopware-hook-system/) you can find the blog 
article written by Daniel Nögel.