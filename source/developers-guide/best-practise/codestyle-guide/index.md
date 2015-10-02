---
layout: default
title: Shopware Codestyle Guide
github_link: developers-guide/codestyle-guide/index.md
tags:
  - api
indexed: true
---

Our coding standard is based on the standards of Typo3 and Zend-Framework. A quick summary of the guide can be downloaded [here](http://community.shopware.com/files/downloads/swagcodingstandard-661541.pdf).

## Code Format und Layout

Coding standards are an crucial factor for code quality. A standardized and visual styling, naming convetions and other specifications are generating homogeneously code which is easy to read and maintain.

### General rules

* One class per file
* File must start with the full PHP tag `<?php` and should not be closed at the end
* Lines of code may be longer than 80 characters
* Line breaks should only be used for a better readability

### When to use Tabs and Spaces

* Indention should only be done with tabs

```php
public function getContextName() 
{
  » return $this->contextName;
}
```

### Naming

The naming of classes and methods are underrated in modern software development. The naming should be fluid and may not contain any abbreviations. Every designation should be english and meaningful. If you are using abbreviations, you should follow the CamelCase standard.

**Example:** `createHtmlContent()`

### Package Names

Every package name should start uppercase und should be continued in UpperCamelCase. You should only use letter alphanumeric characters to prevent problems with different file systems. (Don't use symbols!)

### Namespaces, Interfaces and Classnames

* The following characters are allowed: [A-z],[0-9]
* Namespaces are written in UpperCamelCase except for commonly used names and designations.

### Method names

Every method name is written ins lowerCamelCase. You should only use alphanumeric characters. Method names should be short but meaningful and precise. Constructors must be named `__construct()` since the support for methods named like the class is dropped in PHP7.

**Examples for good method names:**

* `myMethod()`
* `someNiceMethodName()`
* `betterWriteLongMethodNamesThanNamesNobodyUnderstands()`
* `singYmcaLoudly()`
* `__construct()`
   
### Variable names

Variables are written in lowerCamelCase and should be:

* self-explaining
* may be longer if the meaning gets clearer

**Examples for *good* variable names:**

* `$singletonObjectsRegistry`
* `$argumentsArray`
* `$aLotOfHTMLCode`
    
**Examples for *bad* variable names:**

* `$sObjRgstry`
* `$argArr`
* `$cx`
* `$x`,`$y`

_Exceptions here are counter variables like `$i`, `$j`, `$k`... but still try to not use them at all._

### Constants

Constants are always written in uppercase. To group them into different topics, you can use underscores like seen below:

* `STUFF_LEVEL`
* `COOLNESS_FACTOR`
* `PATTERN_MATCH_EMAILADDRESS`
* `PATTERN_MATCH_VALIDHTMLTAGS`
    
**Tip:** It is always good to place regular expressions in constants.

### File names

* Every file name is written in UpperCamelCase
* File names should be equal to their containing class or interface
* Every file must contain only one class or interface
* Unit test files are named like the class they test including a suffix `Test.php`
* Files should be placed in folders based on their namespace

### Strings

Literals:

```php
$vision = 'enter vision here';
```

Concatenate Strings

```php
$message = 'Hi' . $name . ', you look ' . $look . ' today';
```

### if Statements

```php
if ($something || $somethingElse) {
   doThis();
} else {
   doSomethingElse();
}

// one liners - exception for throw statements!
if (allGoesWrong() === true) throw new \Exception('Hey, all went wrong!', 123);

if (weHaveALotOfCriteria() === true
   && notEverythingFitsIntoOneLine() === true
   || youJustTendToLikeIt() === true) {
      doThis();

} else {
   ...
}
```

### switch statement

```php
switch ($something) {
   case FOO:
      $this->handleFoo();
	  break;
   case BAR:
      $this->handleBar();
   	  break;
   default:
      $this->handleDefault();
}
```

## Development workflow

### Test-Driven Development

* Before writing the bugfix or feature, you have to create an unit test.
* Before commit, every unit test should pass.

### Commit messages

For the sake of a good history, you need to write good commit comments. **There should not be any commit without comment!**

The first line should include:

* The ticket prefix and number
* Summary of your changes

**Example:** `SW-12345 - Fixed the login modal width`

## Source Code Dokumentation

**Class**
```php
 /**
 * First sentence is a short description. Then you can write more, just as you like
 * Here may follow some detailed description about what the class is doing.
 *
 * Extends the Basic Enlight_Class
 *
 * @author John Doe
 * @author $Author$
 * @package Shopware
 * @subpackage Controllers_Frontend
 * @copyright Copyright (c) 2011, shopware AG
 * @since 3.5.0 - 2010/12/06
 * @version 4.0.0-SVN$Id$
 */
class Shopware_Controllers_Frontend_Account extends Enlight_Controller_Action implements Enlight_Controller_Hook
{
 ...
}
```

**Interface**
```php
/**
 * First sentence is short description. Then you can write more, just as you like
 *
 * Here may follow some detailed description about what the interface is for.
 *
 * Paragraphs are seperated by a empty line.
 *
 * [annotations see above]
 */
/**
 * First sentence is a short description. Then you can write more, just as you like
 * Here may follow some detailed description about what the class is doing.
 * [annotations see above]
 */
interface SomeInterface 
{
 ...
}
```


**Exception**

```php
/**
 * First sentence is short description. Then you can write more, just as you like
 *
 * Here may follow some detailed description about what the exception is for.
 *
 * Paragraphs are seperated by a empty line.
 * [annotations see above]
 */
class SomeException extends Exception 
{
 ...
}
```

**Method**

```php
/**
 * A description for this method
 *
 * Paragraphs are seperated by a empty line.
 *
 * @author John Doe
 * @author $Author$
 * @copyright Copyright (c) 2011, shopware AG
 * @param \Shopware\Controller\Post $post A post
 * @param string $someString This parameter should contain some string
 * @return void
 */
public function addStringToPost(\Shopware\Controller\Post $post, $someString) 
{
 ...
}
```

**Testcase**
```php
/**
 * @test
 */
public function fooReturnsBarForQuux() 
{
 ...
}
```

**Public API**

```php
/**
 * This method is part of the public API.
 *
 * @return void
 * @api
 */
public function fooBar() 
{
 ...
}
```

Liste der Dokumentations- Annotationen:

**Interface Documentation**

* @author
* @api
* @package 
* @subpackage 
* @copyright
* @since
* @deprecated
* @version

**Class Documentation**

* @author
* @api
* @package 
* @subpackage 
* @copyright
* @since
* @deprecated
* @version
    
**Property Documentation**

* @var
* @api
* @since
* @deprecated

**Constructor Documentation**

* @param
* @throws
* @author
* @api
* @since
* @deprecated
    
**Method Documentation**

* @param
* @return
* @throws
* @author
* @api
* @since
* @deprecated
    
**Testscase Documentation**
* @test
* @author


## Examples of good and evil operations

```php
if ($template)             // BAD
if (isset($template))      // GOOD
if ($template !== NULL))   // GOOD
if ($template !== ''))     // GOOD

if (strlen($template) > 0) // BAD! strlen("-1") is greater than 0
if (is_string($template) && strlen($template) >0) // BETTER

if ($foo == $bar)          // BAD, avoid truthy comparisons
if ($foo != $bar)          // BAD, avoid falsy comparisons
if ($foo === $bar))        // GOOD
if ($foo !== $bar))        // GOOD
```

**Bad coding smell**
```php
// We only allow valid persons
if (is_object($p) && strlen($p->lastN) > 0 && $p->hidden === FALSE
    && $this->environment->moonPhase === MOON_LIB::CRESCENT) 
  {
   $xmM = $thd;
}
```


**Smells better**
```php
if ($this->isValidPerson($person) {
   $xmM = $thd;
}
```