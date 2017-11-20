<?php

namespace SwagCustomRiskRule;

use Shopware\Components\Plugin;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class SwagCustomRiskRule extends Plugin
{
    public function build(ContainerBuilder $container)
    {
        $container->setParameter('swag_custom_risk_rule.plugin_dir', $this->getPath());
        parent::build($container);
    }
}
