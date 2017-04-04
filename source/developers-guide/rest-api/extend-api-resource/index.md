---
layout: default
title: Extend a REST API resource
github_link: developers-guide/rest-api/extend-api-resource/index.md
shopware_version: 5.2.17
indexed: true
tags:
  - extend
  - api
  - Plugin
  - resource
group: Developer Guides
subgroup: REST API
menu_title: Extend a REST API resource
menu_order: 140
---

<div class="toc-list"></div>

## Introduction
This article will provide a small example on how to extend the existing REST API resources. As of Shopware 5.2.17 the resources are loaded as services to the dependency injection container and can be easily decorated.

## Plugin files
You only need a few files for this example plugin. For more information about necessary files and the Shopware 5.2 plugin system see the [5.2 plugin guide](/developers-guide/plugin-system).

### Services.xml
`SwagExtendArticleResource/Resources/services.xml`:
```
<?xml version="1.0" ?>
<container xmlns="http://symfony.com/schema/dic/services"
           xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
           xsi:schemaLocation="http://symfony.com/schema/dic/services http://symfony.com/schema/dic/services/services-1.0.xsd">
    <services>
        <service id="swag_extend_article_resource.article_resource"
                 class="SwagExtendArticleResource\Components\Api\Resource\Article"
                 decorates="shopware.api.article"
                 public="false"
                 shared="false">
        </service>
    </services>
</container>
```

### Article Resource
`SwagExtendArticleResource/Components/Api/Resource/Article.php`:
```
<?php

namespace SwagExtendArticleResource\Components\Api\Resource;

class Article extends \Shopware\Components\Api\Resource\Article
{
    /**
     * @inheritdoc
     */
    public function getList($offset = 0, $limit = 25, array $criteria = [], array $orderBy = [], array $options = [])
    {
        $result = parent::getList($offset, $limit, $criteria, $orderBy, $options);

        foreach($result['data'] as &$article) {
            $article['MyCustomField'] = 'CustomContent';
        }

        return $result;
    }
}
```
Execute the parent `getList()` function to get the original data and add your custom field to every product. Feel free to replace this with your own implementation.

## Download plugin
The whole plugin can be downloaded <a href="{{ site.url }}/exampleplugins/SwagExtendArticleResource.zip">here</a>.