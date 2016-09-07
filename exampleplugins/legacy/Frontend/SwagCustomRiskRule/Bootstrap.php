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
