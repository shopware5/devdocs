---
layout: default
title: Plugin testing
github_link: developers-guide/plugin-testing/index.md
indexed: true
group: Developer Guides
subgroup: Developing plugins
menu_title: Plugin testing
menu_order: 80
---

<div class="toc-list"></div>

## Unit tests
If you want to start writing a new plugin keep in mind that unit testing helps you to deliver higher quality plugins.
With help of the __[cli tools](https://github.com/shopwareLabs/sw-cli-tools)__ you can easily start with plugin skeleton which has all relevant snippets to start directly with testing.
One of the first things that is important for testing is a `phpunit.xml[.dist]`. It could look like this:

```xml
<?xml version="1.0" encoding="UTF-8"?>
<phpunit backupGlobals="false" backupStaticAttributes="false" bootstrap="./Tests/Functional/Bootstrap.php">
    <testsuite name="SwagExampleTest Test Suite">
        <directory>./Tests</directory>
    </testsuite>

    <filter>
        <whitelist processUncoveredFilesFromWhitelist="true">
            <exclude>
                <directory suffix=".php">./Tests</directory>
            </exclude>
        </whitelist>
    </filter>
</phpunit>
```

### Basics
The starting point for testing your own plugin is the __phpunit.xml[.dist]___ in your plugin root directory and the `Bootstrap.php` file in the `./Tests/Functional/` directory of your plugin.

With the `Bootstrap.php` you can setup your environment properly to prepare it for testing. You can __initialize the kernel__, register __event subscribers__ and __namespaces__ or
you could initialize the __shop context__ to make currencies available. To prepare your plugin for testing it is necessary to require the Shopware helper bootstrap.

```php
<?php

require __DIR__ . '/../../../../../tests/Functional/bootstrap.php';
```

<div class="alert alert-warning">
The path to this file depends on whether you are using the <b>legacy plugin system</b> or the <b>new 5.2 Plugin system</b>.
</div>

The helper bootstrap starts the testing kernel and makes several functions like `Shopware()` available for you. You can then use the __service container__.

### Writing tests

You can then place your test which could look like this:
```php
<?php

class CalculatorTest extends Shopware\Tests\Functional\Components\Plugin\TestCase
{
    protected static $ensureLoadedPlugins = [
        'MyPlugin' => [
            'some_config' => 'foo'
        ]
    ];

    public function testMyService()
    {
        $service = new MyService();
        $result = $service->add(1, 1);

        $this->assertEquals(2, $result);
    }
}
```
You can run this test from your plugin root directory by typing `phpunit` if you have installed phpunit globally.
Otherwise you could use the onboard phpunit version shopware comes with.

With help of the `$ensureLoadedPlugins` static you can assure that your plugin is installed and activated and you can even configure it.
It is not required to assure that and the less your test needs the __Shopware stack__, the better the test is written.

We have written a tiny example plugin just to show how you could start testing your work.
You can find a installable ZIP package of it <a href="{{ site.url }}/exampleplugins/SwagTestExample.zip">here</a>.
