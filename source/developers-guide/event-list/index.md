---
layout: default
title: Shopware event list
github_link: developers-guide/event-list/index.md
indexed: true
menu_title: Event list
menu_order: 55
group: Developer Guides
subgroup: Developing plugins
---

If you want to hook onto Shopware you need to know the events. For more about events, see [the event guide](/developers-guide/event-guide/). The following list will give an overview of the existing events and how to subscribe to them.

## Enlight_Controller_Front_StartDispatch

### Definition
```
public function dispatch()
{
    ...
    $this->eventManager->notify(
        'Enlight_Controller_Front_StartDispatch',
        $eventArgs
    );
    ...
}
```

### Registration
```
public static function getSubscribedEvents()
{
    return [
        'Enlight_Controller_Front_StartDispatch' => 'onEnlightControllerFrontStartDispatch'
    ];
}
```

### Listener
```
public function onEnlightControllerFrontStartDispatch(\Enlight_Event_EventArgs $arguments)
{
    /** @var $enlightController Enlight_Controller_Front */
    $enlightController = $arguments->getSubject();
}
```

### Arguments dump
```
stdClass Object
(
    [__CLASS__] => Enlight_Controller_EventArgs
    [_processed] => 
    [_name] => Enlight_Controller_Front_StartDispatch
    [_return] => 
    [_elements] => Array
        (
            [subject] => stdClass Object
                (
                    [__CLASS__] => Shopware_Proxies_EnlightControllerFrontProxy
                    [plugins] => Enlight_Plugin_Namespace_Loader
                    [router] => 
                    [dispatcher] => Enlight_Controller_Dispatcher_Default
                    [request] => 
                    [response] => Enlight_Controller_Response_ResponseHttp
                    [throwExceptions] => 
                    [returnResponse] => 
                    [invokeParams] => Array(7)
                    [instances] => Array(0)
                )
        )
)
```

## Enlight_Controller_Front_RouteStartup

### Definition
```
public function dispatch()
{
    ...
    $this->eventManager->notify(
        'Enlight_Controller_Front_StartDispatch',
        $eventArgs
    );
    ...
}
```

### Registration
```
public static function getSubscribedEvents()
{
    return [
        'Enlight_Controller_Front_RouteStartup' => 'onEnlightControllerFrontRouteStartup'
    ];
}
```

### Listener
```
public function onEnlightControllerFrontRouteStartup(\Enlight_Event_EventArgs $arguments)
{
    /** @var $enlightController Enlight_Controller_Front */
    $enlightController = $arguments->getSubject();
 
    /** @var $request Enlight_Controller_Request_RequestHttp */
    $request = $arguments->getRequest();
 
    /** @var $response Enlight_Controller_Response_ResponseHttp */
    $response = $arguments->getResponse();
}
```

### Arguments dump
```
stdClass Object
(
    [__CLASS__] => Enlight_Controller_EventArgs
    [_processed] => 
    [_name] => Enlight_Controller_Front_RouteStartup
    [_return] => 
    [_elements] => Array
        (
            [subject] => stdClass Object
                (
                    [__CLASS__] => Shopware_Proxies_EnlightControllerFrontProxy
                    [plugins] => Enlight_Plugin_Namespace_Loader
                    [router] => Enlight_Controller_Router_Default
                    [dispatcher] => Enlight_Controller_Dispatcher_Default
                    [request] => Enlight_Controller_Request_RequestHttp
                    [response] => Enlight_Controller_Response_ResponseHttp
                    [throwExceptions] => 
                    [returnResponse] => 
                    [invokeParams] => Array(7)
                    [instances] => Array(0)
                )
 
            [request] => stdClass Object
                (
                    [__CLASS__] => Enlight_Controller_Request_RequestHttp
                    [_paramSources] => Array(2)
                    [_requestUri] => /sw406/
                    [_baseUrl] => /sw406
                    [_basePath] => /sw406
                    [_pathInfo] => /
                    [_params] => Array(0)
                    [_rawBody] => 
                    [_aliases] => Array(0)
                    [_dispatched] => 
                    [_module] => 
                    [_moduleKey] => module
                    [_controller] => 
                    [_controllerKey] => controller
                    [_action] => 
                    [_actionKey] => action
                )
 
            [response] => stdClass Object
                (
                    [__CLASS__] => Enlight_Controller_Response_ResponseHttp
                    [_cookies] => Array(0)
                    [_body] => Array(0)
                    [_exceptions] => Array(0)
                    [_headers] => Array(1)
                    [_headersRaw] => Array(0)
                    [_httpResponseCode] => 200
                    [_isRedirect] => 
                    [_renderExceptions] => 
                    [headersSentThrowsException] => 1
                )
        )
)
```

## Enlight_Controller_Router_Route

### Definition
```
public function match($pathInfo, Context $context)
{
    ...
    $event = $this->eventManager->notifyUntil('Enlight_Controller_Router_Route', [
        //'subject' => $router, @deprecated someone need it?
        'request' => $request,
        'context' => $context,
    ]
    );
    ...
}
```

### Registration
```
public static function getSubscribedEvents()
{
    return [
        'Enlight_Controller_Router_Route' => 'onEnlightControllerRouteRoute'
    ];
}
```

### Listener
```
public function onEnlightControllerRouteRoute(\Enlight_Event_EventArgs $arguments)
{
    /** @var $enlightController Enlight_Controller_Router_Default */
    $enlightController = $arguments->getSubject();
 
    /** @var $request Enlight_Controller_Request_RequestHttp */
    $request = $arguments->getRequest();
}
```

### Arguments dump
```
stdClass Object
(
    [__CLASS__] => Enlight_Event_EventArgs
    [_processed] => 
    [_name] => Enlight_Controller_Router_Route
    [_return] => 
    [_elements] => Array
        (
            [subject] => stdClass Object
                (
                    [__CLASS__] => Enlight_Controller_Router_Default
                    [front] => Shopware_Proxies_EnlightControllerFrontProxy
                    [separator] => /
                    [globalParams] => Array(0)
                    [instances] => Array(0)
                )
 
            [request] => stdClass Object
                (
                    [__CLASS__] => Enlight_Controller_Request_RequestHttp
                    [_paramSources] => Array(2)
                    [_requestUri] => /sw406/
                    [_baseUrl] => /sw406
                    [_basePath] => /sw406
                    [_pathInfo] => /
                    [_params] => Array(0)
                    [_rawBody] => 
                    [_aliases] => Array(0)
                    [_dispatched] => 
                    [_module] => 
                    [_moduleKey] => module
                    [_controller] => 
                    [_controllerKey] => controller
                    [_action] => 
                    [_actionKey] => action
                )
        )
)
```

## Enlight_Controller_Front_RouteShutdown

### Definition
```
public function dispatch()
{
    ...
    $this->eventManager->notify(
        'Enlight_Controller_Front_RouteStartup',
        $eventArgs
    );
    ...
}
```

### Registration
```
public static function getSubscribedEvents()
{
    return [
        'Enlight_Controller_Front_RouteShutdown' => 'onEnlightControllerFrontRouteShutdown'
    ];
}
```

### Listener
```
public function onEnlightControllerFrontRouteShutdown(\Enlight_Event_EventArgs $arguments)
{
    /** @var $enlightController Enlight_Controller_Front */
    $enlightController = $arguments->getSubject();
 
    /** @var $request Enlight_Controller_Request_RequestHttp */
    $request = $arguments->getRequest();
 
    /** @var $response Enlight_Controller_Response_ResponseHttp */
    $response = $arguments->getResponse();
}
```

### Arguments dump
```
stdClass Object
(
    [__CLASS__] => Enlight_Controller_EventArgs
    [_processed] => 
    [_name] => Enlight_Controller_Front_RouteShutdown
    [_return] => 
    [_elements] => Array
        (
            [subject] => stdClass Object
                (
                    [__CLASS__] => Shopware_Proxies_EnlightControllerFrontProxy
                    [plugins] => Enlight_Plugin_Namespace_Loader
                    [router] => Enlight_Controller_Router_Default
                    [dispatcher] => Enlight_Controller_Dispatcher_Default
                    [request] => Enlight_Controller_Request_RequestHttp
                    [response] => Enlight_Controller_Response_ResponseHttp
                    [throwExceptions] => 
                    [returnResponse] => 
                    [invokeParams] => Array(7)
                    [instances] => Array(0)
                )
 
            [request] => stdClass Object
                (
                    [__CLASS__] => Enlight_Controller_Request_RequestHttp
                    [_paramSources] => Array(2)
                    [_requestUri] => /sw406/
                    [_baseUrl] => /sw406
                    [_basePath] => /sw406
                    [_pathInfo] => /
                    [_params] => Array(0)
                    [_rawBody] => 
                    [_aliases] => Array(0)
                    [_dispatched] => 
                    [_module] => 
                    [_moduleKey] => module
                    [_controller] => 
                    [_controllerKey] => controller
                    [_action] => 
                    [_actionKey] => action
                )
 
            [response] => stdClass Object
                (
                    [__CLASS__] => Enlight_Controller_Response_ResponseHttp
                    [_cookies] => Array(0)
                    [_body] => Array(0)
                    [_exceptions] => Array(0)
                    [_headers] => Array(1)
                    [_headersRaw] => Array(0)
                    [_httpResponseCode] => 200
                    [_isRedirect] => 
                    [_renderExceptions] => 
                    [headersSentThrowsException] => 1
                )
        )
)
```

## Enlight_Controller_Front_DispatchLoopStartup

### Definition
```
public function dispatch()
{
    ...
    $this->eventManager->notify(
        'Enlight_Controller_Front_DispatchLoopStartup',
        $eventArgs
    );
    ...
}
```

### Registration
```
public static function getSubscribedEvents()
{
    return [
        'Enlight_Controller_Front_DispatchLoopStartup' => 'onEnlightControllerFrontDispatchLoopStartup'
    ];
}
```

### Listener
```
public function onEnlightControllerFrontDispatchLoopStartup(\Enlight_Event_EventArgs $arguments)
{
    /** @var $enlightController Enlight_Controller_Front */
    $enlightController = $arguments->getSubject();
 
    /** @var $request Enlight_Controller_Request_RequestHttp */
    $request = $arguments->getRequest();
 
    /** @var $response Enlight_Controller_Response_ResponseHttp */
    $response = $arguments->getResponse();
}
```

### Arguments dump
```
stdClass Object
(
    [__CLASS__] => Enlight_Controller_EventArgs
    [_processed] => 
    [_name] => Enlight_Controller_Front_DispatchLoopStartup
    [_return] => 
    [_elements] => Array
        (
            [subject] => stdClass Object
                (
                    [__CLASS__] => Shopware_Proxies_EnlightControllerFrontProxy
                    [plugins] => Enlight_Plugin_Namespace_Loader
                    [router] => Enlight_Controller_Router_Default
                    [dispatcher] => Enlight_Controller_Dispatcher_Default
                    [request] => Enlight_Controller_Request_RequestHttp
                    [response] => Enlight_Controller_Response_ResponseHttp
                    [throwExceptions] => 
                    [returnResponse] => 
                    [invokeParams] => Array(7)
                    [instances] => Array(0)
                )
 
            [request] => stdClass Object
                (
                    [__CLASS__] => Enlight_Controller_Request_RequestHttp
                    [_paramSources] => Array(2)
                    [_requestUri] => /sw406/
                    [_baseUrl] => /sw406
                    [_basePath] => /sw406
                    [_pathInfo] => /
                    [_params] => Array(0)
                    [_rawBody] => 
                    [_aliases] => Array(0)
                    [_dispatched] => 
                    [_module] => 
                    [_moduleKey] => module
                    [_controller] => 
                    [_controllerKey] => controller
                    [_action] => 
                    [_actionKey] => action
                )
 
            [response] => stdClass Object
                (
                    [__CLASS__] => Enlight_Controller_Response_ResponseHttp
                    [_cookies] => Array(0)
                    [_body] => Array(0)
                    [_exceptions] => Array(0)
                    [_headers] => Array(1)
                    [_headersRaw] => Array(0)
                    [_httpResponseCode] => 200
                    [_isRedirect] => 
                    [_renderExceptions] => 
                    [headersSentThrowsException] => 1
                )
        )
)
```

## Enlight_Controller_Front_PreDispatch

### Definition
```
public function dispatch()
{
    ...
    $this->eventManager->notify(
        'Enlight_Controller_Front_PreDispatch',
        $eventArgs
    );
    ...
}
```

### Registration
```
public static function getSubscribedEvents()
{
    return [
        'Enlight_Controller_Front_PreDispatch' => 'onEnlightControllerFrontPreDispatch'
    ];
}
```

### Listener
```
public function onEnlightControllerFrontPreDispatch(\Enlight_Event_EventArgs $arguments)
{
    /** @var $enlightController Enlight_Controller_Front */
    $enlightController = $arguments->getSubject();
 
    /** @var $request Enlight_Controller_Request_RequestHttp */
    $request = $arguments->getRequest();
 
    /** @var $response Enlight_Controller_Response_ResponseHttp */
    $response = $arguments->getResponse();
}
```

### Arguments dump
```
stdClass Object
(
    [__CLASS__] => Enlight_Controller_EventArgs
    [_processed] => 
    [_name] => Enlight_Controller_Front_PreDispatch
    [_return] => 
    [_elements] => Array
        (
            [subject] => stdClass Object
                (
                    [__CLASS__] => Shopware_Proxies_EnlightControllerFrontProxy
                    [plugins] => Enlight_Plugin_Namespace_Loader
                    [router] => Enlight_Controller_Router_Default
                    [dispatcher] => Enlight_Controller_Dispatcher_Default
                    [request] => Enlight_Controller_Request_RequestHttp
                    [response] => Enlight_Controller_Response_ResponseHttp
                    [throwExceptions] => 
                    [returnResponse] => 
                    [invokeParams] => Array(7)
                    [instances] => Array(0)
                )
 
            [request] => stdClass Object
                (
                    [__CLASS__] => Enlight_Controller_Request_RequestHttp
                    [_paramSources] => Array(2)
                    [_requestUri] => /sw406/
                    [_baseUrl] => /sw406
                    [_basePath] => /sw406
                    [_pathInfo] => /
                    [_params] => Array(0)
                    [_rawBody] => 
                    [_aliases] => Array(0)
                    [_dispatched] => 1
                    [_module] => 
                    [_moduleKey] => module
                    [_controller] => 
                    [_controllerKey] => controller
                    [_action] => 
                    [_actionKey] => action
                )
 
            [response] => stdClass Object
                (
                    [__CLASS__] => Enlight_Controller_Response_ResponseHttp
                    [_cookies] => Array(0)
                    [_body] => Array(0)
                    [_exceptions] => Array(0)
                    [_headers] => Array(1)
                    [_headersRaw] => Array(0)
                    [_httpResponseCode] => 200
                    [_isRedirect] => 
                    [_renderExceptions] => 
                    [headersSentThrowsException] => 1
                )
        )
)
```

## Enlight_Controller_Action_PreDispatch*

### Definition
```
public function dispatch($action)
{
    ...
    Shopware()->Events()->notify(
        __CLASS__ . '_PreDispatch',
        $args
    );

    Shopware()->Events()->notify(
        __CLASS__ . '_PreDispatch_' . $moduleName,
        $args
    );

    Shopware()->Events()->notify(
        __CLASS__ . '_PreDispatch_' . $this->controller_name,
        $args
    );
    ...
}
```

### Registration
```
public static function getSubscribedEvents()
{
    return [
        'Enlight_Controller_Action_PreDispatch_Frontend_Detail' => 'onEnlightControllerActionPreDispatchFrontendDetail'
    ];
}
```

### Listener
```
public function onEnlightControllerActionPreDispatchFrontendDetail(\Enlight_Event_EventArgs $arguments)
{
    /** @var $enlightController Enlight_Controller_Action */
    $enlightController = $arguments->getSubject();
 
    /** @var $request Enlight_Controller_Request_RequestHttp */
    $request = $arguments->getRequest();
 
    /** @var $response Enlight_Controller_Response_ResponseHttp */
    $response = $arguments->getResponse();
}
```

### Arguments dump
```
stdClass Object
(
    [__CLASS__] => Enlight_Event_EventArgs
    [_processed] => 
    [_name] => Enlight_Controller_Action_PreDispatch_Frontend_Detail
    [_return] => 
    [_elements] => Array
        (
            [subject] => stdClass Object
                (
                    [__CLASS__] => Shopware_Proxies_ShopwareControllersFrontendDetailProxy
                    [front] => Shopware_Proxies_EnlightControllerFrontProxy
                    [view] => Enlight_View_Default
                    [request] => Enlight_Controller_Request_RequestHttp
                    [response] => Enlight_Controller_Response_ResponseHttp
                    [controller_name] => Frontend_Detail
                    [instances] => Array(0)
                )
 
            [request] => stdClass Object
                (
                    [__CLASS__] => Enlight_Controller_Request_RequestHttp
                    [_paramSources] => Array(2)
                    [_requestUri] => /sw406/sommerwelten/accessoires/170/sonnenbrille-red
                    [_baseUrl] => /sw406
                    [_basePath] => /sw406
                    [_pathInfo] => /sommerwelten/accessoires/170/sonnenbrille-red
                    [_params] => Array(4)
                    [_rawBody] => 
                    [_aliases] => Array(1)
                    [_dispatched] => 1
                    [_module] => frontend
                    [_moduleKey] => module
                    [_controller] => detail
                    [_controllerKey] => controller
                    [_action] => index
                    [_actionKey] => action
                )
 
            [response] => stdClass Object
                (
                    [__CLASS__] => Enlight_Controller_Response_ResponseHttp
                    [_cookies] => Array(0)
                    [_body] => Array(0)
                    [_exceptions] => Array(0)
                    [_headers] => Array(1)
                    [_headersRaw] => Array(0)
                    [_httpResponseCode] => 200
                    [_isRedirect] => 
                    [_renderExceptions] => 
                    [headersSentThrowsException] => 1
                )
        )
)
```

## Enlight_Controller_Action_PostDispatch* and Enlight_Controller_Action_PostDispatchSecure*

### Definition
```
public function dispatch($action)
{
    ...
    if ($this->Request()->isDispatched()
        && !$this->Response()->isException()
        && $this->View()->hasTemplate()
    ) {
        Shopware()->Events()->notify(
            __CLASS__ . '_PostDispatchSecure_' . $this->controller_name,
            $args
        );

        Shopware()->Events()->notify(
            __CLASS__ . '_PostDispatchSecure_' . $moduleName,
            $args
        );

        Shopware()->Events()->notify(
            __CLASS__ . '_PostDispatchSecure',
            $args
        );
    }

    // fire non-secure/legacy-PostDispatch-Events
    Shopware()->Events()->notify(
        __CLASS__ . '_PostDispatch_' . $this->controller_name,
        $args
    );

    Shopware()->Events()->notify(
        __CLASS__ . '_PostDispatch_' . $moduleName,
        $args
    );

    Shopware()->Events()->notify(
        __CLASS__ . '_PostDispatch',
        $args
    );
    ...
}
```

### Registration
```
public static function getSubscribedEvents()
{
    return [
        'Enlight_Controller_Action_PostDispatch_Frontend_Detail' => 'onEnlightControllerActionPostDispatchFrontendDetail'
    ];
}
```

### Listener
```
public function onEnlightControllerActionPostDispatchFrontendDetail(\Enlight_Event_EventArgs $arguments)
{
    /** @var $enlightController Enlight_Controller_Action */
    $enlightController = $arguments->getSubject();
 
    /** @var $request Enlight_Controller_Request_RequestHttp */
    $request = $arguments->getRequest();
 
    /** @var $response Enlight_Controller_Response_ResponseHttp */
    $response = $arguments->getResponse();
}
```

### Arguments dump
```
stdClass Object
(
    [__CLASS__] => Enlight_Event_EventArgs
    [_processed] => 
    [_name] => Enlight_Controller_Action_PostDispatch_Frontend_Detail
    [_return] => 
    [_elements] => Array
        (
            [subject] => stdClass Object
                (
                    [__CLASS__] => Shopware_Proxies_ShopwareControllersFrontendDetailProxy
                    [front] => Shopware_Proxies_EnlightControllerFrontProxy
                    [view] => Enlight_View_Default
                    [request] => Enlight_Controller_Request_RequestHttp
                    [response] => Enlight_Controller_Response_ResponseHttp
                    [controller_name] => Frontend_Detail
                    [instances] => Array(0)
                )
 
            [request] => stdClass Object
                (
                    [__CLASS__] => Enlight_Controller_Request_RequestHttp
                    [_paramSources] => Array(2)
                    [_requestUri] => /sw406/sommerwelten/accessoires/170/sonnenbrille-red
                    [_baseUrl] => /sw406
                    [_basePath] => /sw406
                    [_pathInfo] => /sommerwelten/accessoires/170/sonnenbrille-red
                    [_params] => Array(4)
                    [_rawBody] => 
                    [_aliases] => Array(1)
                    [_dispatched] => 1
                    [_module] => frontend
                    [_moduleKey] => module
                    [_controller] => detail
                    [_controllerKey] => controller
                    [_action] => index
                    [_actionKey] => action
                )
 
            [response] => stdClass Object
                (
                    [__CLASS__] => Enlight_Controller_Response_ResponseHttp
                    [_cookies] => Array(0)
                    [_body] => Array(0)
                    [_exceptions] => Array(0)
                    [_headers] => Array(1)
                    [_headersRaw] => Array(0)
                    [_httpResponseCode] => 200
                    [_isRedirect] => 
                    [_renderExceptions] => 
                    [headersSentThrowsException] => 1
                )
        )
)
```

## Enlight_Plugins_ViewRenderer_PreRender

### Definition
```
public function renderTemplate($template, $name = null)
{
    ...
    $this->Application()->Events()->notify(
        'Enlight_Plugins_ViewRenderer_PreRender',
        array(
            'subject' => $this,
            'template' => $template,
            'request' => $this->Action()->Request()
        )
    );
    ...
}
```

### Registration
```
public static function getSubscribedEvents()
{
    return [
        'Enlight_Plugins_ViewRenderer_PreRender' => 'onEnlightPluginsViewRendererPreRender'
    ];
}
```

### Listener
```
public function onEnlightPluginsViewRendererPreRender(\Enlight_Event_EventArgs $arguments)
{
    /** @var $enlightPlugin Enlight_Controller_Plugins_ViewRenderer_Bootstrap */
    $enlightPlugin = $arguments->getSubject();
 
    /** @var $template Enlight_Template_Default */
    $template = $arguments->getTemplate();
}
```

### Arguments dump
```
stdClass Object
(
    [__CLASS__] => Enlight_Event_EventArgs
    [_processed] => 
    [_name] => Enlight_Plugins_ViewRenderer_PreRender
    [_return] => 
    [_elements] => Array
        (
            [subject] => stdClass Object
                (
                    [__CLASS__] => Enlight_Controller_Plugins_ViewRenderer_Bootstrap
                    [neverRender] => 
                    [noRender] => 
                    [front] => Shopware_Proxies_EnlightControllerFrontProxy
                    [action] => Shopware_Proxies_ShopwareControllersFrontendIndexProxy
                    [engine] => Enlight_Template_Manager
                    [name] => ViewRenderer
                    [collection] => Enlight_Plugin_Namespace_Loader
                    [instances] => Array(0)
                )
 
            [template] => stdClass Object
                (
                    [__CLASS__] => Enlight_Template_Default
                    [cache_id] => 
                    [compile_id] => frontend_emotion_orange_de_DE_1
                    [caching] => 
                    [cache_lifetime] => 3600
                    [template_resource] => 
                    [mustCompile] => 
                    [has_nocache_code] => 
                    [properties] => Array(3)
                    [required_plugins] => Array(2)
                    [smarty] => Enlight_Template_Manager
                    [block_data] => Array(1)
                    [variable_filters] => Array(0)
                    [used_tags] => Array(0)
                    [allow_relative_path] => 
                    [_capture_stack] => Array(1)
                    [template_class] => Smarty_Internal_Template
                    [tpl_vars] => Array(28)
                    [parent] => Enlight_Template_Manager
                    [config_vars] => Array(0)
                )
        )
)
```

## Enlight_Plugins_ViewRenderer_FilterRender

### Definition
```
public function renderTemplate($template, $name = null)
{
    ...
    $render = $this->Application()->Events()->filter(
        'Enlight_Plugins_ViewRenderer_FilterRender',
        $render,
        array('subject' => $this, 'template' => $template)
    );
    ...
}
```

### Registration
```
public static function getSubscribedEvents()
{
    return [
        'Enlight_Plugins_ViewRenderer_FilterRender' => 'onEnlightPluginsViewRendererFilterRender'
    ];
}
```

### Listener
```
public function onEnlightPluginsViewRendererFilterRender(\Enlight_Event_EventArgs $arguments)
{
    /** @var $enlightPlugin Enlight_Controller_Plugins_ViewRenderer_Bootstrap */
    $enlightPlugin = $arguments->getSubject();
 
    /** @var $template Enlight_Template_Default */
    $template = $arguments->getTemplate();
}
```

### Arguments dump
```
stdClass Object
(
    [__CLASS__] => Enlight_Event_EventArgs
    [_processed] => 
    [_name] => Enlight_Plugins_ViewRenderer_FilterRender
    [_return] => 
 
    [_elements] => Array
        (
            [subject] => stdClass Object
                (
                    [__CLASS__] => Enlight_Controller_Plugins_ViewRenderer_Bootstrap
                    [neverRender] => 
                    [noRender] => 
                    [front] => Shopware_Proxies_EnlightControllerFrontProxy
                    [action] => Shopware_Proxies_ShopwareControllersWidgetsIndexProxy
                    [engine] => Enlight_Template_Manager
                    [name] => ViewRenderer
                    [collection] => Enlight_Plugin_Namespace_Loader
                    [instances] => Array(0)
                )
 
            [template] => stdClass Object
                (
                    [__CLASS__] => Enlight_Template_Default
                    [cache_id] => 
                    [compile_id] => frontend_emotion_orange_de_DE_1
                    [caching] => 
                    [cache_lifetime] => 3600
                    [template_resource] => widgets/index/menu.tpl
                    [mustCompile] => 
                    [has_nocache_code] => 
                    [properties] => Array(5)
                    [required_plugins] => Array(2)
                    [smarty] => Enlight_Template_Manager
                    [block_data] => Array(0)
                    [variable_filters] => Array(0)
                    [used_tags] => Array(0)
                    [allow_relative_path] => 
                    [_capture_stack] => Array(1)
                    [template_class] => Smarty_Internal_Template
                    [tpl_vars] => Array(2)
                    [parent] => Enlight_Template_Manager
                    [config_vars] => Array(0)
                )
        )
)
```

## Enlight_Plugins_ViewRenderer_PostRender

### Definition
```
public function renderTemplate($template, $name = null)
{
    ...
    $this->Application()->Events()->notify(
        'Enlight_Plugins_ViewRenderer_PostRender',
        array('subject' => $this, 'template' => $template)
    );
    ...
}
```

### Registration
```
public static function getSubscribedEvents()
{
    return [
        'Enlight_Plugins_ViewRenderer_PostRender' => 'onEnlightPluginsViewRendererPostRender'
    ];
}
```

### Listener
```
public function onEnlightPluginsViewRendererPostRender(\Enlight_Event_EventArgs $arguments)
{
    /** @var $enlightPlugin Enlight_Controller_Plugins_ViewRenderer_Bootstrap */
    $enlightPlugin = $arguments->getSubject();
 
    /** @var $template Enlight_Template_Default */
    $template = $arguments->getTemplate();
}
```

### Arguments dump
```
stdClass Object
(
    [__CLASS__] => Enlight_Event_EventArgs
    [_processed] => 
    [_name] => Enlight_Plugins_ViewRenderer_PostRender
    [_return] => 
    [_elements] => Array
        (
            [subject] => stdClass Object
                (
                    [__CLASS__] => Enlight_Controller_Plugins_ViewRenderer_Bootstrap
                    [neverRender] => 
                    [noRender] => 
                    [front] => Shopware_Proxies_EnlightControllerFrontProxy
                    [action] => Shopware_Proxies_ShopwareControllersWidgetsIndexProxy
                    [engine] => Enlight_Template_Manager
                    [name] => ViewRenderer
                    [collection] => Enlight_Plugin_Namespace_Loader
                    [instances] => Array(0)
                )
 
            [template] => stdClass Object
                (
                    [__CLASS__] => Enlight_Template_Default
                    [cache_id] => 
                    [compile_id] => frontend_emotion_orange_de_DE_1
                    [caching] => 
                    [cache_lifetime] => 3600
                    [template_resource] => widgets/index/menu.tpl
                    [mustCompile] => 
                    [has_nocache_code] => 
                    [properties] => Array(5)
                    [required_plugins] => Array(2)
                    [smarty] => Enlight_Template_Manager
                    [block_data] => Array(0)
                    [variable_filters] => Array(0)
                    [used_tags] => Array(0)
                    [allow_relative_path] => 
                    [_capture_stack] => Array(1)
                    [template_class] => Smarty_Internal_Template
                    [tpl_vars] => Array(2)
                    [parent] => Enlight_Template_Manager
                    [config_vars] => Array(0)
                )
        )
)
```

## Enlight_Controller_Front_PostDispatch

### Definition
```
public function dispatch()
{
    ...
    $this->eventManager->notify(
        'Enlight_Controller_Front_PostDispatch',
        $eventArgs
    );
    ...
}
```

### Registration
```
public static function getSubscribedEvents()
{
    return [
        'Enlight_Controller_Front_PostDispatch' => 'onEnlightControllerFrontPostDispatch'
    ];
}
```

### Listener
```
public function onEnlightControllerFrontPostDispatch(\Enlight_Event_EventArgs $arguments)
{
    /** @var $enlightController Enlight_Controller_Front */
    $enlightController = $arguments->getSubject();
 
    /** @var $request Enlight_Controller_Request_RequestHttp */
    $request = $arguments->getRequest();
 
    /** @var $response Enlight_Controller_Response_ResponseHttp */
    $response = $arguments->getResponse();
}
```

### Arguments dump
```
stdClass Object
(
    [__CLASS__] => Enlight_Controller_EventArgs
    [_processed] => 
    [_name] => Enlight_Controller_Front_PostDispatch
    [_return] => 
    [_elements] => Array
        (
            [subject] => stdClass Object
                (
                    [__CLASS__] => Shopware_Proxies_EnlightControllerFrontProxy
                    [plugins] => Enlight_Plugin_Namespace_Loader
                    [router] => Enlight_Controller_Router_Default
                    [dispatcher] => Enlight_Controller_Dispatcher_Default
                    [request] => Enlight_Controller_Request_RequestHttp
                    [response] => Enlight_Controller_Response_ResponseHttp
                    [throwExceptions] => 
                    [returnResponse] => 
                    [invokeParams] => Array(7)
                    [instances] => Array(0)
                )
 
            [request] => stdClass Object
                (
                    [__CLASS__] => Enlight_Controller_Request_RequestHttp
                    [_paramSources] => Array(2)
                    [_requestUri] => /sw406/
                    [_baseUrl] => /sw406
                    [_basePath] => /sw406
                    [_pathInfo] => /
                    [_params] => Array(0)
                    [_rawBody] => 
                    [_aliases] => Array(0)
                    [_dispatched] => 1
                    [_module] => frontend
                    [_moduleKey] => module
                    [_controller] => index
                    [_controllerKey] => controller
                    [_action] => index
                    [_actionKey] => action
                )
 
            [response] => stdClass Object
                (
                    [__CLASS__] => Enlight_Controller_Response_ResponseHttp
                    [_cookies] => Array(0)
                    [_body] => Array(1)
                    [_exceptions] => Array(0)
                    [_headers] => Array(1)
                    [_headersRaw] => Array(0)
                    [_httpResponseCode] => 200
                    [_isRedirect] => 
                    [_renderExceptions] => 
                    [headersSentThrowsException] => 1
                )
        )
)
```

## Enlight_Controller_Front_DispatchLoopShutdown

### Definition
```
public function dispatch()
{
    ...
    $this->eventManager->notify(
        'Enlight_Controller_Front_DispatchLoopShutdown',
        $eventArgs
    );
    ...
}
```

### Registration
```
public static function getSubscribedEvents()
{
    return [
        'Enlight_Controller_Front_DispatchLoopShutdown' => 'onEnlightControllerFrontDispatchLoopShutdown'
    ];
}
```

### Listener
```
public function onEnlightControllerFrontDispatchLoopShutdown(\Enlight_Event_EventArgs $arguments)
{
    /** @var $enlightController Enlight_Controller_Front */
    $enlightController = $arguments->getSubject();
 
    /** @var $request Enlight_Controller_Request_RequestHttp */
    $request = $arguments->getRequest();
 
    /** @var $response Enlight_Controller_Response_ResponseHttp */
    $response = $arguments->getResponse();
}
```

### Arguments dump
```
stdClass Object
(
    [__CLASS__] => Enlight_Controller_EventArgs
    [_processed] => 
    [_name] => Enlight_Controller_Front_DispatchLoopShutdown
    [_return] => 
    [_elements] => Array
        (
            [subject] => stdClass Object
                (
                    [__CLASS__] => Shopware_Proxies_EnlightControllerFrontProxy
                    [plugins] => Enlight_Plugin_Namespace_Loader
                    [router] => Enlight_Controller_Router_Default
                    [dispatcher] => Enlight_Controller_Dispatcher_Default
                    [request] => Enlight_Controller_Request_RequestHttp
                    [response] => Enlight_Controller_Response_ResponseHttp
                    [throwExceptions] => 
                    [returnResponse] => 
                    [invokeParams] => Array(7)
                    [instances] => Array(0)
                )
 
            [request] => stdClass Object
                (
                    [__CLASS__] => Enlight_Controller_Request_RequestHttp
                    [_paramSources] => Array(2)
                    [_requestUri] => /sw406/
                    [_baseUrl] => /sw406
                    [_basePath] => /sw406
                    [_pathInfo] => /
                    [_params] => Array(0)
                    [_rawBody] => 
                    [_aliases] => Array(0)
                    [_dispatched] => 1
                    [_module] => frontend
                    [_moduleKey] => module
                    [_controller] => index
                    [_controllerKey] => controller
                    [_action] => index
                    [_actionKey] => action
                )
 
            [response] => stdClass Object
                (
                    [__CLASS__] => Enlight_Controller_Response_ResponseHttp
                    [_cookies] => Array(0)
                    [_body] => Array(1)
                    [_exceptions] => Array(0)
                    [_headers] => Array(1)
                    [_headersRaw] => Array(0)
                    [_httpResponseCode] => 200
                    [_isRedirect] => 
                    [_renderExceptions] => 
                    [headersSentThrowsException] => 1
                )
        )
)
```

## Enlight_Controller_Dispatcher_ControllerPath_*

### Definition
```
public function getControllerPath(Enlight_Controller_Request_Request $request)
{
    ...
    if ($event = Shopware()->Events()->notifyUntil(
            'Enlight_Controller_Dispatcher_ControllerPath_' . $moduleName . '_' . $controllerName,
            ['subject' => $this, 'request' => $request]
            )
    ) {
        $path = $event->getReturn();
    } else {
        $path = $this->curDirectory . $controllerName . '.php';
    }
    ...
}
```

### Registration
```
public static function getSubscribedEvents()
{
    return [
        'Enlight_Controller_Dispatcher_ControllerPath_Frontend_Detail' => 'onEnlightControllerDispatcherControllerPathFrontendDetail'
    ];
}
```

### Listener
```
public function onEnlightControllerDispatcherControllerPathFrontendDetail(\Enlight_Event_EventArgs $arguments)
{
    /** @var $enlightController Enlight_Controller_Dispatcher_Default */
    $enlightController = $arguments->getSubject();
 
    /** @var $request Enlight_Controller_Request_RequestHttp */
    $request = $arguments->getRequest();
}
```

### Arguments dump
```
stdClass Object
(
    [__CLASS__] => Enlight_Event_EventArgs
    [_processed] => 
    [_name] => Enlight_Controller_Dispatcher_ControllerPath_Frontend_Detail
    [_return] => 
    [_elements] => Array
        (
            [subject] => stdClass Object
                (
                    [__CLASS__] => Enlight_Controller_Dispatcher_Default
                    [curDirectory] => /var/www/sw406/engine/Shopware/Controllers/Frontend/
                    [curModule] => frontend
                    [defaultAction] => index
                    [defaultController] => index
                    [defaultModule] => frontend
                    [frontController] => 
                    [pathDelimiter] => _
                    [wordDelimiter] => Array(2)
                    [controllerDirectory] => Array(4)
                    [front] => Shopware_Proxies_EnlightControllerFrontProxy
                    [response] => Enlight_Controller_Response_ResponseHttp
                    [instances] => Array(0)
                )
 
            [request] => stdClass Object
                (
                    [__CLASS__] => Enlight_Controller_Request_RequestHttp
                    [_paramSources] => Array(2)
                    [_requestUri] => /sw406/sommerwelten/accessoires/170/sonnenbrille-red
                    [_baseUrl] => /sw406
                    [_basePath] => /sw406
                    [_pathInfo] => /sommerwelten/accessoires/170/sonnenbrille-red
                    [_params] => Array(4)
                    [_rawBody] => 
                    [_aliases] => Array(1)
                    [_dispatched] => 1
                    [_module] => frontend
                    [_moduleKey] => module
                    [_controller] => detail
                    [_controllerKey] => controller
                    [_action] => 
                    [_actionKey] => action
                )
        )
)
```

## Theme_Inheritance_Template_Directories_Collected

### Definition
```
public function getTemplateDirectories(Shop\Template $template)
{
    $directories = $this->getTemplateDirectoriesRecursive(
        $template->getId(),
        $this->fetchTemplates()
    );

    $directories = $this->eventManager->filter(
        'Theme_Inheritance_Template_Directories_Collected',
        $directories,
        ['template' => $template]
    );

    return $directories;
}
```

### Registration
```
public static function getSubscribedEvents()
{
    return [
        'Theme_Inheritance_Template_Directories_Collected' => 'onCollectTemplateDirectories',
    ];
}
```

### Listener
```
public function onCollectTemplateDirectories(\Enlight_Event_EventArgs $args)
{
    /** @var $directories array */
    $directories = $args->getReturn();

    /** @var $template \Shopware\Models\Shop\Template */
    $template = $args->get('template');
    
    // adding own plugin view directory ($this->pluginDirectory contains plugin path)
    $directories[] = $this->pluginDirectory . '/Resources/views';

    $args->setReturn($directories);
}
```

### Arguments dump
```
stdClass object
(
    [__CLASS__] => Enlight_Event_EventArgs
    [_processed] => 
    [_name] => Theme_Inheritance_Template_Directories_Collected
    [_return] => Array (
        [0] => /var/www/html/themes/Frontend/Responsive
        [1] => /var/www/html/themes/Frontend/Bare
    )
    [_elements] => Array(
        [template] => Shopware\Models\Shop\Template
    )
)
```
