<?php

namespace SwagVimeoElement;

use Shopware\Components\Plugin;
use Shopware\Components\Plugin\Context\ActivateContext;
use Shopware\Components\Plugin\Context\DeactivateContext;
use Shopware\Components\Plugin\Context\InstallContext;
use Shopware\Components\Plugin\Context\UninstallContext;
use SwagVimeoElement\Bootstrap\EmotionElementInstaller;

class SwagVimeoElement extends Plugin
{
    public function install(InstallContext $installContext)
    {
        $emotionElementInstaller = new EmotionElementInstaller(
            $this->getName(),
            $this->container->get('shopware.emotion_component_installer')
        );

        $emotionElementInstaller->install();
    }

    public function activate(ActivateContext $activateContext)
    {
        $activateContext->scheduleClearCache(ActivateContext::CACHE_LIST_ALL);
    }

    public function deactivate(DeactivateContext $deactivateContext)
    {
        $deactivateContext->scheduleClearCache(DeactivateContext::CACHE_LIST_ALL);
    }

    public function uninstall(UninstallContext $uninstallContext)
    {
        $uninstallContext->scheduleClearCache(UninstallContext::CACHE_LIST_ALL);
    }
}