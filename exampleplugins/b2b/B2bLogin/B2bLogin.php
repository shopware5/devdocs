<?php declare(strict_types=1);

namespace B2bLogin;

use Shopware\Components\Plugin;
use Shopware\Components\Plugin\Context\InstallContext;

class B2bLogin extends Plugin
{
    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            'Enlight_Controller_Action_PreDispatch_Frontend' => 'addViewDirectories',
            'Enlight_Controller_Action_PostDispatchSecure_Widgets' => 'addViewDirectories',
        ];
    }

    /**
     * @param \Enlight_Controller_ActionEventArgs $args
     */
    public function addViewDirectories(\Enlight_Controller_ActionEventArgs $args)
    {
        $args->getSubject()->View()->addTemplateDir(__DIR__ . '/../Resources/views');
    }

    /**
     * @param InstallContext $context
     */
    public function install(InstallContext $context)
    {
        $connection = Shopware()->Container()->get('dbal_connection');
        $connection->exec('
            ALTER TABLE b2b_debtor_contact
            ADD staff_id VARCHAR(255);

            UNIQUE INDEX `dcstaff_id` (`staff_id`),
        ');

        $connection->exec('
            SET @a = 0;
            UPDATE b2b_debtor_contact SET staff_id = CONCAT(\'A\', \'-\', @a:=@a+1);
        ');

        $attributeService = Shopware()->Container()->get('shopware_attribute.crud_service');
        $attributeService->update('s_user_attributes', 'staff_id', 'string');

        $connection->exec('
            SET @a = 0;
            UPDATE s_user_attributes SET staff_id = CONCAT(\'B\', \'-\', @a:=@a+1);
        ');
    }
}
