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

## Extend Risk Managment Backend Module

To add a new risk rule to the ExtJS store used by the backend module
we use the smarty block `backend/risk_management/store/risk/data`
that can be found in `themes/Backend/ExtJs/backend/risk_management/store/risks.js`.


```php
// in install()
$this->subscribeEvent(
    'Enlight_Controller_Action_PostDispatchSecure_Backend_RiskManagement',
    'onRiskManagementBackend'
);
```

```php
public function onRiskManagementBackend(Enlight_Controller_ActionEventArgs $args)
{
    $subject = $args->getSubject();
    $request = $subject->Request();

    $view = $subject->View();
    $view->addTemplateDir(__DIR__.'/Views/');

    if ($request->getActionName() === 'load') {
        $view->extendsTemplate('backend/my_risk_rule/store/risks.js');
    }
}
```

The file `SwagCustomRiskRule/Views/backend/my_risk_rule/store/risks.js` adds a new entry to the existing store:

```
// SwagCustomRiskRule/Views/backend/my_risk_rule/store/risks.js
//{block name="backend/risk_management/store/risk/data"}
// {$smarty.block.parent}
{ description: 'My Custom Rule', value: 'MyCustomRule' },
//{/block}
```

## Handle custom Risk Rule

Given the new rule is named `MyCustomRule` the event we have subscribed to is `Shopware_Modules_Admin_Execute_Risk_Rule_sRiskMyCustomRule`.

```php
// in install()
$this->subscribeEvent(
    'Shopware_Modules_Admin_Execute_Risk_Rule_sRiskMyCustomRule',
    'onMyCustomRule'
);
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


## The complete plugin bootstrap


```php
// SwagCustomRiskRule/Bootstrap.php
<?php
class Shopware_Plugins_Backend_SwagCustomRiskRule_Bootstrap extends Shopware_Components_Plugin_Bootstrap
{
    public function install()
    {
        $this->subscribeEvent(
            'Enlight_Controller_Action_PostDispatchSecure_Backend_RiskManagement',
            'onRiskManagementBackend'
        );

        $this->subscribeEvent(
            'Shopware_Modules_Admin_Execute_Risk_Rule_sRiskMyCustomRule',
            'onMyCustomRule'
        );

        return true;
    }

    /**
     * @param Enlight_Controller_ActionEventArgs $args
     */
    public function onRiskManagementBackend(Enlight_Controller_ActionEventArgs $args)
    {
        $subject = $args->getSubject();
        $request = $subject->Request();

        $view = $subject->View();
        $view->addTemplateDir(__DIR__.'/Views/');

        if ($request->getActionName() === 'load') {
            $view->extendsTemplate('backend/my_risk_rule/store/risks.js');
        }
    }

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
}

```

You can find a installable ZIP package of this plugin <a href="{{ site.url }}/exampleplugins/SwagCustomRiskRule.zip">here</a>.


