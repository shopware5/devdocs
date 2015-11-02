---
layout: default
title: Shopware 5 events & hooks
github_link: developers-guide/shopware-5-events-and-hooks/index.md
indexed: true
---

# Introduction
In this guide we want you to understand the fundamentals in the Shopware event system.
	
You don't need to read everything, you can jump to the section you want to read. This is only a short explanation of the
different event types in Shopware.

<div class="toc-list"></div>
	
## What is an event system?
An event is an option of getting control of the program flow and extending an existing application. For example an event 
will be fired if the user clicks on a button. After the user clicked on the button the event will be triggered and your 
code, which is registrated on the event, is executed.
Another option to extend Shopware is using hooks. Through hooks you are able to modify a lot of methods in Shopware which are
not handled by the event system.
At first glance, hooks and events are looking similar but there are big differences. Hooks allow to interact with the
code that called it. They are called with the intent that we can work with the returned data of a method. Further than
every public and protected method is hookable, events on the other hand are defined points in the code where an event
will be fired. 

## Events
You can define events to occur during the workflow of the shop. These events include, for example, the registration of 
a new client or placing an order. Each page request leads into different events, which are triggered in the shop.
With each event, you can register event listeners. These distinct functions are notified automatically 
when the corresponding event is triggered.

## Controller events
The controller events are, as the name implies, linked to the controllers. Each Shopware controller has certain basic 
functions which were already discussed in the <a href="{{ site.url }}/developers-guide/controller/">Shopware controllers guide</a>. 
This also means that each controller has been implemented with one of three default events:

* PreDispatch
* PostDispatch
* methodAction

### PreDispatch
The ``PreDispatch`` event is executed before the dispatch process of the controller starts executing.

**Registration example:**
```php
$this->subscribeEvent(
    'Enlight_Controller_Action_PreDispatch_Frontend_Index',
    'onPreDispatchFrontend'
);
```

The first parameter is the event we want to attach and the second parameter in the `subscribeEvent` Method is the method 
name with the code you want to add. 

### PostDispatch
The ``PostDispatch`` on the other hand will be executed after the dispatch process of the controller is finished.

**Registration example:**
```php
$this->subscribeEvent(
    'Enlight_Controller_Action_PostDispatch_Frontend_Index',
    'onPostDispatchFrontend'
);
```

### PostDispatchSecure
We recommend to use always the ``PostDispatchSecure`` event if you want to use the ``PostDispatch`` event. The only
difference is that this event is a secure event. This means not that it is safe in meaning of security, it means that
your ``PostDispatch`` event is reliable. If you use this event you can be sure that this is not an exception or a
download etc.

**Registration example:**
```php
$this->subscribeEvent(
    'Enlight_Controller_Action_PostDispatchSecure_Frontend_Index',
    'onPostDispatchSecureFrontend'
);
```


### methodActions
The controller methods, for example indexAction, can be caught by an event too. These events are constructed using
the following syntax:

```php
Enlight_Controller_Action_MODULE_CONTROLLER_ACTION
```

The placeholders **MODULE**, **CONTROLLER** and **ACTION** represent the corresponding method on which the event should 
be registrated.
With this event type you can extend every controller action in Shopware. 

**Registration example:**
  
The Shopware checkout page is loaded via confirmAction in the frontend checkout controller. If you wish
to load your individual customizations, the following event is registered.

```php
$this->subscribeEvent(
	'Enlight_Controller_Action_Frontend_Checkout_Confirm',
	'onCheckoutConfirmAction'
);
```

### Notify events
The notify event is a purely informative event which, unlike the other two application events, 
offers no possibility for data modification or termination of the process.

**Registration example:**
```php
$this->subscribeEvent(
    'Shopware_Modules_Admin_Login_Successful',
    'onLoginSuccessful'
);
```

### Notifyuntil events
The notifyuntil event is an event which can be used to prevent certain processes. The event is always triggered in 
an ``if`` condition. Whenever the plugin registers an event and returns ``true`` the following process is no longer executed.

### Notify filter events
Shopware's notify filter events allow you to filter determined data or modify SQL statements prior to execution.  

### Collect events
Event which is fired to collect plugin parameters to register additionally application components or configurations. With
this event you can add own elements to an existing array.

### Enlight_Event_EventsArgs
The class ``Enlight_Event_EventsArgs`` serves as a helper class which serves as transfer parameters with every event. 
With this class, the event parameters are requested dynamically. An event is defined as follows:
```php
Enlight()->Events()->notify(
    'Shopware_Tutorial_Start',
    array(
        'subject'  => $this,
        'user'     => $userData,
        'basket'   => $basket,
        'payment'  => $payment
    )
);
```

Can be accessed via the various parameters:
```php
public function onTutorialStart(Enlight_Event_EventArgs $arguments)
{
    $controller = $arguments->getSubject();
    $user = $arguments->getUser();
    $basket = $arguments->getBasket();
    $payment = $arguments->getPayment()
}
```

##### getName
In addition to the dynamic parameter functions there is the function Enlight_Event_EventsArgs::getName()which returns 
the name of the event.

##### stop
As already described, it is possible to prevent the execution of the original function with the function 
``Enlight_Event_EventsArgs::stop()`` zu verhindern. Following the event listener this event will no longer be executed.

##### remove
``Enlight_Event_EventsArgs::remove()`` makes it possible to remove the transfer parameters of the event. 
``Enlight_Event_EventsArgs::remove('subject')`` removes, for example, the subject and subsequent events which no longer have 
access to these parameters.

##### getReturn
``Enlight_Event_EventsArgs::getReturn()`` provides access to the return value of the event. If other plugin event listeners, 
which set a return value, have already been executed prior to your own event listener, your event listener can access 
these values.

##### setReturn
``Enlight_Event_EventsArgs::setReturn()`` makes it possible to modify the return value of the event.

### Example
In this <a href="{{ site.url }}/developers-guide/plugin-quick-start/" target="_blank">guide</a> will be explained how to 
use and register an event correctly in a Shopware plugin.

## Hooks
Naturally, you're not able to manipulate every single shop function with the help of events. For example, every Shopware 
method does not necessarily contain more than one event and you may be bound to a specific event in the system. To make 
the plugin system completely flexible, a hook layer has been integrated into the system. This works on the proxy 
principle - all objects in Shopware (with few exceptions) are flexible and can be overlaid repeatedly. A manual 
extension of classes is not necessary.

There are three different types of hooks:
* Before hook
* After hook
* Replace hook

Hooks can be created just like the events, via the helper function subscribeEvent.
```php
$this->subscribeEvent(
    'CLASS::FUNCTION::TYPE',
    'HOOK-LISTENER'
);
```

Unlike the events, the hooks are not defined. Rather, they need to be assembled. The various components of a hook can be 
set with two colons together. Beginning with the class name, followed by the function name and last the hook type 
(after, before, replace). The second parameter, the subscribeEvent function, is similar to the events of the listener 
function.

We recommend to use events if it is possible because hooks are not backward compatible.

### Before hook
The before hook enables the execution of a function before intervening. Since the original function has not yet been 
performed, of course, there is no return value available.

**Example registration:**
```php
$this->subscribeEvent(
    'sArticles::sGetArticleById::before',
    'beforeGetArticleById'
);
```

### After hook
The after hook allows you to interrupt the execution of a function. Since in this case the original function has already 
been executed, there is now a return value available. This hook type is often used to modify already established shop 
data after the fact.

**Example registration:**
```php
$this->subscribeEvent(
    'sArticles::sGetArticleById::after',
    'afterGetArticleById'
);
```

### Replace hook
The replace hook type enables you to completely replace Shopware functions with your own plugin functions. As the 
function will be completely replaced, there is of course no return possible. This must be set in the plugin manually.

**Example registration:**
```php
$this->subscribeEvent(
    'sArticles::sGetArticleById::replace',
    'replaceGetArticleById'
);
```

With this little example, sArticles::sGetArticleById() is completely overwritten by our own logic.

### Enlight_Hook_HookArgs
The Enlight_Hook_HookArgs class serves as a helper class which, similar to the Enlight_Event_EventArgs class, serves as 
a transfer parameter for each hook. With this class the hook parameters can be queried dynamically. 
The function appears as follows:
```php
public function getSlogan($id, $articleId, $customerId, $sessionId) { }
```

the various parameters can be accessed as follows:

```php
$sloganClass = $arguments->getSubject();
 
$id = $arguments->get('id');
$articleId  = $arguments->get('articleId');
$customerId = $arguments->get('customerId');
$sessionId = $arguments->get('sessionId');
```

#### getArgs
``Enlight_Hook_HookArgs::getArgs()`` returns all transfer parameters in an array.

#### getMethod
``Enlight_Hook_HookArgs::getMethod()`` returns the name of the original function. 

#### getName
``Enlight_Hook_HookArgs::getName()`` function returns the full hook names back which are specified in the 
``$this->subscribeEvent()``. 

#### getReturn
In an after hook type the return value of the original function is available via ``Enlight_Hook_HookArgs::getReturn()``.

#### remove 
``Enlight_Hook_HookArgs::remove()`` function provides, as with the Enlight_Event_EventArgs the possibility of removing 
transfer parameters.

#### set
Via the ``Enlight_Hook_HookArgs::set($variable, $value)`` function, it is possible to modify the transfer parameters 
of the hook.
 
#### setReturn
As with the ``Enlight_Event_EventArgs`` function, the ``Enlight_Hook_HookArgs::setReturn()`` function allows you to 
manipulate the return value.

#### executeParent
In the various argument dumps, the hook listener functions make sure that with ``$arguments->getSubject()``, the actual 
original class ``sArticles`` is not returned, but rather the ``sArticlesProxy`` class. This is the proxy class ``sArticles`` 
that is generated automatically by Shopware when a hook is registered for the class. This class allows us to call the 
original function. This is very helpful when a scenario arises in a replace hook which we don't want to deal with.

### Blog - Understanding the Shopware hook system
<a href="{{ site.url }}/blog/2015/06/09/understanding-the-shopware-hook-system/">Here</a> you can read a blog post by 
Daniel Nögel who explains the Shopware hook system.