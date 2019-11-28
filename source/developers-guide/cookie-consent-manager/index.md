---
layout: default
title: Register a cookie to the cookie consent manager
github_link: developers-guide/cookie-consent-manager/index.md
shopware_version: 5.6.3
indexed: true
tags:
  - cookie
  - consent
  - manager
group: Developer Guides
subgroup: General Resources
menu_title: Register a cookie to the cookie consent manager
menu_order: 300
---

Starting with Shopware 5.6.3, a cookie consent manager has been integrated.
This enables the shop user to configure in detail which cookies he wants to accept or decline by providing an easy to use overlay.

**But what happens now when a plugin introduces a new cookie in Shopware?** <br />
If you're using the "Technically necessary cookies only" mode, each cookie that is not known to Shopware 5.6.3,
will automatically be deleted with every server response.

The following guide will explain, how you can register your plugin's cookies successfully,
so Shopware knows about it and can deal with it properly.

## Registering your own cookie

The very first step is to make sure, that Shopware knows about your cookie and can deal with its presence.
This will show your cookie in the cookie consent manager automatically. If the user decides not to allow your cookie,
Shopware will also automatically try to delete your cookie with every response sent by the server.

Registering your cookie is done using PHP, and only PHP. We wanted to have a single point of truth,
but don't you worry, the necessary changes are very easy.
Also, if you need to do stuff in javascript, this guide also has you covered!

Let's start with an example, before we get into it in detail - but don't worry, it's very simple!

```php
<?php declare(strict_types=1);

namespace ComfortCookie;

use Shopware\Bundle\CookieBundle\CookieCollection;
use Shopware\Bundle\CookieBundle\Structs\CookieGroupStruct;
use Shopware\Bundle\CookieBundle\Structs\CookieStruct;
use Shopware\Components\Plugin;

class ComfortCookie extends Plugin
{
    public static function getSubscribedEvents(): array
    {
        return [
            'CookieCollector_Collect_Cookies' => 'addComfortCookie'
        ];
    }

    public function addComfortCookie(): CookieCollection
    {
        $collection = new CookieCollection();
        $collection->add(new CookieStruct(
            'comfort',
            '/^comfort$/',
            'Matches with only "comfort"',
            CookieGroupStruct::COMFORT
        ));

        return $collection;
    }
}
```

That's actually everything necessary to let Shopware know of your cookie!
Yet, let's have a look at it.

This code is basically a simple plugin, which is listening to an event called `CookieCollector_Collect_Cookies`,
which is the main event to register your cookie.
In the event listener, in this case called `addComfortCookie`, you have to create an instance of a `\Shopware\Bundle\CookieBundle\CookieCollection`.
Afterwards, you'll have to add at least one instance of a `\Shopware\Bundle\CookieBundle\Structs\CookieStruct`.

The `CookieStruct` requires three parameters, we highly suggest to use all four of them though.
The first parameter represents the cookie's technical name, which will be used for saving the current 'active' state of your cookie.

Very important for you is the second parameter, which is the RegEx to match your cookie.
This way you can deal with dynamic cookie names like `session-1`. Just try to be as precise as possible here,
since you **probably** do not want to match multiple cookies with just one RegEx.
In the example mentioned above, only cookies with the exact name `comfort` would be matched, neither `comfortCookie` nor `myComfort`.

If you really want to match multiple cookies with a single `CookieStruct`, go ahead and use a more open RegEx:
```php
new CookieStruct(
    'comfort',
    '/^swag/',
    'Every cookie prefixed with "swag"',
    CookieGroupStruct::COMFORT
)
```

Even when there's no common prefix, you could a RegEx capturing group to group up your cookies:
```php
new CookieStruct(
    'comfort',
    '/^(foo|bar)/',
    'All cookies starting with either "foo" or "bar"',
    CookieGroupStruct::COMFORT
)
```

The third parameter represents the label being shown in the cookie consent manager for your cookie.
Make sure to add translations here, e.g. like this:
```php
public function addComfortCookie(): CookieCollection
{
    $pluginNamespace = $this->container->get('snippets')->getNamespace('my_plugins_snippet_namespace');

    $collection = new CookieCollection();
    $collection->add(new CookieStruct(
        'comfort',
        '/^comfort$/',
        $pluginNamespace->get('my_cookie_label'),
        CookieGroupStruct::COMFORT
    ));

    return $collection;
}
```

The fourth parameter is optional and represents your cookie's group. If none is applied, the "Others" group is used.
You can find all default groups as constants in the `\Shopware\Bundle\CookieBundle\Structs\CookieGroupStruct`.
Also, have a look at the next headline to figure out how to register your cookie group, if necessary.

The last line of your method then returns the `CookieCollection` - and that's it, you've successfully registered your own cookie.

## Registering an own cookie group

Normally, you wouldn't want to do this, but there might be a use case forcing to create your own cookie group.
This is just as simple as registering your own cookie.

Once again, let's first start with the example, only to explain it in short then afterwards.

```php
public static function getSubscribedEvents(): array
{
    return [
        'CookieCollector_Collect_Cookie_Groups' => 'addCookieGroup',
    ];
}

public function addCookieGroup(\Enlight_Event_EventArgs $args): CookieGroupCollection
{
    $pluginNamespace = $this->container->get('snippets')->getNamespace('my_plugins_snippet_namespace');

    $collection = new CookieGroupCollection();
    $collection->add(new CookieGroupStruct(
        'custom',
        $pluginNamespace->get('custom_label'),
        $pluginNamespace->get('custom_description')
    ));

    return $collection;
}
```

As already mentioned, this looks very similar to registering your custom cookie.
First of all, the event being used is named `CookieCollector_Collect_Cookie_Groups` this time.

It expects a `\Shopware\Bundle\CookieBundle\CookieGroupCollection` as the return, thus you need to create it in your event listener.
It then has to be filled using a `\Shopware\Bundle\CookieBundle\Structs\CookieGroupStruct`, which accepts four parameters,
two of them being required.

The first one is the technical name of the group, which is also used by a cookie later on. Maybe add a constant for this.

The second parameter is the actual label to be shown with this group. Once more, make sure to add your translations just like shown in the example
above.

The third parameter is optional and represents a description, which will also be shown when the group is expanded.

The last parameter marks your group as "required", which will prevent the customer to disallow this group and its cookies.<br />
**Do not use this, unless you're a hundred percent sure what you're doing! This might come with legal issues!**

Afterwards, only return your new collection, and that's it!

<div class="alert alert-warning">
<strong>Note</strong>: Empty groups without a single assigned cookie are hidden by default!
</div>

## Reacting upon changes in javascript

We're aware of the fact, that some plugins need to react properly once their cookie got activated or de-activated.
For this reason, we also implemented a javascript event, which can be used to react on changes made to the customer's preferences.

For this we're making use of our publish / subscribe system in our jQuery plugins.
The event you need to subscribe to is called `plugin/swCookieConsentManager/onBuildCookiePreferences`.
It gets fired every time the customer clicks on the "Save" button in the cookie consent manager, no matter if actual changes were made or not.

Here is a short example on how to register your custom logic here:
```js
$.subscribe('plugin/swCookieConsentManager/onBuildCookiePreferences', function (event, plugin, preferences) {
    console.log("Do something like removing a cookie or displaying some warning regarding possible issues!");
});
```

The first parameter supplied represents an instance of the Event object, the second parameter being the `swCookieConsentManager` jQuery plugin
and the last one is an object containing all the necessary data, such as the groups, their active state, the cookies and their respective active state.

As already said, this event is only fired when the customer changes his preferences, or sets them for the very first time.
There might be the need to check for your cookie state with every single page-reload though, but we also got that issue covered.

You can actually check the active state of your cookie in javascript at any given point in time, using the global method `$.getCookiePreference(cookieName)`.
This will return either `true` or `false`, depending on your cookie's active state. If your cookie is unknown yet and thus not saved in the preferences,
`false` will be returned as well.

## Dealing with the LocalStorage

If your plugin also saves data into the LocalStorage, this might be a legal issue as well.
The consent manager itself neither prevents saving data into the LocalStorage nor does it delete or modify data being saved there, at least not with Shopware 5.6.3.

You can still use the consent manager to deal with this issue by simply registering another dummy cookie consisting of a random RegEx, so it matches no actual cookie, and a proper label.

```php
public function addComfortCookie(): CookieCollection
{
    $pluginNamespace = $this->container->get('snippets')->getNamespace('my_plugins_snippet_namespace');

    $collection = new CookieCollection();
    $collection->add(new CookieStruct(
        'allow_local_storage',
        '/^match_no_cookie_DJ7ra9WMy8$/',
        'Saving statistical data into the LocalStorage',
        CookieGroupStruct::COMFORT
    ));

    return $collection;
}
```

This way, your customers will be able to allow saving data into the LocalStorage and you can check for its active status in your javascript code.

```js
if ($.getCookiePreference('allow_local_storage')) {
    // Save data into LocalStorage
}
```