---
layout: default
title: CSRF Protection
github_link: developers-guide/csrf-protection/index.md
shopware_version: 5.2.0
indexed: true
tags:
  - security
  - csrf
group: Developer Guides
subgroup: General Resources
menu_title: CSRF Protection
menu_order: 100
---

This article will focus CSRF attack protection, a new security feature included in Shopware. First, a short introduction to the problem:

> Cross-Site Request Forgery (CSRF) is an attack that forces an end user to execute unwanted actions on a web application in which they're currently authenticated. CSRF attacks specifically target state-changing requests, not theft of data since the attacker has no way to see the response to the forged request. With a little help of social engineering (such as sending a link via email or chat), an attacker may trick the users of a web application into executing actions of the attacker's choosing. If the victim is a normal user, a successful CSRF attack can force the user to perform state-changing requests like transferring funds, changing their email address, and so forth. If the victim is an administrative account, CSRF can compromise the entire web application.

*Source: [Open Web Application Security Project](https://www.owasp.org/index.php/Cross-Site_Request_Forgery_(CSRF))*

<div class="toc-list"></div>

## Attack scenario

Most of the times, the attacker uses a third party trusted website or an email to perform this attack. Suppose a victim is logged in on your shop website and finds a link on a 3rd party forum which opens a specially crafted link to your shop website. He clicks on the link and triggers a malicious request to your shop website.

**Example scenario:**

1. A user is logged in on a shop website http://fancyshop.com
2. This website has a delete account action, implemented in a form like this:
  `<form action="http://fancyshop.com/account/delete" method="post"><input type="submit" name="delete" value="delete" /></form>`
3. Once the button is clicked, the current account is deleted, relying on the currently active session to identify the user.
4. The attacker can now create a page that submits this form on load. He has posted the link to that page on a forum.
5. The user clicks on that link. The page submits the form, which will then delete his/her account because of the active session.
6. The account has been deleted without the user's knowledge.

The problem here is that this request originated in the user's browser, so the application has no way to detect if it was triggered intentionally or not.

## Countermeasures - CSRF tokens

CSRF tokens work by adding an additional authentication mechanism to requests, one that cannot be forged by an attack like the one described above. Using this, the server is able to identify and stop CSRF requests before they actually perform any change. CSRF tokens are typically unique, random strings that are provided by the server to the user's browser, either once per request or per session. On every state-changing request, the token value must be provided, otherwise, the server will ignore the request.

Let's revisit the above attack scenario, but this time using tokens to provide CSRF protection:
1. The website has the same delete account action, but the form now implements a hidden CSRF protection field:
  `<form action="http://fancyshop.com/account/delete" method="post"><input type="hidden" name="csrf_token" value="some-random-token-value" /><input type="submit" name="delete" value="delete" /></form>`
2. The token value was provided by the server and is unique to the user's request or session. So, as an attacker does not have access to the victim's browser, he is not able to read the value of the token.
3. If the user now clicks on the same malicious link the attacker provided, the same form submission will be triggered. However, as the malicious form submission no longer contains the `csrf_token` value (or has an invalid one), the server is now able to tell that this request is not legitimate, and blocks it.

Although more complex, Shopware's solution applies the concept illustrated above to all the actions that might be susceptible to CSRF attacks. It is also configurable in a way that makes it easy for plugin developers to integrate CSRF protection in their custom actions.

## Receiving a token

### Backend

When you open the backend, the first request made will be a `generate` request. This request will return a new token, bound to your session. Since this token is required for every other request, all subsequent requests will be queued until the `generate` request returns a response. From that point on, all future requests will automatically be modified to make use of the `X-CSRF-Token` header. If you have, for some reason, decided to use your own request library, make sure to set the `X-CSRF-Token` header in your request, otherwise every request will result in an exception.

Once the token has been returned, you can get it by using the `Ext.CSRFService` service. The `Ext.CSRFService.getToken()` method will return the current token.

### Frontend

When you open the front end, a request is made to generate a new token, bound to your session and saved in your local storage or cookie storage respectively. This request is independent from the regular shopping requests. After the token has been received, all forms in the shop will be extended by a hidden input field named `__csrf_token`. This is required to link the form submission to the user's session. In addition, every request made by jQuery will be extended with a new header named `X-CSRF-Token`, which includes the received token.

If you are not using jQuery in your plugin, you have to manually call `CSRF.updateForms()` after you've created a new form or replaced some parts of a view which contain a form.

If you need to load some data on pageload, there are a few things you need to consider:
1. If possible, use GET instead of POST, only POST-requests are secured by the token since only POST requests should modify data on the backend. If you only need to load some data, use GET.
2. If you really need to use a POST request on pageload, wait for the jQuery-event `plugin/swCsrfProtection/init` to be thrown. This ensures that your request uses a valid token.

## Validating the token

We have introduced a new component called `CSRFTokenValidator`. This component subscribes to the `Enlight_Controller_Action_PreDispatch_Backend`, `Enlight_Controller_Action_PreDispatch_Frontend` and `Enlight_Controller_Action_PreDispatch_Widgets` events and therefore catches every single request made (except for API requests), regardless of whether it's a `GET`, `POST` or `DELETE` request. The validator now checks if there is a `X-CSRF-Token` header or a `__csrf_token` parameter, which indicates that the application is aware of this protection mechanism. The token is then validated against the token saved in your session.

If there is no token set or the token is invalid, you'll get an exception with the following error message:

> The provided X-CSRF-Token header is invalid. If you're sure that the request should be valid, the called controller action needs to be whitelisted using the CSRFWhitelistAware interface.

That means that you either have to modify your plugin code or whitelist some of your actions.

### Addition to backend token validation

Simple GET requests made with a browser don't send custom headers. Therefore we cannot validate those requests. For this case, plugin developers have to implement a new interface called `CSRFWhitelistAware`.

## Whitelist particular actions

The `CSRFWhitelistAware` interface contains one method, which should return a list with names of whitelisted actions. This tells the validator not to check certain actions. This method applies to both frontend and backend actions.

For example, given an action called `downloadAsCsvAction()`, you have to add `downloadAsCsv` to list of whitelisted actions right inside of the new `getWhitelistedCSRFActions()` method. This looks as follows:

```php
<?php

use Shopware\Components\CSRFWhitelistAware;

class Shopware_Controllers_Backend_MyPlugin extends Shopware_Controllers_Backend_ExtJs implements CSRFWhitelistAware
{
    public function getWhitelistedCSRFActions()
    {
        return [
            'downloadAsCsv'
        ];
    }
}
```

## Disable the protection

In some cases, you might want to disable the protection for the backend or frontend. You can achieve this by setting the following options in your `config.php`. By default, both options are set to `true`.

```php
...
'csrfProtection' => [
    'frontend' => false,
    'backend' => false
],
...
```

## Plugin compatibility for older versions

Since Shopware 5.2, your plugin needs to whitelist an action in order to e.g. transfer files or show a page within a window or iframe. For Shopware versions prior to 5.2, the interface `CSRFWhitelistAware` isn't available and you'll receive an exception. In this case, you have to create a dummy interface which will only be loaded if the original one does not exist.

#### 1. Create a new file `Components/CSRFWhitelistAware.php`

```
<?php

namespace Shopware\Components;

if (!interface_exists('\Shopware\Components\CSRFWhitelistAware')) {
    interface CSRFWhitelistAware {}
}
```

#### 2. Require this file above your class definition in your `Bootstrap.php`

```
require_once __DIR__ . '/Components/CSRFWhitelistAware.php';
```
