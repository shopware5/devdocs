---
title: Interfaces in JavaScript
tags: [javascript]

categories:
- dev

authors: [psc]
indexed: true
github_link: blog/_posts/2015-11-04-javascript-interfaces.md
---

JavaScript has become one of the most popular programming languages in the web. Needless to say, it made a long way to get to this point. At the beginning of the internet, web pages had a very simple structure and an additional scripting language beside the traditional markup code was not really necessary. But times were changing and so did the web. Pages had become much more complex over time and you would rather talk about applications than pages. So now it was the time for JavaScript to step out the shadows and become the scripting language we needed ... really? Without starting a war of discussions about scripting languages we have to reconsider that JavaScript is a very poor language. Building larger applications you will have to face the lack of object oriented programming concepts, which you may already know from other languages like Java or C++.

Dealing with complex web applications you want to use some kind of abstraction layer for a better extendability and maintainable code. So as a developer, who follows the standard design patterns of <a href="https://en.wikipedia.org/wiki/Object-oriented_programming" target="_blank">OOP</a>, you would basically look for an interface you can implement. Sad to say that JavaScript hasn't built in support for traditional abstraction. The inheritance in JavaScript is based on objects and not classes, so there is no way to tell a class that it has to implement a set of given methods. Instead JavaScript uses something that is called <a href="https://en.wikipedia.org/wiki/Duck_typing" target="_blank">duck typing</a>.

> When I see a bird that walks like a duck, swims like a duck and quacks like a duck, I call that bird a duck.

<img src="/blog/img/dog_quack.jpg" style="width: 190px;" class="is-float-right" />

It means that an object with the methods `walk()`, `swim()` and `quack()` can always be treated as a `duck`. In JavaScript objects are defined by the methods they implement and not by an explicit type. When you have a second object of type `dog`, which also implements the `walk()` method, it could be treated like a `duck` as long as none of the other methods get called. So you never can be sure if a given object has implemented the necessary method until you proof it.

But JavaScript is also a very flexible language and of course there are different ways to implement some kind of interface functionality. You will find many articles around the web about this topic. The problem is, without native support, you always have to manually ensure that a class implements the interface you're providing. So you can mitigate the problem, but there is no realistic way to force a third party developer to use your interface as intended. It is all about providing good documentation to your code.

In Shopware we're using <a href="https://www.sencha.com/products/extjs/" target="_blank">ExtJS</a> for the administration panel of the shop system, which is a really complex JavaScript framework. Of course it has features for inheritance like creating and extending classes. But sometimes we're really missing the functionality of an interface, especially when encouraging third party developers to build new modules on top of the existing platform. In the new <a href="https://en.shopware.com/shopware-5-series-digital-publishing/" target="_blank">Digital Publishing</a> module for example, you are able to create custom elements which can be used inside the module. To provide an easy way for creating these elements, we built a kind of abstract class, which defines the basic set of methods and properties the element should use. Developers can extend this abstract class to create new elements right out of the box, without taking care about data handling and all that stuff. But also this is no real interface, so we have to encourage all developers to work through the <a href="https://developers.shopware.com/developers-guide/digital-publishing-elements/" target="_blank">documentation</a> and use the code as intended.

What are your experiences with such a kind of abstraction in JavaScript? Write me on <a href="https://twitter.com/PhilSchuch" target="_blanK">twitter</a>.