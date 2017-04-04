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
