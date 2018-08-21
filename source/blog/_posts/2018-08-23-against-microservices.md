---
title: Against Microservices
tags:
    - software architecture
    - modularized application
    - microkernel
    - Microservices
indexed: false
github_link: blog/_posts/2018-08-23-against-Microservices.md

authors: [jp]
---

Microservices... I have problems with that. And from many discussions at conferences I get the impression that I might not be the only one that's *not getting* it. So in the following article I want to attempt to deep dive into the promisses behind the Microservice architecture, lamend on solutions and show the costs behind it.

## Micro What?

Distributed systems are all the rage now and have been at least over the past decade. The Cloud innovation, especially in the PaaS and FaaS (= keep Mores Law alive by increasing the number of parallel processes) area, pushes software development to create finer grained executable stacks for more and more abstract machines. Technologies invented for [CORBA](https://en.wikipedia.org/wiki/Common_Object_Request_Broker_Architecture), [SOA](https://en.wikipedia.org/wiki/Service-oriented_architecture) or [REST](https://en.wikipedia.org/wiki/Representational_state_transfer) are joining forces in Microservices to rule the word.

Microservices are an architecture style. They are a possible solution to key demands for large scale development. Large Software, large Userbase, large development team, large data and so on. The central promisse is that they allow for **far greater scaling** then any other style of architecture by reducing the need for **global decissions**.

They usually get contrasted with the vast space of **monolithic** architecture styles => an application as a single interconnected entity of functionality.  

## So what is a Microservice?

If you look at the - always insightfull - [blog of Martin Fowler](https://martinfowler.com/articles/Microservices.html) he describes the style as 

> [...] a particular way of designing software applications as suites of **independently deployable services** [...]

How would this work? When planing a green field application one typically slices the project up front in **layers/components/milestones/building blocks**. If you plan to create Microservices you are now free to slice these building blocks into different applications. Communication is only allowed to go through an API and in many cases publish and subscribe to an event system. These Services do not share infratsructure. They are (potentially) deployed to different servers, write to their own storage and know only about necessary pears, that they themselves only accesss through a well defined API. The term *micro* comes from the idea that these services themseves should be *really small* or do *one thing only*.

A single services then has to be a fully working application, that does a miniscule part of the workload of the whole application. Well known and published examples of this architecture styles are [Netflix](https://medium.com/netflix-techblog) and Amazon, or eCommerce specific [Otto Group](https://dev.otto.de/tag/self-contained-systems/) or the [Spryker Plattform](https://academy.spryker.com/).

Of course the devil is in the details. But simply put Microservices reduce code coupling in favor of networking and reduce organization coordination by giving more freedom to the individual teams and developers. So let's investigate the two promises separately:

## Reduced Coupling

It should be common knowledge by now that coupling code is not only the one thing responsible for Software to actually do something but also the main cause of death for legacy systems. Systems need to be intertwined because one of the main benefits is that existing data and functionality can be rearanged into new and interesting functionality. A byproduct of this rearangement usually is friction, because the original system was not designed to behave in the newly implemented way. As a system gains new capabilities it internally starts to accumulate more and more friction. When handling this friction outweighs the time spent on the actual feature implementation you are in trouble.

This insight is not only well known but also a quite old. And of course multiple not mutually exclusive strategies are available to prevent this already: [DDD](https://en.wikipedia.org/wiki/Domain-driven_design), [Ports & Adapters](http://alistair.cockburn.us/Hexagonal+architecture), [SOLID](https://en.wikipedia.org/wiki/SOLID), [TDD](https://en.wikipedia.org/wiki/Test-driven_development), [Clean Code](https://medium.com/mindorks/how-to-write-clean-code-lessons-learnt-from-the-clean-code-robert-c-martin-9ffc7aef870c) and many other sources try to help you here. 

Microservices now add three main strategies for prevention. 

**Services share the least viable amount of information with each other.** If executed correctly this is a very good thing! Information hiding is by no means a new concept, and by no means a solved problem. Microservices try to do achieve this by making the process simply **more painful** then it was in the past. If you need an API that contains additional Information this needs additional planing and agreement between the developers. However the incentive still is to create a to broad interface if not checked otherwise.  

*Verdict: Adding pain is a interesting concept, but by no means a sufficient guarantee for good design.* 

**Make Services small so they can easily be replaced** Also a nice idea create a service in hours or days, if it doesn't add the expected value, just discard it. If a Service that is currently not owned needs changes the next developer can start at a green field if he so chooses, just the API needs to be the same. I would bet you have done this countless times in the past, but without calling it service. Reengeneering a class, a namespace, a feature. This is not new. Our programming languages actually have constructs for this. And at least my experience is that changing the internal structure without changing the external behaviour only works to a certain degree. Imagine moving from CRUD to CQRS+ES for a single building block. This either has consequences for external usage or just adds friction.

*Verdict: Really no help*

**Use unreliable communication technologies so that all coupling is taken with a grain of salt.** This one is interesting: Networking adds the problem of delays, retries, unavailabilities to something that a monolith would do and guarantee in process. Failure tolerant networking adds the ability to silence functionality by only removing a single service without having to change anything else about the system. But there is a tradeoff: There needs to be an enforced convention to secure all applications behave like this and you need documentation to know wich service needs to be killed for this. Exactly the thing you are now missing for your existing application, too. If you want to enforce workings like this I would make a case that it is actually easier to do it through static analysis in a monolyth.

*Verdict: Has a desirable effect on your architecture.*

Effectively Microservices reduce coupling mainly by making it harder to use common abstractions across a project. Since these are all different applications you need some kind of package management system to achieve code sharing. But more on that in the next chapter.

Ok, so where is the Microservice advantage exactly? Quite easily put: **Best practices get enforced through pain**. If you don't behave according to common wisdom they show bugs very early. But basically - at least I would argue - there are not that many benefits to a monolith from a software architecture standpoint here.  

## Freedom for Teams

Developers, developers, developers!!! Maybe Microservices show strengths here? As [Conway's Law](https://en.wikipedia.org/wiki/Conway%27s_law) states.

> "organizations which design systems ... are constrained to produce designs which are copies of the communication structures of these organizations." - M. Conway

While this abstract definition might be a little hard to grasp, there is a very good example that showcases the effect your organizational structure has on the software you produce:

>  "If you have four groups working on a compiler, you'll get a 4-pass compiler." - E. S. Raymond

... because communication is most intense inside the team and gets sparser and sparser the further away another team is organization wise. Microservices are the logical conclusion to this. There needs to be a formal way to negotiate API interfaces between teams and that's it. This actually solves a real problem in Software development: **Scale** If you ever started a green field project with a projection for more then two developers and a initial runtime of more then a year you should know how hard it is to get everyone working. Huge Silicon Valley corporations want to start projects with hundreds of developers or maintain flagship products with with even more people. Communication becomes an overhead that delays a product significantly think of it like an expression of the big upfront design antipattern. The more people you have that need to work the costlier it becomes  to negotiate a single decision.  

By giving the highest degree of freedom to your teams the structure of your software changes, too. Dependencies in such a huge product can no longer be top down tree like organized but are in fact more like a **bee hive**. Microservices can potentially be even more then that. The hive is multinational! Your services can be written in any language, come from any source and as long as network communication is possible you are fine. In extreme cases there can and will be C code communicating happily with a PHP script that notifies a Python app that provides data for Node app. Every one of these apps is self contained and uses a different set of tools for its build, deployment, integration and monitoring. Yeah!

Now imagine something is broken. How can you inspect, debug and fix any issue in such a system? You need conventions!

> "With great power comes great responsibility" - B. Parker

If you want to base each new service on different technology you will find that your Microservices have multiple different solutions to the same common problem. [*Not invented here*](https://en.wikipedia.org/wiki/Not_invented_here) is the buildin result from this approach to team organizations.

To keep with the bee hive metaphor: Even they grow over time from just a founding queen.

In the end the freedom simply does not come for free. Where does scale outweigh central technical decision management? Some huge companies that develop concurrently on a single product of course have good reasons to go into that direction. And in the wild you will find scaled back solutions (e.g. single programming language, central logging and monitoring, centrally enforced architecture, even single process) that try to get the pros without the cons. But what is left in these cases?


## Problems arise

I get hyped by talks on conferences. Either watching them directly live or in recording. But Microservices tend to tickle a nerve where I constantly ask myself "What aren't they talking about?" Microservices add pressure on sophisticated technological solutions where a monolith can get away with far less effort. Take logging for example. While a single process application may be inspectable through a file log initially, a distributed system can not.

Or how do you secure that the negaotiated API interfaces actually work? Integration tests of course! All neighboring services must be integrated with one another, through the network, which means lots of tests for lots of services with lots of communication. 

How will developers set up the application locally? Docker containers of course! Each Service runs in it's own container, comes usually at least one storage container. Somewhere is a routing configuration.

Scaling contrary to common belief is an issue, too. There may be many independent Services that need to be scaled appropriately to improve the overall Performance of the application. 

While all these issues are of course solvable, they will block development resources. They may delay the product significantly. They are the real **costs of Microservices**.   

## The PHP factor

So we are actually developing PHP here. PHP is single threaded, shared nothing often stateless function execution at it's heart. So when we talk about scaling we actually do not mean the application itself but the limits in the infrastructure. MySQL too slow? Use ElasticSearch! Webserver responds too slow? Put a load balancer in front of two! Horizontal Scaling is actually not that hard in PHP.

Oh and while we are at it. The *Deployment Monolith Antipattern* is solvable through package management too. Just look at the shopware plugin store. PHP is an interpreted language, source changes in production are actually not the hardest problem.   

## Benefits in cherry picking

Of course I do not want to discard the whole notion of Microservices. There is value, but there are also other options. From a technical standpoint I would argue that projects usually do not fail because the test suite was too sophisticated, the documentation too helpful, or the architecture was too clear and predictable.

And there are real world benefits to all of them! Good old static code analysis will help you greatly with dependency management and finally this can all be part of the global CI process.

And it even might be necessary to create different Applications for parts of your problem. Mayber the requirements for a single part of your application are so drastically different that this becomes necessary. Feel free to use concepts from Microservices. **Hybridization** of architecture is not necessarily a bad thing.  

Problem solved? Well there are still the organizational difficulties...

## Alternative: (Micro-)Kernel

Microservices and monoliths are on oposing sides of the static dependency spectrum. While a monolith is interconnected and interdependent a Microservice is *(almost)* not. Well this spectrum does not only consist of black and white. My favorite alternative is the kernel style. A Kernel represents a core domain of an application. In our case eCommerce. This kernel then provides an extension mechanism for others to extend, alter and replace these core concepts. A plugin system if you want.

Apart from the basic eCommerce workflows the Kernel provides the technical groundwork for all plugins to use. It contains a deployment machanism, package management and lays the technological groundwork for the necessarry infrastructure coupling. Most importantly it provides **the base quality of the product as a whole**.  

In a company driven by Conways law the natural limit for a kernel is the amount of features a single team can handle. A basic runtime that shares a technical and functional base for everyone to harness. One arranges orthogonal features in orthogonal teams and creates a core that is owned and used by everyone. The viability of this approach is proven by big and small companies alike, think Operating System vendors, Framework vendors and the like. There is however a critical situation when a kernel's size outgrows the team. A need for action arises. A split is necessary.

Conway's insight should mean that an organization in order to produce software needs to be fluid enough to support good design. So if an application changes the communication and therefore team structure needs to be changed in the best interest of the product.      

## Conclusion

From a technical standpoint there is no clear benefit in using Microservices as the main application design. They just have the potential to add risk and cost to a project. In my opinion this style just complicates stuff from the get go. Sizing is a really hard problem in software and I have seen many applications that where suprisingly large in relation to the work they actually performed. If you have a fairly simple problem to solve a single developer will be faster to fix it them twenty. Or as we say: 

> Not everybody is Netflix

If in the past you were not able to create a good application through concepts of OOP why would you be able to create such a thing adding layers of indirection on top of the actual problem. If the application design rots, there is no reason to expect adding networking to the communication will help. Microservices are by no means automatically cheaper in development or maintenance then a monolith. So when investing into new technology, maybe one should rather invest in solving the actuall problems of the past, directly.

That's it for my rant. Thanks for reading!





