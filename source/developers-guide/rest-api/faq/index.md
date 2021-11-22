---
layout: default
title: REST API - Frequently Asked Questions
github_link: developers-guide/rest-api/faq/index.md
shopware_version: 
indexed: true
tags:
  - api
  - rest
  - faq
  - problems
group: Developer Guides
subgroup: REST API
menu_title: REST API FAQ
menu_order: 340
---

<div class="toc-list"></div>

## Introduction
This article is a summary of problems that are reported frequently and the solutions to those problems. 

## Why do I get HTML errors instead of JSON?
If you receive errors with HTML instead of JSON (usually with a complete stacktrace) like this:

```
<br />
<b>Fatal error</b>:  Uncaught Shopware\Components\Api\Exception\NotFoundException: Article by id 23213213 not found in /engine/Shopware/Components/Api/Resource/Article.php:155
Stack trace:
#0 /engine/Shopware/Controllers/Api/Articles.php(75): Shopware\Components\Api\Resource\Article-&gt;getOne('23213213', Array)
#1 /engine/Library/Enlight/Controller/Action.php(159): Shopware_Controllers_Api_Articles-&gt;getAction()
#2 /engine/Library/Enlight/Controller/Dispatcher/Default.php(523): Enlight_Controller_Action-&gt;dispatch('getAction')
#3 /engine/Library/Enlight/Controller/Front.php(223): Enlight_Controller_Dispatcher_Default-&gt;dispatch(Object(Enlight_Controller_Request_RequestHttp), Object(Enlight_Controller_Response_ResponseHttp))
#4 /engine/Shopware/Kernel.php(182): Enlight_Controller_Front-&gt;dispatch()
#5 / in <b>2/engine/Shopware/Components/Api/Resource/Article.php</b> on line <b>155</b><br />
```
This is a sign that the configs `throwExceptions` and `noErrorHandler` which are located in `config.php` are set to `true`.
Removing these entries or setting them to `false` should resolve this issue. 


## How can I access the API if my shop is protected with a `.htaccess` file?
You need to add an exception for the API route. Here is an example for the `.htaccess` file:
```
SetEnvIf Request_URI "(.*\/api((\/?$)|(\/.*$)))" ALLOW
Order Allow,Deny
Authtype Basic
AuthName "Shopware Testshop"
AuthUserFile /path/to/.htpasswd

require valid-user
Allow from env=ALLOW
Allow from env=REDIRECT_ALLOW
Satisfy any

# Rest of the shopware .htaccess
...

```

## How do I configure API development tools like Postman to use them with the Shopware API?

There are two ways to allow API dev tools to authenticate with the Shopware API:

* API tools like Postman support the following URL schema: 
http://username:apikey@mydemoshop.com/api/ (if you experience any issues with
Postman, try the Google Chrome version instead of the standalone version)
* Use the HTTP Basic authentication (introduced with Shopware 5.3.2)

To transmit a data to the API, select JSON (application/json) as Content-Type.
