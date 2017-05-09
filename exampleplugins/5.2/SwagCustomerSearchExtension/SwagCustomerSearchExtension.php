<?php

namespace SwagCustomerSearchExtension;

use Shopware\Components\Plugin;

class SwagCustomerSearchExtension extends Plugin
{
    public static function getSubscribedEvents()
    {
        return [
            'Enlight_Controller_Action_PostDispatch_Backend_Customer' => 'extendCustomerStream'
        ];
    }

    public function extendCustomerStream(\Enlight_Event_EventArgs $args)
    {
        /** @var \Shopware_Controllers_Backend_Customer $subject */
        $subject = $args->getSubject();

        $subject->View()->addTemplateDir(__DIR__ . '/Resources/views');

        $subject->View()->extendsTemplate('backend/customer/swag_customer_stream_extension.js');
    }
}