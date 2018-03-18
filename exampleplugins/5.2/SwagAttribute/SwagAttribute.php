<?php

namespace SwagAttribute;

use Doctrine\ORM\Tools\SchemaTool;
use Shopware\Components\Plugin;
use Shopware\Components\Plugin\Context\InstallContext;

class SwagAttribute extends Plugin
{
    public static function getSubscribedEvents()
    {
        return [
            'Enlight_Controller_Action_PostDispatch_Backend_Base' => 'extendExtJS'
        ];
    }

    public function install(InstallContext $context)
    {
        $context->scheduleClearCache(InstallContext::CACHE_LIST_DEFAULT);

        $service = $this->container->get('shopware_attribute.crud_service');

        $service->update('s_articles_attributes', 'my_column', 'combobox', [
            'label' => 'Field label',
            'supportText' => 'Value under the field',
            'helpText' => 'Value which displayed inside a help icon tooltip',

            //user has the opportunity to translate the attribute field for each shop
            'translatable' => true,

            //attribute will be displayed in the backend module
            'displayInBackend' => true,

            //in case of multi_selection or single_selection type, article entities can be selected,
            'entity' => 'Shopware\Models\Article\Article',

            //numeric position for the backend view, sorted ascending
            'position' => 100,

            //user can modify the attribute in the free text field module
            'custom' => true,

            //in case of combo box type, defines the selectable values
            'arrayStore' => [
                ['key' => '1', 'value' => 'first value'],
                ['key' => '2', 'value' => 'second value']
            ],
        ]);

        $service->update(
            's_articles_attributes',
            'my_own_validation',
            'string',
            ['label' => 'My own validation', 'displayInBackend' => true, 'translatable' => true],
            null,
            true
        );

        $service->update(
            's_articles_attributes',
            'my_own_type',
            'text',
            ['label' => 'My own extjs type', 'displayInBackend' => true, 'translatable' => true],
            null,
            true
        );

        $em = $this->container->get('models');
        $schemaTool = new SchemaTool($em);
        $schemaTool->updateSchema(
            [ $em->getClassMetadata(\SwagAttribute\Models\SwagAttribute::class) ],
            true
        );

        $service->update(
            's_articles_attributes',
            'my_multi_selection',
            'multi_selection',
            [
                'entity' => \SwagAttribute\Models\SwagAttribute::class,
                'displayInBackend' => true,
                'label' => 'My multi selection',
            ],
            null,
            true
        );
    }

    public function extendExtJS(\Enlight_Event_EventArgs $arguments)
    {
        /** @var \Enlight_View_Default $view */
        $view = $arguments->getSubject()->View();
        $view->addTemplateDir($this->getPath() . '/Resources/views/');
        $view->extendsTemplate('backend/swag_attribute/Shopware.attribute.Form.js');
    }
}
