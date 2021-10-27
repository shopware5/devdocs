---
title: Large Scale Plugin Architecture
tags:
    - Software Architecture
    - SOLID Principles
    - Layered Architecture
    - Domain Driven Design
github_link: blog/_posts/2016-12-05-large-scale-plugin-architecture.md
authors: [jp]
---

Last time I published a post where I was talking about a build system for a new project we were undertaking. After getting a basic project structure and development environment set up we were tasked to create a macro architecture for our software.

In this first part I want to show you how we derived a *macroscopic layer structure from technical requirements*. The domain structure, and even the service structure will be part of later posts.

### Requirements

Let me introduce you to the basic requirements we were tasked with:

> "In the following months, and possibly years you will have to create a large scale extension to shopware that exposes a extensible Development Framework, a REST-API, and a end user frontend. Besides creating new entities, simple data containers and dynamic workflows you will furthermore be tasked with problems that are commonly considered *hard to implement in Shopware*. Although you can initially require a future Shopware release, you might have to support more then one version at a time after release. Oh, and foreign developers might bind to your interfaces, so please create something stable that can still be changed after release. We call it B2B Suite."

Woah!! That is a little much.... Maybe... we should start like I started, and break this down into it's various components.

What are the technical components that are required?

| Type                  | Targeted Actor                             | Code stability   |
| -------------         | -------------                              | -----            |
| REST-API              | System Integrator                          | very stable      |
| Developer-Framework   | Other developers                           | stable           |
| Web-Frontend          | End users                                  | open             |

And how complex might the tasks get?

| Use case                  | Targeted                                                                                   | Expectation      |
| -------------             | -------------                                                                              | -----            |
| Simple Entity management  | Is a simple use case with an easy implementation. The only caveats here is extensibility.  | CRUD             |
| Complex Workflows         | Unknown complex stuff, might not have common solutions.                                    | The real fun :)  |
| Complex due to Shopware   | Should be a simple use case, but is not easily implemented.                                | Ugly code        |

So now we have different required parts, learned a little bit about the actors using these parts. And we also gained some insight as to how complex these parts might get.

<img src="/blog/img/large-scale-plugin-architecture/reason-to-change.svg" />

So the plugin itself has ties to at least these entities. Each either preventing change from or proclaiming change to the plugins structure. We need to create an architecture that takes this into account. Separating good from bad change and necessary from unnecessary change. In order to accomplish this I want to show you a iterative approach to layer design.

### Iterative Architecture

We need to gain greater insight into the __The Plugin__ thingy. In this chapter I want to define the macro structure of the technical layers based on the components and actor classes defined above. The design goal is to split the responsibilities to change or be static in a meaningful way.

I am a big proponent of **Domain Driven Design** and even more of the ideas behind it. I truely believe that basically anything can be abstracted away apart from the core domain of a application. Using arrays or yaml as config? Who cares! Making changes through REST or CLI. Doesn't matter. Triggering a new order without a shipping address? Deny!

So I believe it is a quite natural starting point to define a domain core precisely handling our use cases / user stories / features / whatever. This core of course has to communicate with the outside world. But does not come with communications means itself. So lets start by drawing a domain core:

<img src="/blog/img/large-scale-plugin-architecture/plugin-layer-architecture-1.svg" />

The nice thing about this is that we could start right now constructing business use cases agnostic to I/O transport mechanisms. I usually tend to start working right now and create a few example cases, that are exclusively called through a automated test suite. The UI lives outside of the core so lets add it:

<img src="/blog/img/large-scale-plugin-architecture/plugin-layer-architecture-2.svg" />

Most use cases will have to interact with Shopware which is therefore at least on the other side of the core:

<img src="/blog/img/large-scale-plugin-architecture/plugin-layer-architecture-3.svg" />

Notice the direction the arrows point, the frontend and REST-API depend on the domain core. That means change for both frontends is inevitable when the core changes. Good! A Change in use case should require a change in I/O. But there is this arrow between Shopware and the domain core. That can not be good.... Obviously Shopware does not depend on our plugin, but does our plugin have to depend on Shopware?

----

#### Excurse: The **D** in SOLID: Dependency Inversion Principle

Polymorphism really is the key to get this right. Instead of depending on the whole and direct implementation of Shopware, we depend on an interface our domain owns that provides just the data access wrapped into our own business objects services.

Let's assume this is our class:

````php
class LoginPerformerService {
    public function performLogin(string $email, bool $overwriteExisting = false): My\Identity
    {
        if(!$overwriteExisting && Shopware()->Admin()->sCheckUser()) {
            throw new \DomainException('Would have to overwrite existing identity')
        }

        try {
            $identity = $this->identityRepository
                    ->fetchIdentityByEmail($email);
        } catch (My\NotFoundException()) {
             $identity = new My\GuestIdentity();
        }

        if(!$identity->isPersistent()) {
            return $identity;
        }

        Shopware()->Front()->Request()->setParam('password', $identity->getPassword());
        Shopware()->Front()->Request()->setParam('email', $identity->getEmail());

        Shopware()->Admin()->sLogin();

        Shopware()->Session()->offsetSet('my-identity', serialize($identity));

        return $identity;
    }
}
````

You see there is a mixed bag of responsibilities from Shopware and from our own code. You might even think this code is reasonably clean, and be correct with it, unless we try to evaluate which lines actually belong to us and which don't. Let me just replace everything that belongs to Shopware with `_XX_`.

````php
class LoginPerformerService {
    public function performLogin(string $email, bool $overwriteExisting = false): My\Identity
    {
        if(!$overwriteExisting && _XX_) {
            throw new \DomainException('Would have to overwrite existing identity')
        }

        try {
            $identity = $this->identityRepository
                    ->fetchIdentityByEmail($email);
        } catch (My\NotFoundException()) {
             $identity = new My\GuestIdentity();
        }

        if(!$identity->isPersistent()) {
            return $identity;
        }

        _XX_
        _XX_

        _XX_

        _XX_

        return $identity;
    }
}
````

Turns out five of our statements actually belong to Shopware, so now we can replace them with method calls.

````php
class LoginPerformerService {
    public function performLogin(string $email, bool $overwriteExisting = false): My\Identity
    {
        if(!$overwriteExisting && $this->shopLogin->isLoggedIn()) {
            throw new \DomainException('Would have to overwrite existing identity')
        }

        try {
            $identity = $this->identityRepository
                    ->fetchIdentityByEmail($email);
        } catch (My\NotFoundException()) {
             $identity = new My\GuestIdentity();
        }

        if(!$identity->isPersistent()) {
            return $identity;
        }

        $this->shopLogin->storeLoginOf($identity);

        return $identity;
    }
}
````

Create an interface

````php
interface ShopLogin {
    public function isLoggedIn(): bool;
    public function storeLoginOf(Identity $identity);
}
````

And implement the interface

````php
class ShopwareLogin {
   public function isLoggedIn()
   {
        return Shopware()->Admin()->sCheckUser();
   }

   public function storeLoginOf(Identity $identity)
   {
        Shopware()->Front()->Request()->setParam('password', $identity->getPassword());
        Shopware()->Front()->Request()->setParam('email', $identity->getEmail());

        Shopware()->Admin()->sLogin();

        Shopware()->Session()->offsetSet('my-identity', serialize($identity));
   }
}

````

The obvious trade off is now instead of one class you have to maintain and remember three different classes but there is also a key benefit to this:

**Reversed Ownership** From the perspective of the service there is no Shopware, just an interface to call. Your main Domain is secured and our plugin does no longer depend on Shopware, but basically any possible login implementation. This lesson in code also applies to software architecture as a whole. We can reverse the ownership of whole layers by adding one level of indirection.

----

We call it Bridge! A indirection layer between the domain core and Shopware :)

<img src="/blog/img/large-scale-plugin-architecture/plugin-layer-architecture-4.svg" />

On paper this may look like a good enough architecture. But Shopware is not just a collection of models and services, but also a really powerful frontend framework, http abstraction layer, template engine and so on. And we should harness this power. So we need to integrate the Frontend layers into our application. The most naive approach first:

<img src="/blog/img/large-scale-plugin-architecture/plugin-layer-architecture-5.svg" />

Like the initial domain core design the frontends now depend directly on Shopware itself. Although I called this approach naive, it might actually be the correct one for our use case. If the frontends actually only provide a controller and a view component and just use the domain core as the model this might be a good solution. Here we have to think really careful! Where do we want to start mixing HTTP with our application? Will this mix be Shopware + HTTP, or just HTTP and then Shopware?

Depending on the domain of our plugin several other solutions are possible. For example a bridge for both transport layers:

<img src="/blog/img/large-scale-plugin-architecture/plugin-layer-architecture-6.svg" />

Or even just one?

<img src="/blog/img/large-scale-plugin-architecture/plugin-layer-architecture-7.svg" />

Maybe... Lets's take a look how this works against our actors:


### Reason to change

So what actor can introduce change into which component?

| Use case       | Shopware | End User | Foreign Developer | System Integrator | Requirement Change |
| -------------  | ---------|----------|-------------------|-------------------|--------------------|
| REST-Frontend  | -        |-         |X                  |X                  |X                   |
| REST-Bridge    | X        |-         |X                  |-                  |X                   |
| Store Frontend | -        |X         |X                  |-                  |X                   |
| Front-Bridge   | X        |-         |X                  |-                  |X                   |
| Domain-Core    | -        |X         |X                  |-                  |X                   |
| Shop-Bridge    | X        |-         |X                  |-                  |X                   |

And to what components should these actor bind to?

| Use case       | Shopware | End User | Foreign Developer | System Integrator | Requirement Change |
| -------------  | ---------|----------|-------------------|-------------------|--------------------|
| REST-Frontend  | -        |-         |X                  |X                  |X                   |
| REST-Bridge    | X        |-         |-                  |-                  |X                   |
| Store Frontend | -        |X         |X                  |-                  |X                   |
| Front-Bridge   | X        |-         |-                  |-                  |X                   |
| Domain-Core    | -        |-         |X                  |-                  |X                   |
| Shop-Bridge    | X        |-         |-                  |-                  |X                   |

Congrats! We have an architecture that **channels change** to different layers and provides reasonably stable ready to use interfaces.

### Conclusion

You might have noticed that up until now we completely omitted any use cases for the plugin. I will return with these in the next installment of this post.

This approach moves Shopware behind the same curtain as every other service. It is as easy to import Shopware into the plugin as it is to import any other framework.

One could argue that singling out reasons to change is over engineering. And depending on the size of the plugin one could win an argument against me. But If you are planing on writing a sufficient amount of code, you should think about your dependencies, not just in terms of code but also of real world actors to create a structure sufficient for you.

The main concern one could therefore have is that an architecture like that encourages the *[Not invented here syndrome](https://en.wikipedia.org/wiki/Not_invented_here)*, which certainly can be the case. The bridges encourage cherry picking, and it suddenly becomes a conscious and meaningful decision if you want to reuse something provided by Shopware or are more comfortable with deploying your own solution. But this simply is how modern development works. And opening a topic for discussion should hardly be a problem.

I usually always recommend the idea over the implementation. But after developing in this structure for the better part of the past year I see a great deal of long time potential in this type of technical architecture.
