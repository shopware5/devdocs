---
layout: default
title: Boosting
github_link: search/developer/boosting.md
indexed: true
menu_title: Boosting
group: Shopware Enterprise
subgroup: Enterprise Search
subsubgroup: Developer
menu_order: 3
shopware_version: 1.2.0
---

"Boosting" is the process on prioritizing certain search results over others. For example a shop owner might
want to boost products in order to free the stock for new products. Or during a promotion products from a certain
manufacturer should be boosted.

<div class="toc-list"></div>

## Boosting in SES

Rules for boosting can be created from the SES backend module. The screenshot below shows the boosting configuration:

<img src="{{ site.url }}/assets/img/search/boosting_detail.png" style="width: 100%"/>

The follwing fields are available:

 * `Boosting name`: Usually there will be multiple boosting, for each use case a seperate one. In order to tell apart the
 various boostings, its recommended to choose a speaking name for each rule
 * `Active`: SES will only apply boostings which are marked as "active"
 * `Relevance`: The boost each product matching the rules will get. Usually a value between 1 and 100, higher values are also possible
 * `Boosting rule`: Defines, which products should get the defined boosting. In the image above all products having the
 "comming soon" flag set will get a boost of 10
 * `Valid from… till…`: Defines from when to when the boosting applies. This allows preparing future boostings / promotions

## Technical overview

Boostings are applied during query time, the main entry point is `\SwagEnterpriseSearch\Bundle\SearchBundleES\SearchQueryBuilder::applyBoosting`.
This will read all configured, active and valid boosting definitions from the database and apply them after they have been
converted to ElasticSearch queries by `\SwagEnterpriseSearch\Bundle\EnterpriseSearchBundle\Boosting\BoostingQueryBuilder::getBoostingQuery`.
All rule types that are available in the backend module (`AND`, `OR`, `PRODUCT_COMPARE` and `TRUE`) have separate
handlers, such as `\SwagEnterpriseSearch\Bundle\EnterpriseSearchBundle\Boosting\RuleHandler\AndRuleHandler` or
`\SwagEnterpriseSearch\Bundle\EnterpriseSearchBundle\Boosting\RuleHandler\ProductRuleHandler`. These will convert a definition
such as `['ProductCompareRule' => ['name', '=', 'table']` to an ElasticSearch query such as `BoolQuery(TermQuery('name', 'table'), BoolQuery::should)`.

### Own rule types

Own rule types can simply be registered through the DI container:

```
<service id="swag_enterprise_search.boosting.my_type" class="SwagEnterpriseSearch\Bundle\EnterpriseSearchBundle\Boosting\RuleHandler\MyType">
    <tag name="enterprise_search.boosting.rule" />
</service>
```

The tag `enterprise_search.boosting.rule` will register the given service as a rule handler.

The service now just needs to implement the interface `SwagEnterpriseSearch\Bundle\EnterpriseSearchBundle\Boosting\OperatorResolverInterface`:

```
namespace SwagEnterpriseSearch\Bundle\EnterpriseSearchBundle\Boosting\RuleHandler;

use ONGR\ElasticsearchDSL\Query\MatchQuery;
use SwagEnterpriseSearch\Bundle\EnterpriseSearchBundle\Boosting\OperatorResolverInterface;

class MyType implements RuleHandlerInterface
{
    public function supports($name)
    {
        return $name === 'my_rule';
    }

    public function handle($name, $data, $nextRecursion, SearchContextInterface $context)
    {
        list($field, $operator, $value) = $data;

        // logic to convert the current instance into an ES query

        return $query;
    }
}

```

Your `supports` method should return `true`, if your service is able to handle the given rule type - `AND`, `OR` and
`PRODUCT_COMPARE` are the default ones. If the service returned `true` for a given rule type, the `handle` method will be called:

 * `name`: The rule type, "my_rule" in the above case
 * `data`: Usually a tuple of three values: The field, the operator and the value. If your rule type is more like a container
 type (such as `AND`), `$data` will just contain more nested definitions.
 * `nextRecursion`: A callback method you can call in order to let other handlers handle the inner data of your rule type. Again
 this mostly applies for container types (such as `AND`).
 * `$context`: A context object with the current search term and the `ShopContext` object

The following examples show, how SES uses the `handle` method by itself:

```
// \SwagEnterpriseSearch\Bundle\EnterpriseSearchBundle\Boosting\RuleHandler\AndRuleHandler
public function handle($name, $data, $nextRecursion, SearchContextInterface $context)
{
    $boolQuery = new BoolQuery();
    $result = $nextRecursion($data);
    foreach ($result as $item) {
        $boolQuery->add($item, BoolQuery::MUST);
    }
    if (count($boolQuery->getQueries())) {
        $boolQuery->addParameter('boost', 1 / (count($boolQuery->getQueries())));
    }

    return $boolQuery;
}
```

The `AND` rule handler will create an ElasticSearch `BoolQuery`. Then it will call `$nextRecursion` so that all data
inside the `AND` rule is resolved. The return values are than added to `BoolQuery`. The definition `BoolQuery::MUST`
defines, that all sub-conditions need to evaluate to `true` if the current rule should apply.

Please notice, that ElasticSearch will add the boosting to each matching subrule. For that reason you need to devide `boost`
by the number of subrules:

```
if (count($boolQuery->getQueries())) {
    $boolQuery->addParameter('boost', 1 / (count($boolQuery->getQueries())));
}
```

### Own operators
Operators (such as `=` or `>=`) are handled by the `\SwagEnterpriseSearch\Bundle\EnterpriseSearchBundle\Boosting\OperatorResolver`.
The `OperatorResolver` will translate all operator definitions to the corresponding ElasticSearch queries:
The `>=` operator, for example, is converted to a `new RangeQuery($field, ['gte' => $value])` object. More complex
operators such as "notcontains" will be converted into a `MUST_NOT` `BoolQuery` with an inner `MatchQuery`:

```
$query = new BoolQuery();
$query->add(new MatchQuery($field, $value), BoolQuery::MUST_NOT);
```

Own operators can simply be implemented by decorating `\SwagEnterpriseSearch\Bundle\EnterpriseSearchBundle\Boosting\OperatorResolver`
and implementing `\SwagEnterpriseSearch\Bundle\EnterpriseSearchBundle\Boosting\OperatorResolverInterface`.
In your decorator just overwrite the method `getQuery`: If your own operator is passed, handle it by yourself, if another
operator is passed, just pass it to the decorated service.

### ValueSearch
In the SES boosting dialog the shop owner is able to search values for the boosting rules. This way he does not need
to remember IDs or exact product names - he can just search them in the dialog.
During this search SES will pass the current field name (e.g. "name") and the search term (e.g. "summer") to the
`\SwagEnterpriseSearch\Bundle\EnterpriseSearchBundle\Boosting\ValueSearch\ValueSearch` service. The `ValueSearch`
service will then iterate all registered services implementing the interface `\SwagEnterpriseSearch\Bundle\EnterpriseSearchBundle\Boosting\ValueSearch\ValueSearchInterface`.

Again each of this services has a `supports` method which sould return `true`, if the given field (e.g. "name") is
supported. Then the `findValues` method is called. The service should return a list of values for the given field
which match the given search term.

```
public function findValues($field, $searchQuery): array
{
    switch ($field) {
        case 'categoryIds':
            $sql = 'SELECT `id`, `description` FROM s_categories WHERE `description` LIKE :search';
            break;
        case 'id':
            $sql = 'SELECT `id`, `name` FROM s_articles WHERE `name` LIKE :search';
            break;
    }


    $result = $this->connection->fetchAll($sql, ['search' => '%' . $searchQuery . '%']);

    return $result;
}
```

The returned array should look like this:

```
[
    [1, "name"],
    [2, "other name"]
]
```

The first value *must* be the value of the field requested, in this case the ID of the category or product. All other
fields have just informational purpose for the user and should usually include the searched fields as well as other
relevant information such as the product number etc.

Own handlers for certain value types can be registered using the tag `enterprise_search.value_search`. Optionally
a `priority` can be set if you need to overwrite some default handlers, for example.
