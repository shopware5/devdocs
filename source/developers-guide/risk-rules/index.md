---
layout: default
title: Custom Risk Rules
github_link: developers-guide/risk-rules/index.md
shopware_version: 5.1.2
indexed: true
group: Developer Guides
subgroup: Tutorials
menu_title: Risk Rules
menu_order: 40
---

Shopware 5.1.2 introduced a new event to provide custom risk rules: `Shopware_Modules_Admin_Execute_Risk_Rule_sRisk[RiskName]`.

<div class="toc-list"></div>

## Extend Risk Management Backend Module

To add a new risk rule to the ExtJS store used by the backend module
we use the smarty block `backend/risk_management/store/risk/data`
that can be found in `themes/Backend/ExtJs/backend/risk_management/store/risks.js`.


```php
<?php
// SwagCustomRiskRule/Subscriber/RiskManagement.php
namespace SwagCustomRiskRule\Subscriber;

use Enlight\Event\SubscriberInterface;

class RiskManagement implements SubscriberInterface
{
    /*
     * @var  $pluginPath string
     */
    private $pluginPath;
    /**
     * @param $pluginPath
     */
    public function __construct($pluginPath)
    {
        $this->pluginPath = $pluginPath;
    }
    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            'Enlight_Controller_Action_PostDispatchSecure_Backend_RiskManagement' => 'onRiskManagementBackend'
        ];
    }
    public function onRiskManagementBackend(\Enlight_Event_EventArgs $args)
    {
        /** @var \Shopware_Controllers_Backend_Customer $controller */
        $controller = $args->get('subject');
        
        $view = $controller->View();
        $request = $controller->Request();
        
        $view->addTemplateDir($this->pluginPath . '/Resources/views');
        
        if ($request->getActionName() == 'load') {
            $view->extendsTemplate('backend/swag_custom_risk_rule/store/risks.js');
        }
    }
}
```

The file `SwagCustomRiskRule/Resources/views/backend/my_risk_rule/store/risks.js` adds a new entry to the existing store:

```javascript
// SwagCustomRiskRule/Resources/views/backend/my_risk_rule/store/risks.js

//{block name="backend/risk_management/store/risk/data"}
//{$smarty.block.parent}
{ description: 'My Custom Rule', value: 'MyCustomRule' },
//{/block}
```

## Handle custom Risk Rule

Given the new rule is named `MyCustomRule` the event we have subscribed to is `Shopware_Modules_Admin_Execute_Risk_Rule_sRiskMyCustomRule`.

```php
// extend existing subscriber or define a new one

public static function getSubscribedEvents()
{
    return [
        'Shopware_Modules_Admin_Execute_Risk_Rule_sRiskMyCustomRule' => 'onMyCustomRule'
    ];
}
```

In the event callback we have access to the the name of the rule, the user, the basket and to the value:

```php
/**
 * @param Enlight_Event_EventArgs $args
 * @return bool
 */
public function onMyCustomRule(Enlight_Event_EventArgs $args)
{
    $rule   = $args->get('rule');
    $user   = $args->get('user');
    $basket = $args->get('basket');
    $value  = $args->get('value');

    if ($basket['AmountNumeric'] > $value) {
        return true; // it's a risky customer
    }

    return false;
}
```

## The complete plugin

You can find an installable ZIP package of this plugin <a href="{{ site.url }}/exampleplugins/SwagCustomRiskRule.zip">here</a>.


