<?php declare(strict_types=1);

namespace B2bAcl;

use Shopware\B2B\Acl\Framework\AclDdlService;
use Shopware\B2B\AclRoute\Framework\AclRoutingUpdateService;
use Shopware\Components\Plugin;
use Shopware\Components\Plugin\Context\InstallContext;

class B2bAcl extends Plugin
{
    public function install(InstallContext $context)
    {
        $connection = Shopware()->Container()->get('dbal_connection');
        $connection->exec(
            'CREATE TABLE IF NOT EXISTS `b2b_offer` (
               `id` INT(11) NOT NULL AUTO_INCREMENT,
               `s_user_id` INT(11) NULL DEFAULT NULL,
               `name` VARCHAR(255) NOT NULL COLLATE \'utf8_unicode_ci\',
               `description` TEXT NULL COLLATE \'utf8_unicode_ci\',
              
               PRIMARY KEY (`id`),
              
               CONSTRAINT b2b_offer_s_user_id_FK FOREIGN KEY (`s_user_id`) 
                 REFERENCES `s_user` (`id`) ON UPDATE NO ACTION ON DELETE CASCADE
            )
              COLLATE = utf8_unicode_ci;'
        );

        AclRoutingUpdateService::create()->addConfig(Offer\AclConfig::getAclConfigArray());
        AclDdlService::create()->createTable(new Offer\OfferAclTable());

        parent::install($context);
    }

    /**
     * @inheritdoc
     */
    public static function getSubscribedEvents()
    {
        return [
            'Enlight_Controller_Dispatcher_ControllerPath_Frontend_B2bOffer' => 'registerController',
            'Enlight_Controller_Action_PostDispatchSecure' => 'onPostDispatchSecure',
        ];
    }

    /**
     * @param \Enlight_Event_EventArgs $args
     */
    public function onPostDispatchSecure(\Enlight_Event_EventArgs $args)
    {
        $this->container->get('template')->addTemplateDir(
            $this->getPath() . '/Resources/views/'
        );
        $this->container->get('snippets')->addConfigDir(
            $this->getPath() . '/Resources/snippets/'
        );
    }

    /**
     * @param \Enlight_Event_EventArgs $args
     * @return string
     */
    public function registerController(\Enlight_Event_EventArgs $args)
    {
        $this->container->get('template')->addTemplateDir(
            $this->getPath() . '/Resources/views/'
        );
        $this->container->get('snippets')->addConfigDir(
            $this->getPath() . '/Resources/snippets/'
        );

        return $this->getPath() . '/Controllers/Frontend/B2bOffer.php';
    }
}
