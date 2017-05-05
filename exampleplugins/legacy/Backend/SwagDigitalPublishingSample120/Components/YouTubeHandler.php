<?php

namespace Shopware\SwagDigitalPublishingSample120\Components;

use Shopware\SwagDigitalPublishing\Components\ElementHandler\PopulateElementHandlerInterface;

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
     *
     * @return array
     */
    public function handle(array $element)
    {
        return $element;
    }
}