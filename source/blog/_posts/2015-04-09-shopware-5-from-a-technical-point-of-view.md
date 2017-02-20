---
title: Shopware 5 from a technical point of view
tags: [tech, odl]

categories:
- dev

authors: [viison]
indexed: true
github_link: blog/_posts/2015-04-09-shopware-5-from-a-technical-point-of-view.md
---

Shopware moves with the times and ships Shopware 5 with a completely overhauled default template, which is now fully responsive. With more and more shoppers <a href="https://www.internetretailer.com/2015/03/09/nearly-half-digital-shoppers-top-retailers-are-mobile-only" target="_blank">preferring to use mobile devices</a> for online shopping, this is an important step to take. The core classes have also been revised and especially with the new <a href="https://developers.shopware.com/developers-guide/shopware-5-search-bundle/" target="_blank">SearchBundle classes</a>, plugin authors and Shopware agencies now have a much easier time to customize article listings.

![image](/blog/img/shopware-5-from-a-technical-point-of-view_1.png)

Another area where Shopware 5 provides improvements is internationalization. Since not all countries have the same address format as Germany, Shopware has been made more flexible at this point. The street name and street number fields have been merged into one address line field, furthermore it is now possible to enable up to two additional address line fields for customer addresses.

![image](/blog/img/shopware-5-from-a-technical-point-of-view_2.png)

## SearchBundle in Action

<p>As mentioned earlier, manipulating article listings got a lot simpler with Shopware 5. Let us have a look at how this makes plugin development easier. One of our plugins, the <a href="http://store.shopware.com/en/viison00571/latest-articles.html" target="_blank">Latest articles plugin</a>, can be massively simplified by making use of the new SearchBundle classes. The task of this plugin is to provide a page with a special article listing, which displays articles that are newly available in the online shop. Our old plugin had 826 lines of code, more than 500 of them only making sure that the latest article page behaves the same way as an ordinary category listing (e.g. sorting &amp; pagination functionalities). All this code was essentially duplicated, because there was no clean way to manipulate the queries that provide the article data of a category. An alternative to this in Shopware 4 would have been to use the `Shopware_Modules_Articles_sGetArticlesByCategory_FilterSql` event to make changes to the SQL that is used to gather the article data. Manipulating SQL queries with search &amp; replace is not a good style though, since it is not robust to changes of the original query and can also easily create SQL injection vulnerabilities when performed carelessly. Luckily, Shopware 5 allows us to perform customized article filtering and sorting in a much cleaner way.</p>

The following is the whole code needed for a frontend controller that shows an article listing with custom filtering:

```php
class Shopware_Controllers_Frontend_ViisonLatestArticles extends Enlight_Controller_Action
{
    public function indexAction()
    {
        $context = $this->get('shopware_storefront.context_service')->getProductContext();
        $criteria = $this->get('shopware_search.store_front_criteria_factory')->createListingCriteria(
            $this->Request(),
            $context
        );
        $maxAge = 30; // Only show articles created within the last 30 days
        $criteria->addCondition(new ArticleAgeCondition($maxAge));
        $result = $this->get('shopware_search.product_search')->search(
            $criteria,
            $context
        );
        $products = $this->get('legacy_struct_converter')->convertListProductStructList(
            $result->getProducts()
        );
        $this->View()->assign([
            'showListing' => true,
            'ajaxCountUrlParams' => ['SwagSearchBundleCount' => 1],
            'criteria' => $criteria,
            'facets' => $result->getFacets(),
            'sArticles' => $products,
            'shortParameters' => $this->get('query_alias_mapper')->getQueryAliases(),
            'sSort' => $this->Request()->getParam('sSort')
        ]);
    }
}
```
The ArticleAgeCondition is a custom filter criterion that performs the specific filtering that our plugin needs. Shopware also comes with a number of predefined conditions, so you do not always have to write these conditions yourself. The predefined conditions can be found in the <a href="https://developers.shopware.com/developers-guide/shopware-5-search-bundle/" target="_blank">SearchBundle documentation</a>. The ArticleAgeCondition class is defined as follows:

```php
class ArticleAgeCondition implements ConditionInterface
{
    /**
     * @var int
     */
    private $maxAge = 30;
    /**
     * @param int $maxAge
     */
    public function __construct($maxAge)
    {
        $this->maxAge = $maxAge;
    }
    /**
     * @return int
     */
    public function getMaxAge()
    {
        return $this->maxAge;
    }
    /**
     * @return string
     */
    public function getName()
    {
        return 'viison_article_age';
    }
}
```

As you see, the condition class itself does not a whole lot, it is basically just a container for the parameters of a filter. The real logic behind a condition is implemented in its respective handler class:

```php
class ArticleAgeConditionHandler implements ConditionHandlerInterface
{
    public function supportsCondition(ConditionInterface $condition)
    {
        return ($condition instanceof ArticleAgeCondition);
    }

    public function generateCondition (
        ConditionInterface $condition,
        QueryBuilder $query,
        ShopContextInterface $context
    ) {
        $query->andWhere('product.datum IS NOT NULL');
        $query->andWhere('DATEDIFF(NOW(), product.datum) <= :max_age');
        $query->setParameter('max_age', $condition->getMaxAge());
    }
}
```

To make Shopware aware of our custom condition handler, we have to register it with the SearchBundle:

```php
class LatestArticlesSubcriber implements SubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return [
           'Shopware_SearchBundleDBAL_Collect_Condition_Handlers' => 'registerConditionHandler'
       ];
    }

    public function registerConditionHandler()
    {
        return new ArticleAgeConditionHandler();
    }
}
```

By defining a `Views/frontend/viison_latest_articles_new/index.tpl` template that extends `parent:frontend/listing/index.tpl`, we come up with a result like the following when visiting `http://<SHOP_URL>/ViisonLatestArticles`:

![image](/blog/img/shopware-5-from-a-technical-point-of-view_3.png)

Both the filter and sorting features are working as expected, without us having to write additional code:

![image](/blog/img/shopware-5-from-a-technical-point-of-view_4.png)

So we are done already – not too much work involved indeed. At the same time, the new implementation of our plugin has only 169 lines of code in total – 80% less than the original plugin. To make it even better, it now has the new filtering feature and its code is much cleaner, eliminating all code duplication.

## Splitting an address line into street name + house number

The new combined address line in Shopware 5 is good for internationalization, but unfortunately, sometimes still separate street names and house numbers are needed. In our case, we stumbled upon this need while making our <a href="http://store.shopware.com/viison00656/dhl-adapter.html" target="_blank">DHL Adapter</a> ready for Shopware 5. When creating a shipping label via the DHL web service, street name and house number are both required fields. This is the case not only for national, but also for international shipments.

At first thought, the task of splitting an address line into street name and house number might seem simple – looking for a number at the end of a string (when thinking of a typical German address like ‘Mustermannstr. 1’) cannot be too difficult. However, especially when considering international addresses, the complexity of this task becomes apparent. In many other countries, the house number is placed at the beginning of the address and not at the end (e.g. “1117 Franklin Blvd”, “205, rue Richelieu”). To make things even more complicated, users might be tempted to insert additional information into the address line, especially when no additional address lines are available (e.g. “3940 Radio Road, Unit 110”, “Pallaswiesenstr. 57 App. 235”). We therefore recommend shop operators to enable at least one additional address line to make the splitting of address information a bit easier at this point.

f the splitting only has to work for german addresses and we assume that only street name and house number are given, the splitting can be performed relatively robustly using a regex like:

```
\A\s*(.*?)\s*\/?(\pN+\s*[a-zA-Z]?(?:\s*[-\/\pP]\s*\pN+\s*[a-zA-Z]?)*)\s*\Z
```

This takes into account that house numbers can sometimes look a bit more complicated (“13/15”, “7 B”, “3-10”, “2/41/7/21”). This method has shown to give correct results for about 98-100% of german addresses.

If you also want to support international addresses, this task becomes incredibly harder. It certainly is possible to come up with a regex that works well enough on this task, though. The downside here is the added complexity and that maintainability might be a problem if you don’t have a regex guru nearby.

Here is a *slightly* more complicated regex that is capable of splitting our unit tests successfully:

```
\A\s*
(?: #########################################################################
    # Option A: [<Addition to address 1>] <Street name> <House number>      #
    # [<Addition to address 2>]                                             #
    #########################################################################
    (?:(?P<A_Addition_to_address_1>.*?),\s*)? # Addition to address 1
(?:No\.\s*)?
    (?P<A_Street_name_1>\pN+[a-zA-Z]?(?:\s*[-\/\pP]\s*\pN+[a-zA-Z]?)*) # Street name
\s*,?\s*
    (?P<A_House_number_1>(?:[a-zA-Z]\s*|\pN\pL{2,}\s\pL)\S[^,#]*?(?<!\s)) # House number
\s*(?:(?:[,\/]|(?=\#))\s*(?!\s*No\.)
    (?P<A_Addition_to_address_2>(?!\s).*?))? # Addition to address 2
|   #########################################################################
    # Option B: [<Addition to address 1>] <House number> <Street name>      #
    # [<Addition to address 2>]                                             #
    #########################################################################
    (?:(?P<B_Addition_to_address_1>.*?),\s*(?=.*[,\/]))? # Addition to address 1
    (?!\s*No\.)(?P<B_House_number>\S\s*\S(?:[^,#](?!\b\pN+\s))*?(?<!\s)) # House number
\s*[\/,]?\s*(?:\sNo\.)?\s+
    (?P<B_Street_name>\pN+\s*-?[a-zA-Z]?(?:\s*[-\/\pP]?\s*\pN+(?:\s*[\-a-zA-Z])?)*|[IVXLCDM]+(?!.*\b\pN+\b))(?<!\s) # Street name
\s*(?:(?:[,\/]|(?=\#)|\s)\s*(?!\s*No\.)\s*
    (?P<B_Addition_to_address_2>(?!\s).*?))? # Addition to address 2
)
\s*\Z
```

Note that this is a regex in PCRE_EXTENDED format, which allows for free formatting and comments inside the regex. To use it within PHP, you therefore have to use the <a href="http://php.net/manual/en/reference.pcre.pattern.modifiers.php" target="_blank">/x pattern modifier</a>.

You can run our unit tests and play with this monster over at the great <a href="https://regex101.com/r/vO5fY7/1" target="_blank">regex101.com site</a>. Find a fully functional address splitter class below:

```php
class AddressSplittingService
{
    public static function splitAddress($address)
    {
                $regex = '
            /\A\s*
            (?: #########################################################################
                # Option A: [<Addition to address 1>] <Street name> <House number>      #
                # [<Addition to address 2>]                                             #
                #########################################################################
                (?:(?P<A_Addition_to_address_1>.*?),\s*)? # Addition to address 1
            (?:No\.\s*)?
                (?P<A_Street_name_1>\pN+[a-zA-Z]?(?:\s*[-\/\pP]\s*\pN+[a-zA-Z]?)*) # Street name
            \s*,?\s*
                (?P<A_House_number_1>(?:[a-zA-Z]\s*|\pN\pL{2,}\s\pL)\S[^,#]*?(?<!\s)) # House number
            \s*(?:(?:[,\/]|(?=\#))\s*(?!\s*No\.)
                (?P<A_Addition_to_address_2>(?!\s).*?))? # Addition to address 2
            |   #########################################################################
                # Option B: [<Addition to address 1>] <House number> <Street name>      #
                # [<Addition to address 2>]                                             #
                #########################################################################
                (?:(?P<B_Addition_to_address_1>.*?),\s*(?=.*[,\/]))? # Addition to address 1
                (?!\s*No\.)(?P<B_House_number>\S\s*\S(?:[^,#](?!\b\pN+\s))*?(?<!\s)) # House number
            \s*[\/,]?\s*(?:\sNo\.)?\s+
                (?P<B_Street_name>\pN+\s*-?[a-zA-Z]?(?:\s*[-\/\pP]?\s*\pN+(?:\s*[\-a-zA-Z])?)*|[IVXLCDM]+(?!.*\b\pN+\b))(?<!\s) # Street name
            \s*(?:(?:[,\/]|(?=\#)|\s)\s*(?!\s*No\.)\s*
                (?P<B_Addition_to_address_2>(?!\s).*?))? # Addition to address 2
            )
            \s*\Z/x';
                $result = preg_match($regex, $address, $matches);
                if ($result === 0)
                {
            throw new Exception('Address \'' . $address . '\' could not be splitted into street name and house number');
                }
                else if ($result === false)
                {
            throw new Exception('Error occured while trying to split address \'' . $address . '\'');
                }
                if (!empty($matches['A_Street_name']))
                {
            return array(
                                'additionToAddress1' => $matches['A_Addition_to_address_1'],
                                'streetName' => $matches['A_Street_name'],
                                'houseNumber' => $matches['A_House_number'],
                                'additionToAddress2' => $matches['A_Addition_to_address_2']
            );
                }
                else
                {
            return array(
                                'additionToAddress1' => $matches['B_Addition_to_address_1'],
                                'streetName' => $matches['B_Street_name'],
                                'houseNumber' => $matches['B_House_number'],
                                'additionToAddress2' => $matches['B_Addition_to_address_2']
            );
                }
        }
}
```

You can use a line like the following to test the address splitter:

```
var_dump(AddressSplittingService::splitAddress('Pallaswiesenstr. 57 App. 235'));
```

The output of this command is:

```php
array(4) {
  ["additionToAddress1"]=>
  string(0) ""
  ["streetName"]=>
  string(16) "Pallaswiesenstr."
  ["houseNumber"]=>
  string(2) "57"
  ["additionToAddress2"]=>
  string(8) "App. 235"
}
```

In our concrete use case with the <a href="http://store.shopware.com/viison00656/dhl-adapter.html" target="_blank">DHL Adapter</a> and <a href="http://www.pickware.de" target="_blank">Pickware</a>, one additional address line can be displayed on the shipping label. We decided to join all available additional address information (separated by commata) and to use these as the additional address line. This makes sure that all available information shows up on the label.

In practice, the shipping process with Shopware 5 works as follows:

1\. During checkout, the user can enter an arbitrary address line, e.g. “Wiesentcenter, Bayreuther Str. 108, 2. Stock” as part of his shipping address.
![image](/blog/img/shopware-5-from-a-technical-point-of-view_5.png)

2\. The address gets split up automatically when creating a shipping label with the <a href="http://store.shopware.com/viison00656/dhl-adapter.html" target="_blank">DHL Adapter</a>.
![image](/blog/img/shopware-5-from-a-technical-point-of-view_6.png)

3\. The shipping label that is created for this order contains all information that was given during checkout:
![image](/blog/img/shopware-5-from-a-technical-point-of-view_7.png)

## Conclusion

Shopware 5 comes with a bunch of new features that make it worth updating. With its new responsive template, Shopware now per default comes with a frontend that is optimized for mobile devices. For developers, it is definitely worthwhile to check out the new SearchBundle classes that take care of article filtering and sorting and are designed to be easily customizable by plugins. As we have seen in our example, these new classes can massively simplify plugin development.

In the area of internationalization, Shopware now supports multiple address lines. This makes it usable in all countries, no matter how odd the address format might be. We presented a robust solution to extract the street name and house number from a given address, if they are required separately for a specific application.

You can download the latest Shopware 5 version here. At the time of writing, the newest available Shopware 5 version was 5.0.0 RC1.

## About VIISON
The <a href="http://www.viison.com/">VIISON GmbH</a> is a young startup from Darmstadt with a talented team of web- and mobile developers. Creating innovative and sustainable solutions in close collaboration with our customers is our passion.

As Shopware Business Partner we already count over 600 satisfied shop operators using one of our Shopware plugins (for example our <a href="http://store.shopware.com/viison00656/dhl-adapter.html">DHL</a> or <a href="http://store.shopware.com/viison01622/ups-adapter.html">UPS Adapters</a>). On top of that, we proudly offer <a href="http://www.pickware.de/">Pickware</a>, a commissioning and inventory management system developed especially for Shopware. Being seamlessly integrated in the Shopware environment, Pickware maps all relevant processes in mobile apps and thereby achieves a significant improvement of productivity.
