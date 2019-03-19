---
title: Understanding the Shopware hook system
tags:
    - hook
    - extension
    - plugin
    - system

categories:
- dev

authors: [dn]
indexed: true
github_link: blog/_posts/2015-06-09-understanding-the-hook-system.md
group: Developer Guides
subgroup: General Resources
menu_title: Hooks
menu_order: 50
---

Shopware was built with plugin developers in mind, so there are powerful ways to modify the default behaviour of the system 
without losing backward compatibility. In this post I want to discuss the technical details of Shopware's hook system.

## Hook?
Generally there are several ways to extend Shopware. By default we distinguish *global events*, *application events* and *hooks*
(for a brief overview see the [plugin quick start guide](http://devdocs.shopware.com/developers-guide/plugin-quick-start#logical-extensions)).
In addition to that, you are able to [decorate Shopware's core services](https://developers.shopware.com/developers-guide/shopware-5-core-service-extensions/).

The main difference between events and hooks is the fact that events are emitted at certain places in the source code
where a kind Shopware developer had extensibility in mind and added a (hopefully) useful event for you to use. Hooks 
are a more generic approach and allow you to extend any public or protected method in certain classes. So, even if no
explicit event is available, a hook might help you to extend Shopware's functionality. You are then able to modify
input arguments, return values or replace a method entirely. For that reason, the hook system can be considered as offering aspect oriented programming (AOP) in Shopware. 

## What is AOP?
AOP ([aspect oriented programming](http://en.wikipedia.org/wiki/Aspect-oriented_programming)) is a programming paradigm
that addresses [cross-cutting concerns](http://en.wikipedia.org/wiki/Cross-cutting_concern) in software. It allows you
to add behaviour to your objects, without having to modify the objects themselves. As PHP does not support AOP natively,
it is usually added using other patterns like the proxy pattern. 
So from an architectural point of view, one could consider Shopware implementing extensibility of some objects by using
an AOP based paradigm. The up and downsides of this will be discussed in the [Best practice?](#best-practice%3F) section. 

## Implementation details
### Proxy pattern
Technically speaking the hook system makes use of the [proxy pattern](http://en.wikipedia.org/wiki/Proxy_pattern). Hookable
classes are not instantiated directly but with a generated proxy class, which inherits from the hooked class. 

Whenever you request a core class, e.g. via `Shopware()->Modules()->Article()`, Shopware will return such a proxy for the
class name (see \Shopware_Components_Modules::loadModule for more details). You can test it easily by printing out the class
name of your article core class: `echo get_class(Shopware()->Modules()->Articles());` will print `Shopware_Proxies_sArticlesProxy`. 

### What does the proxy do?
So we already know, that - behind the scenes - we are always working with the proxy objects and not the actual class. In
most cases this does not make any difference, as the proxy object inherits from the base class. But how does the
extensibility come in?

The proxies can be found in `cache/production____YOUR_REVISION___/proxies`. When you subscribe to a hook using

```php
$this->subscribeEvent('sArticles::sGetArticleById::before', 'myCallback');
```

in your plugin's bootstrap, Shopware will regenerate those proxies and create a file like this:


```php
class Shopware_Proxies_sArticlesProxy extends sArticles implements Enlight_Hook_Proxy
{
    public function executeParent($method, $args = array())
    {
        return call_user_func_array(array($this, 'parent::' . $method), $args);
    }

    public static function getHookMethods()
    {
        return array (  0 => 'sGetArticleById',);
    }
    
    public function sGetArticleById($id = 0, $sCategoryID = NULL, $number = NULL, $selection = array ())
    {
        return Enlight_Application::Instance()->Hooks()->executeHooks(
            $this, 'sGetArticleById', array('id'=>$id, 'sCategoryID'=>$sCategoryID, 'number'=>$number, 'selection'=>$selection)
        );
    }
}
```

In this case we have three methods:

 * `executeParent` can be used from e.g. replace hooks, if you still need to call the overwritten function.
 * `getHookMethods` will return an array of hooked methods - that's only relevant for Shopware internally
 * `sGetArticleById` finally is a hooked method. As you can see, it implements the same method interface, as the original
 function. But instead of executing the original logic, it calls `executeHooks` on the `\Enlight_Hook_HookManager` with a
 reference to the proxy class (`$this`), the name of the method (`sGetArticleById`) and the method parameters.
 
So whenever `sGetArticleById` is called, it will run `executeHooks` in the `HookManager`. The result of that call will then
be returned.

### What does the HookManager do?
The actual logic of the `executeHook` method is quite simple:

```php
public function executeHooks($class, $method, $args)
{
    $args = new Enlight_Hook_HookArgs(array_merge(array(
        'class' => $class,
        'method' => $method,
    ), $args));
    $className = get_parent_class($class);
    $eventManager = $this->eventManager;

    $event = $this->getHookEvent($className, $method, 'before');
    $eventManager->notify($event, $args);

    $event = $this->getHookEvent($className, $method, 'replace');
    if ($eventManager->hasListeners($event)) {
        $eventManager->notify($event, $args);
    } else {
        $args->setReturn($args->getSubject()->executeParent(
            $method,
            $args->getArgs()
        ));
    }

    $event = $this->getHookEvent($className, $method, 'after');
    return $eventManager->filter($event, $args->getReturn(), $args);
}
```

First of all the `\Enlight_Hook_HookArgs` object is created. That object will be passed to any event listener, so you
might already have used it.

Then the *before* hooks are processed:

```php
$event = $this->getHookEvent($className, $method, 'before');
$eventManager->notify($event, $args);
```

The method `getHookEvent` will just generate the event name from the context objects, in our case it could be `sArticles::sGetArticleById::before`.
Then a usual `notify` event is emitted, allowing you to modify the method arguments of the `sGetArticleById` call. 
 
In the next step the *replace* hook is processed:
 
```php
if ($eventManager->hasListeners($event)) {
    $eventManager->notify($event, $args);
} else {
    $args->setReturn($args->getSubject()->executeParent(
        $method,
        $args->getArgs()
    ));
}
```
If a listener is registered, it will be called using the `notify` event again. In your replace listener you can do your 
own calculations and set the return value using `\Enlight_Hook_HookArgs::setReturn`. If no listener is registered, the 
original `sGetArticleById` method is called using the `executeParent` method of the proxy object.

Finally the *after* hooks are processed and the result is returned (`$args->getReturn()`).

```
return $eventManager->filter($event, $args->getReturn(), $args);
```

## Structural overview
The following diagram shows the rough call stack of hooked calls.
![Structural overview of hooks](/blog/img/hook-overview.png)

## Best practice?
Hooks are a double-edged sword in some regards. They are stable, powerful and add extensibility to a wide range of objects
without the need to take care of the extensibility in any single object. 
There are downsides, however. As hooks allow you to directly bind to public and also protected methods, hookable classes are hard
to maintain and hook callbacks might rely on implementation details that might change. As there are no interfaces,
it might be hard to detect those changes.
Furthermore, replace hooks do not allow multi-inheritance. If plugin A and plugin B use a replace hook and do a `executeParent` 
at some point, the main class will be called always - so no nesting is possible, as it is using the 
[decorator pattern](http://en.wikipedia.org/wiki/Decorator_pattern). In addition to that, replace hooks tend to
lead developers to duplicate core logic into their plugin. When newer Shopware versions update the original method 
(e.g. to fix a bug), replace hooks might still enforce the old behaviour.

For these reasons, events should always be preferred over hooks, especially the replace hook should be avoided when possible.
Also that's the reason why only core classes and repositories are hookable in Shopware. For other object, especially
when loaded from the DI container, decorators might be a suitable replacement.
