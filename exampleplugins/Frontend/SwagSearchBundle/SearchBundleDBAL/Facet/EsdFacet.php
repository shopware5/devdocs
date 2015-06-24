<?php

namespace ShopwarePlugins\SwagSearchBundle\SearchBundleDBAL\Facet;

use Shopware\Bundle\SearchBundle\FacetInterface;

class EsdFacet implements FacetInterface
{
    /**
     * @inheritdoc
     */
    public function getName()
    {
        return 'swag_search_bundle_esd';
    }

    /**
     * @inheritdoc
     */
    public function jsonSerialize()
    {
        return get_object_vars($this);
    }
}
