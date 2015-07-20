---
title: Cross cutting concerns
tags:
    - cross cutting concerns
    - command bus
    - events
    - observer pattern
    - AOP

categories:
- dev

authors: [dn]
---

In the last blog post I discussed [the shopware hook system](http://devdocs.shopware.com/blog/2015/06/09/understanding-the-shopware-hook-system/)
and also mentioned, that hooks are technically a way to address cross cutting concerns with an AOP approach.
In this blog entry I want to have a deeper look into cross cutting concerns and ways to address them in PHP.

# What is a "concern"?
Talking about `cross cutting concerns` raises the questions, what are `concerns` in the first place? In regards of computer
science, [wikipedia defines](https://en.wikipedia.org/wiki/Concern_(computer_science)) concerns as

> a particular set of information that has an effect on the code of a computer program

An information could be anything from knowledge of a certain calculation to a concrete functional requirement you need to
reflect in your code. Writing modular programs, you usually will try to split your code in independent parts which interact
with each other and hide the actual implementation to each other.
The general design principle behind this is the so called **[separation of concerns](https://en.wikipedia.org/wiki/Separation_of_concerns)**,
as it suggests to structure the code by concerns: Usually you will try to separate the price calculation from view related
things like "formatting the price". This way of organizing the code will usually to more understandable, reusable and
maintainable code, as you are able to change the details of e.g. a class without having to take care of other classes.

The well known acronym [SOLID](https://en.wikipedia.org/wiki/SOLID_(object-oriented_design)) covers this aspect in the
**single responsibility principle** which if often summarized to "there should onl be one reason to change the code of a class".
If there are multiple reasons to change your class (e.g. the price calculation changed, the logger syntax changed or the
security policy changed) your class is responsible for multiple concerns (calculation, logging, security) and violates
the **separation of concerns** approach as well as the **single responsibility principle**.

# Cross cutting concerns
So usually developers will try to identify the main concerns of their software and split it into separate classes or functions.
There are system-level and peripheral cases, however, where such a clear separation of concerns is not possible: Usually logging,
security aspects or internationalisation issues are examples for concerns that needs to be taken care of in wide ranges of the
application and that "cross cut" many other components. Those concerns are called "cross cutting concerns" in differentiation to "core concerns".

![Structural overview of hooks](/blog/img/cross-cutting.png)

As you can see in the image above, you might have several core concerns like "cart", "account" or "price calculation" that
are split into separate classes and namespaces. Writing object oriented code, there are usually a lot of patterns to
address this kind of issues and model an architecture, that separate those concerns in a proper way.
On the other hand, the cross-cutting concerns need to be taken care of in
all of the core concerns and are not that easy to implement properly. In many cases, this kind of concerns lead to
scattering (duplication) of code or tangling (tight coupling ) of the code.

Cross cutting concerns are not a bad thing per se - they are a need of any modern application. But many applications
fall short of implementing those concerns in a way, that principles as DRY (don't repeat yourself) or SRP (singe responsibility principle)
are archived. For that reason "cross cutting concerns" should be considered a generic term for a certain kind of
architectural needs of an application.

# Example

Imagine you have a shopping cart class with a purchase method:

```
class Cart
{
    public function purchase($itemId, $customerId)
    {
        $this->privileges->purchaseAllowed($itemId, $customerId);
        $this->log->debug("Purchasing $itemId");

        try {
            $this->connection->beginTransaction();

            $purchase = new Purchase($itemId, $customerId);
            $this->em->persist($purchase);
            $this->em->flush();

            $this->connection->commitTransaction();
        } catch(\Exception $e) {
            $this->connection->rollbackTransaction();
            $throw $e;
        }

        $this->log->debug("Purchased $itemId");
    }
}
```

What are the core and cross cutting concerns here?

The actual core concern is creating a new purchase to the database. Elements like privilege checking, logging and transaction
could be considered cross cutting concerns, as these elements will affect many other components of your application, too.
Building the application like this will scatter e.g. the log-concern all over your classes and massively bloat the implementation. 

# How to deal with cross cutting concerns

## Events
One common approach to address cross cutting concerns is event based programming. In this section we will discuss the observer
 and the PubSub pattern.

### Observer pattern
In case of the [observer pattern](https://en.wikipedia.org/wiki/Observer_pattern) our `Cart` class would maintain a list
of dependents and notify them about relevant events.

```
class Cart
{
    private $observers;
    
    public function purchase($itemId, $customerId)
    {
        $this->notify('cart.beforePurchase', [ 'itemId' => $itemId, 'customerId' => $customerId ]);    
    
        $purchase = new Purchase($itemId, $customerId);
        $this->em->persist($purchase);
        $this->em->flush();
        
        $this->notify('cart.afterPurchase', [ 'itemId' => $itemId, 'customerId' => $customerId ]);

    }
    
    public function addObserver(Observer $observer)
    {
        $this->observers[] = $observer;
    }
    
    private function notify($type, $context = [])
    {
        foreach ($this->observers as $observer) {
            $observer->notify($type, $context);
        }
    }
}
```

With this we can easily register our dependent services to `Cart` without actually being forced to deal with e.g. logging
there. A possible observer might look like this:

```
class Logger implements Observer
{
    public function __construct() { … }

    public function notify($type, $context)
    {
        switch ($type) {
            case 'cart.beforePurchase':
                $this->log->debug("Purchasing " . $context['itemId']);
                break;
            case 'cart.afterPurchase':
                $this->log->debug("Purchased " . $context['itemId']);
                break;
        }
    }
}
```

Such an observer can easily be subscribed using e.g. `$container->get('cart')->addObserver(new Logger());`. The same way
we could easily wrap the purchase in a transaction or do the permission check in the `cart.beforePurchase` notification.

As you can see, the `notify` method is quite generic and deals with a `$context` object one might discuss. If it is more
suitable for your case, you could easily create a `CartObserver` interface with two more specific handle methods like
`notifyBeforePurchase($itemId, $customerId)` and `notifyAfterPurchase($itemId, $customerId)`. PHP even comes with own
observer interface `SplObserver` and `SplSubject` - but those might not be suitable in any case and do pass a reference
of the object to the observers, which might be questionable or not sufficient.   

### PubSub
The [Publish-subscribe pattern](https://en.wikipedia.org/wiki/Publish%E2%80%93subscribe_pattern) is another event based
pattern that allows you to address cross cutting concerns. 

While the observer pattern forces the objects to care about their observers by themselves, PubSub introduces an own 
service to take care of this. This central instance will take care of subscriptions as well as of notifications.

```
class EventManager
{
    private $events = [];

    public function subscribe($name, $callback)
    {
        if (!isset($this->events[$name])) {
            $this->events[$name] = [];
        }
        
        $this->events[$name][] = $callback;
    }
    
    public function notify($name, Event $event)
    {
        if (!isset($this->events[$name])) {
            return false;
        }
        
        foreach ($this->events[$name] as $subscriber) {
            $subscriber($event);
        }
    }
}
```

Having an event dispatcher like this, our `Cart` class might look like this:

```
class Cart
{
    public function purchase($itemId, $customerId)
    {
        $this->event->notify('cart.beforePurchase', new BeforePurchaseEvent($itemId, $customerId));    
    
        $purchase = new Purchase($itemId, $customerId);
        $this->em->persist($purchase);
        $this->em->flush();
        
        $this->event->notify('cart.afterPurchase', new AfterPurchaseEvent($itemId, $customerId));
    }
}
```

`BeforePurchaseEvent` and `AfterPurchaseEvent` are just simple value containers which store some context and allow the
subscribers to access this context:

```
$container->get('event_manager')->subscribe('cart.beforePurchase', function(BeforePurchaseEvent $event) {
    // logging $event->getItemId;
});

$container->get('event_manager')->subscribe('cart.afterPurchase', function(AfterPurchaseEvent $event) {
    // logging $event->getItemId;
});
```

A very common PubSub implementation is the [symfony event dispatcher](https://github.com/symfony/EventDispatcher/blob/master/EventDispatcherInterface.php)
which also comes along with a handy [EventSubscriberInterface](https://github.com/symfony/EventDispatcher/blob/master/EventSubscriberInterface.php)
which makes subscribing events even easier and is also available in Shopware.

  
### Conclusion
Both approaches will allow you to remove the direct dependency to logging, transactions and ACL from the cart implementation
and address those issues in specific observers / subscribers. 

Both patterns tend to hide the actually executed code of the `Cart` class a bit, as the dependencies are registered during runtime
and cannot be statically inspected. For that reason, you cannot tell beforehands, if e.g. only the `Logger` service is subscribed
or also the ACL service. This kind of abstraction can make debugging harder.
 
Another common problem is the handling of context information: In the observer example a simple `Array` was used, which is convenient
but might lead to issues regarding typos and debuggability. This might easily be changed to event objects, as
the PubSub example shows: The classes `BeforePurchaseEvent` and `AfterPurchaseEvent` define a clean interface for a specific
event and can easily inspected for debugging reasons.
I already mentioned, that the PHP [SplObserver](http://php.net/manual/de/class.splobserver.php) interface for an observer pattern
defines, that a reference of the subject (the `Cart` class in our case) is passed. From my experience with the Shopware
event system, I would consider this a bad practice, as this will allow the observers to hardly bind to the subject, even though
this might not be necessary at all. Selectively passing relevant context information also will make sure, that the observer
 do not modify e.g. internal state of the passed class. 
 
This is related to a third issue I'd like to address: In many cases some kind of "backchannel" is wanted, so that a 
event listener can e.g. filter some context variables (`filter` event in Shopware) or return objects (`notifyUntil`).
This might be considered bad practice, too, as multiple event listener changing the same event object by reference will lead
to hard to debug errors and side effects in some cases. Speaking of [loose coupling](https://en.wikipedia.org/wiki/Loose_coupling)
as one of the main goals for our event system, by-reference changes of context objects and behavioural decisions depending
on the return value of an event, are not ideal but hard to avoid. 

## Command Bus
The command bus is based on the [command pattern](https://en.wikipedia.org/wiki/Command_pattern) extended by an additional service layer
and also allows you to address cross cutting concerns easily. The generally idea is, to not call services directly, but
have a "command bus" service, which "dispatched" your commands to the corresponding handler.

The `Cart` example above might look like this:

```
class PurchaseCommand
{
    private $itemId;
    private $customerId;

    public function __construct($itemId, $customerId)
    {
        $this->itemId = $itemId;
        $this->customerId = $customerId;
    }
    
    public getItemId()
    {
        return $this->itemId;
    }
    
    public getCustomerId()
    {
        return $this->customerId;
    }
}

class PurchaseHandler
{
    public function handle(PurchaseCommand $command)
    {
        $purchase = new Purchase($command->getItemId(), $command->getCustomerId());
        $this->em->persist($purchase);
        $this->em->flush();
    }
}

class CommandBus implements CommandBusInterface
{
    private $handlers;
    
    public function __construct($handlers) { … }
   
    public function handle($command)
    {
        $this->getHandlerForCommand($command)->handle($command);
    }
    
    private function getHandlerForCommand() { … }
}
```

A setup like this will allow you, to process a new purchase calling:

```
$container->get('command_bus')->handle(new PurchaseCommand(3, 15));
```

The command bus will then find the correct handler for the `PurchaseCommand` (in our case the `PurchaseHandler`) and make
it handle the command. Analog to the PubSub example, `PurchaseCommand` is a simple value object without any logic. 

### Handling cross cutting concerns
With the `CommandBus` being a central instance that handles all the commands in our application, we have a nice entry
point for extension. The `CommandBus` class can easily be decorated, as we will see in the following logger example:

```
class LoggerDecorator implements CommandBusInterface
{
    protected $logger;
    protected $decoratedBus;

    public function __construct(Logger $logger, CommandBusInterface $decoratedBus)
    {
        $this->logger = $logger;
        $this->decoratedBus = $decoratedBus;
    }
    
    public function handle($command)
    {
        if (!$command instanceof PurchaseCommand) {
            return $this->decoratedBus->handle($command);
        }
        
        $this->logger->debug("Purchasing " . $command->getItemId());
        $this->decoratedBus->handle($command);
        $this->logger->debug("Purchased " . $command->getItemId());
    }
}
```
In this case the command is explicitly checked against `PurchaseCommand`, without this check, we'd even get a very
general logger. 

### Conclusion
The `CommandBus` pattern takes another approach then the observer and PubSub pattern. The commands are first class citizens
of your application, which makes it easy, to tell core and cross cutting concerns apart. Instead of having a primary 
"core service" one the one hand which is tightly coupled to your application and observers / subscribers on the other hand
which communicate with some kind of messaging system, the messaging system (more precise: command bus) becomes a central
part of your application. By decorating this "central part", its easy to cover wide ranges of your service layers and 
take care of cross cutting concerns.

As the `CommandBus` addresses the service layer itself, its not a "drop in" solution like the observer / PubSub pattern is 
in many cases. Especially in legacy applications with a tangled service layer, refactoring the services into small command
handlers might not always be that easy to accomplish. When it comes to returning results, things even get a bit disputable:
Often it is argued, that Commands should not return anything. This, however, seems to refer to [CQS](https://en.wikipedia.org/wiki/Command%E2%80%93query_separation)
even though a CommandBus does not necessarily imply CQS. Anyway: Usually the [domain event pattern](http://martinfowler.com/eaaDev/DomainEvent.html) is the recommended
solution for this kind of problems, a small PHP example can be found in Benjamin Eberlei's blog post
[Decoupling applications with domain events](http://www.whitewashing.de/2012/08/25/decoupling_applications_with_domain_events.html). 



## AOP
As discussed in [the shopware hook system](http://devdocs.shopware.com/blog/2015/06/09/understanding-the-shopware-hook-system/),
AOP is another approach to handle cross cutting concerns. In difference to the pattern based approaches above, AOP
is a programming paradigm, that (usually) addresses this kind of issues on a language base. 
Due to the complexity of the topic, we will discuss AOP in a separate blog post and try to implement [Go AOP PHP](https://github.com/lisachenko/go-aop-php)
using the example of Shopware. 

# Further readings:

 * [Wrangle cross cutting concerns with event driven development](https://speakerdeck.com/cjsaylor/wrangle-cross-cutting-concerns-with-event-driven-development) - A quick overview with slides from Chris Saylor
 * [Command bus to awesome town](http://de.slideshare.net/rosstuck/command-bus-to-awesome-town) - Slides by Ross Tuck who also addresses cross cutting concerns
 * [Tactician command bus library](http://tactician.thephpleague.com/) - Command bus library by Ross Tuck
 * [Go AOP PHP](https://github.com/lisachenko/go-aop-php) - AOP implementation for PHP
 * [Discussing aspects of AOP](http://www.researchgate.net/publication/220425704_Discussing_aspects_of_AOP) - Overview of cross cutting concerns and the general benefits of AOP 
