---
layout: default
title: Using global variables in templates
github_link: developers-guide/global-variables-in-templates/index.md
shopware_version: 5.2
indexed: true
tags:
  - global
  - variables
  - template
group: Developer Guides
subgroup: General Resources
menu_title: Global variables in templates
menu_order: 200
---

In this article we will show how to assign global variables to all frontend templates using a small plugin based on the 5.2 plugin system.

<div class="toc-list"></div>

## Register event
Since we want to add our global variables only to frontend templates we register to the secure post dispatches of frontend and widgets.  
__Attention:__ The plugin should not do some performance sensitive tasks here, otherwise each request in the storefront will be slowed down. Furthermore the assigned variables may not be compatible with the HTTP cache.
```    
/**
 * @inheritdoc
 */
public static function getSubscribedEvents()
{
    return [
        'Enlight_Controller_Action_PostDispatchSecure_Frontend' => 'onPostDispatch',
        'Enlight_Controller_Action_PostDispatchSecure_Widgets' => 'onPostDispatch'
    ];
}
```
Our example will just add the user login status to the template.
```
/**
 * @param \Enlight_Controller_ActionEventArgs $args
 */
public function onPostDispatch(\Enlight_Controller_ActionEventArgs $args)
{
    $args->getSubject()->View()->assign('sUserloggedIn', Shopware()->Modules()->Admin()->sCheckUser());
}
```

## Example Plugin
The complete code of this example plugin can be found <a href="{{ site.url }}/exampleplugins/SwagGlobalVariables.zip">here</a>. Just unpack it to your `custom/plugins` folder inside your shopware installation and activate it in the plugin manager.
