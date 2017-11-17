---
title: On testing strategies
tags:
    - test pyramid
    - test diamond
    - integration test
    - functional test
    - unit test
    - stability
    - software quality
indexed: false
github_link: blog/_posts/2017-11-17-on-testing-strategies.md

authors: [jp]
---

In this post I want to introduce you to the kind of strategies we evaluated to secure the functionality of the Shopware B2B-Suite.

When we set of to write the B2B-Suite we as a team set a few technical goals we wanted to achieve. One of them was to embrace automatic testing at its fullest and harness the potential implied by it:

* Allow refactoring even a day before release
* Help instill trust into the stability of the application for our customers
* Create a good application architecture that feels about right for the complexity introduced

## The Application

First we take a look at a fairly standard application design. We need this to have a shared base for the comparision of the different testing strategies. This model omits the framework and data structures which are not essential for our evaluation.

We have a controller, that uses a domain service and two auxiliary services to handle requests. The services themselves have dependencies on other services or storage implementations.

<img src="/blog/img/advantages-of-integration-testing/standard-application.svg" />

So how should we test this? Let's discuss some strategies:

## Testing Strategy: Functional testing only

Many projects out there use acceptance tests as their only way of automated testing. So why is it a bad idea? One word: Performance! Functional tests use a real browser to check the application. Even high performance applications have response times of at least 50ms, excluding the JavaScript execution time you are quickly at 500ms or more - for a **single page**. So let's math with an average of 250ms per page:

| Page Loads  |  Time   |
|-------------|---------|
| 1           | 250 ms  |
| 5           |   1 min |
| 10          |   2 min |
| 50          |  10 min |
| 100         |  20 min |
| 400         |   1 hr  |

Now you have to ask yourself: Do you want to maintain this? Is it useful? Creating an application that has 400 different click paths is quite easy, but waiting an hour to validate every single change is tedious at least and at worst it stops being helpful quickly.

The main advantage is that - at least for settled applications - introducing testing late in a development flow makes this a quick win because usually you have to write the least amount of code for this. And even if the Structure of your source code does not allow to easily introduce testing into every layer, these tests at least tend to solidify the basic functionality. So this is possible by just introducing a single layer on top of your application:

<img src="/blog/img/advantages-of-integration-testing/application-functional-testing.svg" />

**Good:**

* Gives you 99% certainty that the application works correctly.
* Adds minimal code to maintain.

**Bad:**

* Takes a long time even for small applications
* Does not help developers
* Does nothing to your application architecture, but add a few css classes
* Usually very fragile, small changes tend to have an unreasonably big impact (you change a buttons position and the login breaks)

**Verdict:** Run!

## Testing Strategy: The Pyramid

There is a testing pyramid favored by people like Robert C. Martin, it looks like this:

<img src="/blog/img/advantages-of-integration-testing/unit-test-pyramid.svg" />

"A Testing Pyramid that puts the focus on **unit tests** proposes to write a few integration tests and if really necessary few functional test. So for our example application this looks like this:

<img src="/blog/img/advantages-of-integration-testing/standard-application-with-pyramid.svg" />

So what do we have here? We have a Unit Test for each class of the application. Each dependency is mocked so we can test the code of a single service in absolute isolation. This is the core of your test suite: The unit tests! Now we need to integrate in a few places, I personally would propose that `MyService` and `ContextProvider` should be integrated with their subsidiary services, so we add this:

<img src="/blog/img/advantages-of-integration-testing/standard-application-with-pyramid-integration.svg" />

These two integration tests that integrate the central services with their storage. Last but not least we have a small functional test for our controller.

<img src="/blog/img/advantages-of-integration-testing/standard-application-with-pyramid-functional.svg" />

Pyramid successfully implemented!

But this did not work for us. Since we use minimal Integration tests and mock all outputs of the dependencies we quickly found ourselves in a place where the test suite did not produce certain edge cases correctly. Mocks are simply a lie in your system. Let's take a closer look at `MyServiceTest`:

<img src="/blog/img/advantages-of-integration-testing/mocking-error.svg" />

The Problem is the dependency between the mocks and the actual implementations. Although one can connect them through a *realization* arrow, mocks treat real implementations like interfaces. So if the signature does not change, but the result itself changes over time a mock will not catch this error. And changing the result over time is quite easy:

At first a method might look like this:

```php
public function isValidResponseCode(int $code): bool
{
    return in_array($code, [200, 300]);
}
```

And then gets changed to this:

```php
public function isValidResponseCode(int $code): bool
{
    return in_array($code, [200, 300, 202, 303]);
}
```

Boom, a mock will never automatically produce this result, but for all means it will keep the test suite green. There are certain other problems with mocks, so... Time for an excurse:

----

## Excurse: Mocking

All I really can say about mocking is this: it is a pain in the ass! There are currently a few contenders for mocking in PHPUnit tests and they all work internally the same way and are kind of awful. So lets say we have a class called `Something` that has a method called `getResponse` and we want it to return `'foo'` for our test, how would we accomplish this through mocking?

```php
class Something {
    public function getResponse(): string {
        return 'bar';
    }
}
```

#### PHPUnit Mocks

The default for PHPUnit Mocks is to use the built in Mocking Framework. This usually looks like this:

```php
class SomeTest extends PHPUnit_Framework_TestCase {

    public function test_something()
    {
        // create a mock
        $stub = $this->createMock(Something::class);

        // Configure the mock
        $stub->method('getResponse')
             ->willReturn('foo');

        $this->assertEquals('foo', $stub->getResponse());
    }
}
```

Cool? No! Actually there are a few problems with that:

* If you refactor the `getResponse` method no IDE will match this with this string automatically.
* When you write this you have to remember what the `getResponse` Method was called. The IDE will not help you here.
* `'foo'` is a totally made up value. As you can see the real implementation is not able to create this value, so why is there a test that checks against impossible values?

#### Prophecy

Prophecy is part of the newer generation of mocking frameworks. It has a two step approach, where you first configure the mock and then create the result. So how would this look like?

```php
class SomeTest extends PHPUnit_Framework_TestCase {

    public function test_something()
    {
        // create a prophet
        $prophet = $this->prophesize(Something::class);

        // Configure the prophet
        $prophet->getResponse()
             ->willReturn('foo');

        // create the mock
        $stub = $prophet->reveal();

        $this->assertEquals('foo', $stub->getResponse());
    }
}
```

Cool? A little bit cooler at least. It is possible to annotate the real class for prophets, at least. But other then that it has the same issues as `PHPUnit Mocks`.

#### Just use PHP

Of course we can create mocks with the built in features of the language itself. Just like this:

```php
class SomeTest extends PHPUnit_Framework_TestCase {

    public function test_something()
    {
        // create a prophet
        $stub = new class extends Something {
            public function getResponse(): string {
                return 'foo';
            }
        };

        $this->assertEquals('foo', $stub->getResponse());
    }
}
```

Although this is by far my favorite approach because the IDE can help you best with this approach, it still is messy.

**It is impossible to get `foo` as a return value from the tested method** This means that tests with Mocks tend to use made up values that can diverge quite heavily from real data.

----

So how did the pyramid fair for us?

**Good:**

* Helps during development, creates entry points for every possible error
* Always fast to execute
* Creates extremely well designed classes

**Bad:**

* Almost doubles the amount of code in your project
* Mocks need to be changed in sync with their loosely coupled subjects
* Produces false positives
* Creates sometimes awkward to integrate classes

**Verdict:** Can be better...

## Testing Strategy: The Diamond

There is a third strategy, that puts all the effort into Integration tests. This looks something like that:

<img src="/blog/img/advantages-of-integration-testing/test-diamond.svg" />

I have color coded the application diagram to show you how we interpreted the diamond:

* **Green:** No dependencies, a test becomes automatically a **unit test**
* **Yellow:** Has dependencies (either on other classes, or on infrastructure) must be an **integration test**
* **Blue:** Is front facing so it must be a **functional test**

<img src="/blog/img/advantages-of-integration-testing/application-diamond-testing.svg" />

By applying this simple ruleset we still have a one to one parity of test classes to production classes. This means that for each bug or feature there is an easy entry point in our test suite. And the test suite is therefore useful for developers and gets actively supported. If bugs are introduced during development there is usually a test that shows it immediately. If a contract between objects is changed this change is immediately available in the suite and in many cases even leads to a failing test. (depending on the test quality: see [Mutation Testing by my colleague Thomas Eiling](https://developers.shopware.com/blog/2017/08/24/mutation-testing/)).

So now lets take a look at our `MyServiceTest` the way it looks like in integration testing:

<img src="/blog/img/advantages-of-integration-testing/my-service-integration-test.svg" />

This test still covers all n-paths of the class `MyService`! It covers most paths in it's directly dependant classes `MyRepository` and `ValidationService` and covers a few paths in their dependencies, and so on. You can imagine this like different saturations of a color:

<img src="/blog/img/advantages-of-integration-testing/my-service-integration-test-with-saturation.svg" />

Now we no longer make up the output of any subsidiary service. We even noticed that using the real storage has virtually no impact on the test performance, but creates a huge benefit by testing the friction between these different services in our test suite at all times [(see factor X)](https://12factor.net/dev-prod-parity).

The big picture then looks like this:

<img src="/blog/img/advantages-of-integration-testing/standard-application-with-diamond.svg" />

The mocks in this case come from a specially set up database and this is the only real downside to this approach. Your fixtures may diverge from a production setting! We have seen this in the past and usually got real bugs from this. But even in these cases the problem arose from a single point of failure which is far better then mocking in every single place.

**Good:**

* Helps during development, creates entry points for every possible error
* Almost as fast as unit tests
* The real data is used
* Creates an architecture of well integrated parts

**Bad:**

* Almost doubles the amount of code in your project
* Fixture data will never show 100% production state
* Will also not give you a 100% certainty that everything works

**Verdict:** We use it!

## A word on TDD

I personally am a huge fan of TDD and practice it almost always when developing or exploring software. This practice is not lost here. Just because you have to implement stuff that you are using does not reduce the amount of freedom you have in the 30 second cycle of test driven development. On the contrary - at least for me - it tends to create more realistic assumptions on the scope of classes. By doing almost only integration testing all my tests look and feel the same. The usual friction that occurs when combining multiple unit tested classes is by far reduced because the integration assumptions all - by definition - hold up against the real implementations.

## Conclusion

The Diamond seems to be the best solution to the testing strategy problem. In fact we have been and still are developing the B2B-Suite with that strategy in mind and have seen quite good results with it. Currently our complete suite takes around 3 minutes to execute which is still passable for a quick overview. None of our tests depend on its predecessor and can all be executed in isolation (which makes Mutation testing possible in the first place), so during development we usually single out a few tests to run repeatedly.

The issue of software architecture comes a little short in this blog post, let me tell you why: It is a problem where tests only are a part of the solution! As you could read in a [previous post I published](https://developers.shopware.com/blog/2016/12/05/large-scale-plugin-architecture/) where I used the actor model to create a plugin architecture there are numerous ways that need to be taken into account when creating a sustainable system architecture. Testing will help you a lot with *friction* and *cohesion* (as introduced by Robert C. Martin) but not help you derive a greater truth from this. This is a place where [DDD](https://en.wikipedia.org/wiki/Domain-driven_design) seems to provide the better tools.

So all in all, at least to us this is the best overall approach to develop stable software, with the least tradeoffs and the most benefits. Thanks for reading and as always feel free to get in touch and discuss with me!
