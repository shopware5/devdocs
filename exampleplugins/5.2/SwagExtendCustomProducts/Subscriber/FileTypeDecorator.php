<?php

namespace SwagExtendCustomProducts\Subscriber;

use Enlight\Event\SubscriberInterface;
use Shopware\Components\DependencyInjection\Container;
use ShopwarePlugins\SwagCustomProducts\Components\FileUpload\FileTypeWhitelistInterface;
use SwagExtendCustomProducts\Decorators\FileTypeWhiteListDecorator;

class FileTypeDecorator implements SubscriberInterface
{
    /**
     * @var Container
     */
    private $container;

    /**
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            'Enlight_Bootstrap_AfterInitResource_custom_products.file_upload.file_type_whitelist' => 'decorateFileTypeWhiteList',
        ];
    }

    public function decorateFileTypeWhiteList()
    {
        /** @var FileTypeWhitelistInterface $fileTypeWhiteList */
        $fileTypeWhiteList = $this->container->get('custom_products.file_upload.file_type_whitelist');

        $this->container->set(
            'custom_products.file_upload.file_type_whitelist',
            new FileTypeWhiteListDecorator($fileTypeWhiteList)
        );
    }
}
