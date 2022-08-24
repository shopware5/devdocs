---
layout: default
title: Search Redirects
github_link: search/developer/redirects.md
indexed: true
menu_title: Search Redirects
group: Shopware Enterprise
subgroup: Enterprise Search
subsubgroup: Developer
menu_order: 5
---

SES will perform redirects for search terms, if

 * the search term belongs to an active synonym group with a redirect defined
 * the search term is the product number of an existing product

You can easily add additional logic to also perform redirects e.g. for EAN or catalog numbers.

## General mechanism

SES has a redirect handler, which is called in the frontend search controller:

```
class SearchRedirectHandler
{
    public function getRedirect($searchTerm, SynonymStruct $synonym = null)
    {
        /** @var SearchRedirectInterface $redirect */
        foreach ($this->redirectServices as $redirect) {

            if ($url = $redirect->getRedirect($searchTerm, $synonym)) {
                return $url;
            }

        }

        return null;
    }
}
```

It will iterate all redirect implementations and return the first URL which is returned by one of the implementations.
Each implementation has to implement `SearchRedirectInterface`:

```
interface SearchRedirectInterface
{
    /**
     * Returns a redirect URL if a redirect should be performed - or NULL if no redirect can be determined in the
     * current implementation
     *
     * @param $searchTerm
     * @param SynonymStruct|null $synonym
     * @return string|null
     */
    public function getRedirect($searchTerm, SynonymStruct $synonym = null);
}
```

The synonym redirect implementation, for example, is quite simple:

```
// \SwagEnterpriseSearch\Bundle\EnterpriseSearchBundle\SearchRedirect\SynonymRedirect
class SynonymRedirect implements SearchRedirectInterface
{
    public function getRedirect($searchTerm, SynonymStruct $synonym = null)
    {
        if ($synonym && $synonym->getRedirectUrl()) {
            return $synonym->getRedirectUrl();
        }
    }
}
```

It will just check, if a valid synonym was passed and if it contains a redirect - and return that if it does.
Other implementations could be more complex, such as the `ProductNumberRedirect`:

```
// \SwagEnterpriseSearch\Bundle\EnterpriseSearchBundle\SearchRedirect\ProductNumberRedirect
class ProductNumberRedirect implements SearchRedirectInterface
{
    private $connection;
    private $router;
    private $contextService;

    public function __construct(Connection $connection, $router, ContextServiceInterface $contextService) { … }

    public function getRedirect($searchTerm, SynonymStruct $synonym = null)
    {
        $result = $this->getArticleByNumber($searchTerm);

        if (!$result) {
            return;
        }

        $assembleParams = [
            'sViewport' => 'detail',
            'sArticle' => $result['articleId'],
        ];

        // if variant is not the main variant, add the number to the URL
        if ($result['kind'] != 1) {
            $assembleParams['number'] = $result['number'];
        }

        return $this->router->assemble($assembleParams);
    }

    private function getArticleByNumber($searchTerm)
    {
        $result = $this->connection->fetchAll(
            'SELECT
              ad.ordernumber as number,
              ad.articleID as articleId,
              ad.kind
            FROM s_articles_details ad

            INNER JOIN s_categories c
              ON c.id = :mainCategory
              AND c.active = 1

            INNER JOIN s_articles_categories_ro ro
              ON ro.articleID = ad.articleID
              AND ro.categoryID = c.id


            WHERE `ordernumber` LIKE :number
            LIMIT 1',
            ['number' => $searchTerm, 'mainCategory' => $this->contextService->getShopContext()->getShop()->getCategory(
            )->getId()]
        );

        return array_shift($result);
    }
}
```

In this case the service will query the database in order to find a matching product and then assemble a SEO url which is returned.

## Implementing own redirect services
If you need to implement own redirect services (e.g. for catalog numbers which are stored as product attributes),
you just need to create a service implementing `SearchRedirectInterface` as described above.

Then register it in your `services.xml` using the tag `enterprise_search.redirect`. This could look like this:

```
<service id="swag_enterprise_search.redirect.catalog_redirect" class="SwagEnterpriseSearch\Bundle\EnterpriseSearchBundle\SearchRedirect\CatalogRedirect">
    <argument type="service" id="dbal_connection" />
    <tag name="enterprise_search.redirect" />
</service>
```

The tag will make sure, that your service is handled by `SearchRedirectHandler`.

## Priority
As `SearchRedirectHandler` will only handle the first URL returned by one of the implementations, the order of the services
is critical: Do you want product numbers or catalog numbers to be more important?
For that reason, the `enterprise_search.redirect` tag support priorities: The higher the priority is, the earlier the
corresponding handler will be executed.

```
<service id="swag_enterprise_search.redirect.product_number" class="SwagEnterpriseSearch\Bundle\EnterpriseSearchBundle\SearchRedirect\ProductNumberRedirect">
    <argument type="service" id="dbal_connection" />
    <argument type="service" id="router" />
    <argument type="service" id="shopware_storefront.context_service" />
    <tag name="enterprise_search.redirect" priority="20" />
</service>

<service id="swag_enterprise_search.redirect.synonym" class="SwagEnterpriseSearch\Bundle\EnterpriseSearchBundle\SearchRedirect\SynonymRedirect">
    <argument type="service" id="dbal_connection" />
    <tag name="enterprise_search.redirect" priority="10" />
</service>
```

As you can see, `ProductNumberRedirect` has a priority of 20, `SynonymRedirect` has a priority of 10. In order to make
your `CatalogRedirect` more important thant the `ProductNumberRedirect` you need to give it at least a priority of 21.
If you want to place it between `ProductNumberRedirect` and `SynonymRedirect`, it needs a priority between 10 and 20.
