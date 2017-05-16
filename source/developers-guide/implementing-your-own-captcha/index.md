---
layout: default
title: Implementing your own captcha
github_link: developers-guide/implementing-your-own-captcha/index.md
tags:
  - captcha
  - recaptcha
  - example
indexed: true
shopware_version: 5.3.0
---

<div class="toc-list"></div>

## Introduction

This article will show you how to implement your own [CAPTCHA](https://en.wikipedia.org/wiki/CAPTCHA).
Since Shopware 5.3 there is a new component which allows for choosing different CAPTCHAs and can be extended.
Extensions have to use the plugin system introduced in Shopware 5.2.

The finished example from this article is an implementation of Google ReCaptcha and can be downloaded <a href="{{ site.url }}/exampleplugins/SwagReCaptcha.zip">here</a>.

The plugin is structured like this:

```
├── Resources
│   ├── views
│   │   └── widgets
│   │       └── captcha
│   │           └── recaptcha.tpl
│   ├── config.xml
│   └── services.xml
├── plugin.png
├── plugin.xml
├── ReCaptcha.php
└── SwagReCaptcha.php
```

## The basics

For the Captcha to work we need

 - A plugin skeleton
 - A class implementing `Shopware\Components\Captcha\CaptchaInterface`, used for validation and challenge data generation
 - A template file, used to display the challenge to the user 
 - A service definition used by Shopware to recognize the new Captcha
 
## The plugin skeleton

This is similar to every other post-5.2 plugin:

 - Create a new folder `SwagReCaptcha` inside `custom/plugins`
 - Create a `plugin.xml` file inside your new folder
 - Create a `SwagReCaptcha.php` file inside your folder, using the same name as the folder.
 - Create the folder path `Resources/views` inside your folder
 - Optionally create a 16x16px `plugin.png` containing your backend icon to be shown in the plugin manager
 
### SwagReCaptcha.php
 
```php
<?php

namespace SwagReCaptcha;

use Shopware\Components\Plugin;

class SwagReCaptcha extends Plugin
{
    /**
     * @inheritdoc
     */
    public static function getSubscribedEvents()
    {
        return [
            'Enlight_Controller_Dispatcher_ControllerPath_Widgets_Captcha' => 'registerTemplatePath',
        ];
    }

    public function registerTemplatePath(\Enlight_Event_EventArgs $args)
    {
        $this->container->get('template')->addTemplateDir(
            $this->getPath() . '/Resources/views/'
        );
    }
}
```

### plugin.xml

```xml
<?xml version="1.0" encoding="utf-8"?>
<plugin xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
    xsi:noNamespaceSchemaLocation="https://raw.githubusercontent.com/shopware/shopware/5.2/engine/Shopware/Components/Plugin/schema/plugin.xsd">
    
    <label lang="de">Google ReCaptcha</label>
    <label lang="en">Google ReCaptcha</label>

    <version>1.0.0</version>
    <link>http://shopware.com</link>
    <author>shopware AG</author>
    <compatibility minVersion="5.2.0" />

    <changelog version="1.0.0">
        <changes lang="de">Veröffentlichung</changes>
        <changes lang="en">Release</changes>
    </changelog>
</plugin>
```

## Implementing the Captcha interface

Next step is to implement the captcha interface. The full implementation is shown first and then explained in detail.

```php
<?php

namespace SwagReCaptcha;

use Enlight_Controller_Request_Request;
use Shopware\Components\Captcha\CaptchaInterface;

class ReCaptcha implements CaptchaInterface
{

    /**
     * @var \Shopware\Components\HttpClient\GuzzleFactory
     */
    private $guzzle;

    /**
     * @var \Shopware_Components_Config
     */
    private $config;

    /**
     * @param \Shopware\Components\HttpClient\GuzzleFactory $guzzle
     * @param \Shopware_Components_Config $config
     */
    public function __construct(
        \Shopware\Components\HttpClient\GuzzleFactory $guzzle,
        \Shopware_Components_Config $config
    ) {
        $this->guzzle = $guzzle;
        $this->config = $config;
    }

    /**
     * {@inheritDoc}
     */
    public function validate(Enlight_Controller_Request_Request $request)
    {
        $recaptchaUserInput = $request->get('g-recaptcha-response');

        if (empty($recaptchaUserInput)) {
            return false;
        }

        $secret = $this->config->getByNamespace('SwagReCaptcha', 'secret');

        if (empty($secret)) {
            return false;
        }

        /** @var \GuzzleHttp\ClientInterface $client */
        $client = $this->guzzle->createClient();

        $response = $client->post('https://www.google.com/recaptcha/api/siteverify', [
            'body' => [
                'secret' => $secret,
                'response' => $recaptchaUserInput,
            ]
        ]);

        if ($response->getStatusCode() !== 200) {
            return false;
        }

        $body = $response->getBody();
        $content = json_decode($body->getContents(), true);

        return is_array($content) &&
        array_key_exists('success', $content) &&
        $content['success'] === true ? true : false;
    }

    /**
     * {@inheritDoc}
     */
    public function getTemplateData()
    {
        $sitekey = $this->config->getByNamespace('SwagReCaptcha', 'sitekey');

        return [
            'sitekey' => $sitekey
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'recaptcha';
    }
}
```

The interface you are implementing defines three methods:

 - `getName()`
     - Has to be unique
 - `validate(Enlight_Controller_Request_Request $request)`
     - This method will ingest the form post request after it is send.
       It has to return true or false, indicating wether the captcha was solved correctly or not.
 - `getTemplateData()`
     - Has to return an array which will be assigned to your smarty template.
     
In the case of ReCaptcha we need to make a call to Google from php, so `Guzzle` is injected into the constructor,
as well as `Shopware_Components_Config` since it needs the ReCaptcha sitekey and secret.

## Creating a backend menu

With the new plugin system, creating a backend config is fairly simple: Inside the `Resources` folder create a new file `config.xml` with the following content.
 
```xml
<?xml version="1.0" encoding="utf-8"?>
<config xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:noNamespaceSchemaLocation="https://raw.githubusercontent.com/shopware/shopware/5.2/engine/Shopware/Components/Plugin/schema/config.xsd">
    <elements>
        <element required="true" type="text">
            <name>sitekey</name>
            <label lang="de">ReCaptcha Sitekey</label>
            <label lang="en">ReCaptcha Sitekey</label>
        </element>
        <element required="true" type="text">
            <name>secret</name>
            <label lang="de">ReCaptcha Secret</label>
            <label lang="en">ReCaptcha Secret</label>
        </element>
    </elements>
</config>
```

The `name` tag is later used to access the values entered by the user.

## The template

Your template has to be inside the folder `Resources/views/widgets/captcha` and has to have the same name your `getName()` method returns.
In our case, the correct name is `recaptcha.tpl`.

The content is up to you, all html defined here is included into the target forms.
The ReCaptcha implementation only needs a fairly minimal markup, since most of the final markup is loaded from google:

```html
<div class="review--captcha">
    <div class="captcha--code">
        <script src='https://www.google.com/recaptcha/api.js'></script>
        <div class="g-recaptcha" data-sitekey="{$sitekey}"></div>
    </div>
</div>
```

The google script injects an input field named `g-recaptcha-response` which is used in the `validate()` as shown above.

## Creating a service tag

The last step is to make our captcha known to the DI container and inject the constructor parameters.
To achieve this, create a new file `services.xml` in the `Resources` folder with the following content:
  
```xml
<?xml version="1.0" encoding="utf-8"?>
<container xmlns="http://symfony.com/schema/dic/services"
           xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
           xsi:schemaLocation="http://symfony.com/schema/dic/services http://symfony.com/schema/dic/services/services-1.0.xsd">
    <services>
        <service id="shopware.captcha.recaptcha" class="SwagReCaptcha\ReCaptcha">
            <argument type="service" id="guzzle_http_client_factory"/>
            <argument type="service" id="config"/>
            <tag name="shopware.captcha"/>
        </service>
    </services>
</container>
```

Notice the `<tag name="shopware.captcha"/>` tag, it causes shopware to recognize the service as captcha implementation.