---
title: Mutation Testing
tags:
- phpunit
- humbug
- testing

categories:
- dev

authors: [tn]
github_link: blog/_posts/2017-08-24-mutation-testing.md

---

<div class="alert alert-info">
Anyone who is just searching for the example source code and does not want to read the complete blog post, <a href="https://github.com/teiling88/mutation-testing">here it is.</a>
</div>

In this blog post I want to present the concept of mutation testing and a simple example with [humbug](https://github.com/humbug/humbug) for you. For a few months we have reached the 100 % code coverage goal in our actual project the [b2b-suite](https://docs.enterprise.shopware.com/b2b-suite/). But what does this number stand for? Yes it ***only*** says you created enough unit-tests to execute every single line of code in your application. Nothing more. In the following sections I will create an simple class which is completely covered with unit tests. After that we will improve the tests and show how mutation testing can support us there. 

## Create an example class and unit tests

Let us have a look on this simple comparison class as example:
```php
<?php declare(strict_types=1);

class Comparison
{
    public function isGreaterThan(int $x, int $y): bool
    {
        return $x > $y;
    }

    public function isSmallerThan(int $x, int $y): bool
    {
        return $x < $y;
    }
}
```

To create an unit-test to reach 100 % coverage is very simple. See the example below: 

```php
<?php declare(strict_types=1);

class ComparisonTest extends PHPUnit\Framework\TestCase
{
    private $comparison;

    public function setUp()
    {
        $this->comparison = new Comparison();
    }

    public function test_isGreaterThan()
    {
        self::assertTrue($this->comparison->isGreaterThan(5, 3));
    }

    public function test_isSmallerThan()
    {
        self::assertTrue($this->comparison->isSmallerThan(3, 5));
    }
}
```     
The execution of this unit tests creates the following output:
```
OK (2 tests, 2 assertions)


Code Coverage Report:   
  2017-08-22 12:42:16   
                        
 Summary:               
  Classes: 100.00% (1/1)
  Methods: 100.00% (2/2)
  Lines:   100.00% (2/2)

Comparison
  Methods: 100.00% (2/2)   Lines: 100.00% (2/2)

```

So we created a class with two methods which are covered by tests. We could think this class is bullet proof and every invalid change will be discovered from our test. But really? What if some developer adds a small equal sign to our methods? Our new Comparison class looks like this:

```php
<?php declare(strict_types=1);

class Comparison
{
    public function isGreaterThan(int $x, int $y): bool
    {
        return $x >= $y;
    }

    public function isSmallerThan(int $x, int $y): bool
    {
        return $x <= $y;
    }
}
```

And the result of our unit test is the same like above. Every test passed. But now false positive results are possible . If we use the method `isGreaterThan` with the parameters `$x = 5; $y = 5;` we get true as return value instead of the supposed false value.

So what happened? At the moment we only test the happy execution path of this methods and didn't observe of the threshold values. For every developer it is obviously that 5 is greater than 3 and 3 is smaller than 5. So we create this kind of test. But how do we have to improve our test to cover oll existing threshold values?

First, we should test the nearest combination of parameters which causes an false return value. In our example methods we can easily use the equal number for `$x` and `$y`. After that we should test the farthest combination which causes an true return value. For this we can easily use the PHP constants `PHP_INT_MAX` and `PHP_INT_MIN`. The new created test can you see below:

```php
<?php declare(strict_types=1);

class ComparisonTest extends PHPUnit\Framework\TestCase
{
    // ...

    public function test_isGreaterThan()
    {
        self::assertTrue($this->comparison->isGreaterThan(5, 3));
        self::assertFalse($this->comparison->isGreaterThan(4, 4));
        self::assertTrue($this->comparison->isGreaterThan(PHP_INT_MAX, PHP_INT_MIN));
    }

    public function test_isSmallerThan()
    {
        self::assertTrue($this->comparison->isSmallerThan(3, 5));
        self::assertFalse($this->comparison->isSmallerThan(4, 4));
        self::assertTrue($this->comparison->isSmallerThan(PHP_INT_MIN, PHP_INT_MAX));
    }
}
```

## Mutation Testing

In this small example it is very easy to find the needed test range and threshold values. But how works this in bigger Applications with hundred of classes and thousand lines of code? I guess in the most cases only the happy path will be tested. So how can mutation testing help us to create better tests?

The basic concept of mutation testing sounds very easy. You change comparison statements as an example from `===` to `!==` or changes return values of methods like `return true;` to `return false;`. This new versions of your application are called "mutants". After your change you execute the test suite. If the suite fails your tests "killed the mutant". This means your tests detects the wrong behaviour.  

Mutation testing introduces a new quality score the so-called "Mutation Score Indicator". This score is the ratio of the number of Dead Mutants over all created Mutants. Usually this score is calculated like the code coverage in percent.

In order to make this kind of testing automatically we can use [humbug](https://github.com/humbug/humbug) for that. Humbug has a wide range of mutators like the described mutations above. A good overview can be found [here](https://github.com/humbug/humbug#mutators). 

So let us revert the new assertions and execute humbug for the first time. Humbug executes phpunit in the first place. After that it will create the mutants and executes the test suite again for every created mutant. To improve the execution time humbug only uses those test classes which cover the specific file and line on which the mutation was inserted.

Humbug creates the following output: 
```
Humbug has completed the initial test run successfully.
Tests: 2 Line Coverage: 100.00%

Humbug is analysing source files...

Mutation Testing is commencing on 1 files...
(.: killed, M: escaped, S: uncovered, E: fatal error, T: timed out)

M.M.

4 mutations were generated:
       2 mutants were killed
       0 mutants were not covered by tests
       2 covered mutants were not detected
       0 fatal errors were encountered
       0 time outs were encountered

Metrics:
    Mutation Score Indicator (MSI): 50%
    Mutation Code Coverage: 100%
    Covered Code MSI: 50%
```

As we can see, humbug created 4 mutations, 2 mutants were killed and 2 mutants was not detected. So let us have a look at the generated mutations which are not detected:

```php
    public function isGreaterThan(int $x, int $y): bool
    {
        return $x >= $y;
    }
    
    public function isSmallerThan(int $x, int $y): bool
    {
        return $x <= $y;
    }    
```

Humbug detects automatically the same issues which we found above manually. If we add the new assertions which we already created above we should reach an Mutation Score Indicator of 100%. The created output stands below:
```
Humbug has completed the initial test run successfully.
Tests: 2 Line Coverage: 100.00%

Humbug is analysing source files...

Mutation Testing is commencing on 1 files...
(.: killed, M: escaped, S: uncovered, E: fatal error, T: timed out)

....

4 mutations were generated:
       4 mutants were killed
       0 mutants were not covered by tests
       0 covered mutants were not detected
       0 fatal errors were encountered
       0 time outs were encountered

Metrics:
    Mutation Score Indicator (MSI): 100%
    Mutation Code Coverage: 100%
    Covered Code MSI: 100%
```

## Conclusion
Mutation Testing especially humbug is a powerful tool to rate the quality of your unit tests. It checks the hole test suite and give you the safety that your created tests are useful. Our b2b-suite has at the moment a Mutation Score Indicator of 79%. So I think there is some place left for improvements ;-). 

If you are interested in the source code, it can be found [here](https://github.com/teiling88/mutation-testing). 
