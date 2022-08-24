<?php declare(strict_types=1);

namespace B2bContingentRuleItem;

use B2bContingentRuleItem\RuleItem\WeekdayRuleType;
use Shopware\B2B\Common\B2BContainerBuilder;
use Shopware\Components\Plugin;
use Shopware\Components\Plugin\Context\InstallContext;
use SwagB2bPlugin\Resources\DependencyInjection\SwagB2bPluginConfiguration;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class B2bContingentRuleItem extends Plugin
{
    /**
     * @param InstallContext $context
     */
    public function install(InstallContext $context)
    {
        $connection = Shopware()->Container()->get('dbal_connection');
        $connection->exec(
            'CREATE TABLE IF NOT EXISTS b2b_contingent_group_rule_weekday (
              contingent_rule_id INT(11) NOT NULL,
              weekday_id INT(11) NOT NULL,
              
              PRIMARY KEY (`contingent_rule_id`),
            
              CONSTRAINT b2b_contingent_group_rule_weekday_contingent_rule_id_FK FOREIGN KEY (`contingent_rule_id`) 
                REFERENCES `b2b_contingent_group_rule` (`id`) ON UPDATE NO ACTION ON DELETE CASCADE
             )
              COLLATE=\'utf8_unicode_ci\''
        );
    }

    /**
     * @param ContainerBuilder $container
     */
    public function build(ContainerBuilder $container)
    {
        $containerBuilder = B2BContainerBuilder::create();
        $containerBuilder->addConfiguration(new SwagB2bPluginConfiguration());
        $containerBuilder->registerConfigurations($container);

        $restrictTypes = $container->getParameter('b2b_contingent_rule.restrict_types');
        $restrictTypes[] = WeekdayRuleType::NAME;
        $container->setParameter('b2b_contingent_rule.restrict_types', $restrictTypes);
        parent::build($container);
    }

    /**
     * @inheritdoc
     */
    public static function getSubscribedEvents()
    {
        return [
            'Enlight_Controller_Action_PostDispatchSecure' => 'onPostDispatchSecure',
        ];
    }

    /**
     * @param \Enlight_Event_EventArgs $args
     */
    public function onPostDispatchSecure(\Enlight_Event_EventArgs $args)
    {
        $this->container->get('Template')->addTemplateDir(
            $this->getPath() . '/Resources/views/'
        );
    }
}
