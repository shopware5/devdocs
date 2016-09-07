<?php

namespace ShopwarePlugins\SwagESBlog\ESIndexingBundle;

use Shopware\Bundle\ESIndexingBundle\SettingsInterface;
use Shopware\Bundle\StoreFrontBundle\Struct\Shop;

class BlogSettings implements SettingsInterface
{
    /**
     * @param Shop $shop
     * @return array|null
     */
    public function get(Shop $shop)
    {
        return [
            'settings' => [
                'analysis' => [
                    'analyzer' => [
                        'blog_analyzer' => [
                            'type' => 'custom',
                            'tokenizer' => 'standard',
                            'filter' => [
                                'lowercase',
                            ]
                        ]
                    ]
                ]
            ]
        ];
    }
}
