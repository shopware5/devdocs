<?php

namespace SwagDigitalPublishingSample\Components;

use Shopware\Bundle\StoreFrontBundle\Struct\ShopContextInterface;
use SwagDigitalPublishing\Components\ElementHandler\PopulateElementHandlerInterface;

class YouTubeHandler implements PopulateElementHandlerInterface
{
    /**
     * Check if the given element can be handled by our ElementHandler.
     *
     * @param array $element
     *
     * @return boolean
     */
    public function canHandle(array $element)
    {
        return $element['name'] === 'youtube';
    }

    /**
     * @param array $element
     * @param ShopContextInterface $context
     *
     * @return array
     */
    public function handle(array $element, ShopContextInterface $context)
    {
        return $element;
    }
}