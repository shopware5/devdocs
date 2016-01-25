---
title: Graph based recommendation engine for Shopware
tags:
    - graph database
    - neo4j
    - recommendation engine

categories:
- dev
indexed: true
github_link: blog/_posts/2015-10-14-graph-based-reco-engine.md

authors: [dn]
---
At the end of September, [Benjamin](https://developers.shopware.com/blog/authors/bc/) and I visited the
[Bulgaria PHP conference](http://www.bgphp.org/).
We saw a very interesting talk by [Mariusz Gil](https://twitter.com/mariuszgil/) called
[Discovering graph structures](https://joind.in/talk/view/14886), which gave a quick
introduction to graph databases and especially [Neo4j](neo4j.com).

Over a long weekend I decided that trying to implement a recommendation engine
for Shopware based on Neo4j might be a good start to get into Neo4j and graph
databases in general. In this blog post I will discuss the graph database,
Shopware's current way of handling recommendations and a simple plugin to implement
a recommendation engine using neo4j in Shopware.

 <div class="toc-list" data-depth="4" data-headline=""></div>

## What is it all about?
<img src="/blog/img/graph_simple_result.png" alt="Simple graph" class="is-float-left" style="width:300px" />
A graph database is a database which represents information in form of graphs. It's ideal for highly cross-linked
data which needs to be queried in a semantic way.
A typical graph database will have **nodes**, **edges** and **properties** to represent data. The **nodes** are entities
like e.g. customer and items / products.
**Properties** are additional information stored for the nodes, e.g. *name* and *address* of a person or the *price*
of an item.
**Edges** represent the relationship between nodes, so they will bring semantic information into the system. Having
*customer* and *items* as nodes, one might have *edges* like "purchased". This way one could design a graph with all
*items* that all *customers* purchased.

Graph databases are popular everywhere meaningful information needs to be extracted from an information
network. Popular examples are recommendation engines for e.g. products, movies or even friends in social networks.
Even though it is possible to represent such structures in relational databases, graph databases are actually made
for this kind of requirement and might be preferable in regards of performance and maintainability.

The graph above shows a simple customer / item graph with three customers (blue) having purchased various items (green).
As you can see, all customers purchased the "ESD download" item, so other items could be suggested to the customers
 based on this common preference.

## Neo4j
<img src="/blog/img/graph_tom_hanks.png" alt="Tom hanks movies + directors" class="is-float-right"  style="max-width:350px" />

Neo4j is a very popular open source graph database written in Java. You can simply download the community edition
at [http://neo4j.com/download/](http://neo4j.com/download/), unzip the archive and run `bin/neo4j start` in order
to run the neo4j server locally.
Afterwards you can navigate your browser to `http://localhost:7474/` and play around with the tutorials a bit.
I highly recommend the movie graph tutorial, as it shows some basic concepts and allows to learn the query language
called `cypher` easily.

So there is a query, for example, to show all movies with Tom Hanks and the directory of each of those movies (see image
on the right).

Other examples show how to find all co-actors of Tom Hanks or the shortest relationship path between e.g. Tom Hanks and
Kevin Bacon (also known as [bacon path](https://en.wikipedia.org/wiki/Six_Degrees_of_Kevin_Bacon)). So playing around with the movie data
demo set gives quite a good impression of what is possible with a graph database.

### The cypher query language
Cypher is a declarative query language for neo4j. It focuses on being human readable and allowing people to express *which*
information they want to read, not *how* the information can be found.

Typical keywords are `MATCH`, `WHERE` and `RETURN`: `MATCH` will usually describe a (sub)graph in the neo4j database,
`WHERE` adds some additional conditions to the information being queried and `RETURN` defines the data you actually
want to read.

A typical query might look like this:

```
MATCH (currentCustomer:Customer {name: "Peter"})-[:purchased]->(purchasedItems:Item)
RETURN purchasedItems
```

Re-formatting the query a bit makes understanding it even easier:

```
MATCH
    (currentCustomer:Customer {name: "Peter"})
    -[:purchased]->
    (purchasedItems:Item)
RETURN purchasedItems
```

First of all there is the `MATCH` statement, which describes the relation we want to find:

* find a node of type `Customer` with `name="Peter"` and assign the alias `currentCustomer`
* from this node find all edges / relations of type `purchased`
* the edge should point to another node of type `Item` with the alias `purchasedItems`
* return all `purchasedItems`

The statement `-[:purchased]->` is kind of a ASCII representation of the relation you want to describe, so its
very easy to read and to understand. The `MATCH` query does not need to have three parts, you can extend it to
you needs:

```
MATCH
    (currentCustomer:Customer {name: "Peter"})
    -[:purchased]->
    (purchasedItems:Item)
    <-[:purchased]-
    (otherCustomer:Customer)
RETURN otherCustomer
```

In this case we added another edge `purchased` and another node `Customer` called `otherCustomer`. The arrow now points
into the other direction, so that it goes from `otherCustomer` towards `purchasedItems`, but there are also bidirectional
edges, if that better fits your needs.

### More on Cypher & Co
Of course this is only a small part of the functionality of the cypher query language; it also supports updating and
deleting graphs / nodes / edges, importing CSV, prepared statements and various functions. I highly recommend that you read the
[neo4j documentation](http://neo4j.com/docs/stable/cypher-query-lang.html) which provides some good examples and explanations,
and even an interactive query editor / graph browser.

Additionally there are [ebooks on the neo4j homepage](http://neo4j.com/books/) you can download by leaving your contact data.
They will give a deeper insight of graph databases in general and ways to design graphs properly.


### PHP integration
Neo4j has a REST API, which makes it is easy to communicate with the server. Of course in most cases you don't want to take
care of that yourself; information about some PHP integrations can also be found in the
[PHP section](http://neo4j.com/developer/php/) of the neo4j wiki. The most popular libraries seem to be
[neo4j php](https://github.com/jadell/neo4jphp) by Josh Adell and [Neo4j PHP OGM](https://github.com/lphuberdeau/Neo4j-PHP-OGM)
by Louis-Philippe Huberdeau. The first one is a simple OOP library to create cypher queries, the latter is a
Doctrine2 style OGM (object graph mapper) which is built on top of the neo4jphp library.


## The current situation in Shopware
Currently, the so called "Marketing aggregate" components of Shopware take care
of parts of the recommendation engine. They make use of the PHP /MySQL stack
only, so that there are no additional dependencies for this kind of recommendation engine.

### The general concept
Generally the "also bought" functionality is based on these components:

* The table `s_articles_also_bought_ro` which is a denormalized representation
of the "also bought" marketing information. This is basically a ManyToMany
mapping for every item in the shop: It indicates which item was purchased how
often and with which other item
![](/blog/img/graph_also_bought_table.png)
* The `\Shopware_Components_AlsoBought` component takes care of refreshing
this table. If the whole "also bought" table needs to be rebuilt, it will call
the `\Shopware_Components_AlsoBought::initAlsoBought` method. Usually this is not
necessary, as the `\Shopware_Components_AlsoBought::refreshMultipleBoughtArticles` method
will allow Shopware to update the "also bought" table on a per-order base.

### The SQL side
Generally this form of denormalization is used as the kind of queries needed to get this
information in a relational database is quite complex.

The "also bought" query in Shopware might look like this:

```
SELECT
    detail1.articleID as article_id,
    detail2.articleID as related_article_id,
    COUNT(detail2.articleID) as sales
FROM s_order_details detail1
   INNER JOIN s_order_details detail2
      ON detail1.orderID = detail2.orderID
      AND detail1.articleID != detail2.articleID
      AND detail1.modus = 0
      AND detail2.modus = 0
      AND detail2.articleID > 0
      AND detail1.articleID = :articleId
GROUP BY detail2.articleID
```

As you can see, it takes a given item `articleId`, reads all orders with it
and joins all other items of the very same order. After a `GROUP BY` on
the "also bought" items, it can aggregate the total sum of the "also bought"
value for each other item using `COUNT(detail2.articleID) as sales`.

This works quite well and gives us the information needed - but it does
not scale well enough for it to be used live on a per request base:

![](/blog/img/graph_explain.png)

As you can see here, the relevant query might produce a temporary table and a
filesort - so it is not a good idea to run it on every request on the detail page.
Instead, we are currently running this query on a per order base:

![](/blog/img/graph_basket_also_bought.png)

This will just fetch the "pairs" for the "also bought" information and then
increase the `sales` field in the `s_articles_also_bought_ro` table. This
query also has a much smaller database footprint, so we don't have the
performance issue we'd have if we used the first query shown above.

### Round up
The "also bought" functionality shows that getting this kind of recommendation
information is possible in SQL - but it takes quite some effort to do it in
a proper way, and it doesn't allow the kind of flexibility you might be used to
from using a graph database.


## Writing a recommendation plugin
Below I will discuss a simple recommendation plugin which you can find at [my github repo](https://github.com/dnoegel/DsnRecommendation).

The plugin must be installed by checking it out to `engine/Shopware/Plugins/Local/Core/DsnRecommendation`. After that,
the composer dependencies can be installed running `composer install` from the plugin directory. Configure your neo4j
server in your shop's `config.php` file:

```
'neo4j' => array(
    'host' => 'localhost',
    'user' => 'neo4j',
    'pass' => 'shopware',
    'port' => ''
)
```

Now you can install and activate the plugin using the shopware command line tools:

```
./bin/console sw:plugin:refresh
./bin/console sw:plugin:install --activate DsnRecommendation
```

### Creating some demo data
<img src="/blog/img/graph_outdoor.png" alt="Simple graph" class="is-float-right" style="width:400px" />

First of all, the plugin provides some demo data that can be used to test various recommendation scenarios. It will
create some items, customers and orders. The items are separated into groups like "console games", "outdoor games" and
"board and card games", so we can test different customers with different preferences.

The demo data generation can be triggered by running `./bin/console dsn:recommendation:demo`. Afterwards, the shop
should have a new category "Gaming" with the subcategories "digital", "analog" and "outdoor":

Technically the demo data is generated by the `DemoData` components that can be found in the folder
`DsnRecommendation/Components/DemoData` of the plugin.

### Exporting orders to Neo4j
The plugin requires an initial export of the order data to Neo4j: `./bin/console dsn:neo4j:export`
Afterwards the plugin will automatically sync new orders to the graph database.

Technically this command will export the orders as CSV by using `\Shopware\Plugins\DsnRecommendation\Components\CsvExporter`.
Then `\Shopware\Plugins\DsnRecommendation\Components\Neo\BulkExporter` will let neo4j import the CSV running this cypher
query:

```
USING PERIODIC COMMIT

LOAD CSV WITH HEADERS FROM "$url" AS row
MERGE (customer:Customer { id: row.userId, name:row.userName})
MERGE (item:Item { id:row.itemId, name:row.item })
CREATE UNIQUE (customer)-[:purchased]->(item);
```

So it will basically iterate the CSV and create `Customer` and `Item` nodes and create edges to link a given
customer to the items he purchased.

### Having a look at the resulting graph
<img src="/blog/img/graph_neo.png" alt="Resulting graph graph" class="is-float-left" style="width:300px" />
Now you can navigate to your neo4j frontend, typically `http://localhost:7474/browser/`. In the query window at the top
cypher queries can be entered. The query `MATCH (n) RETURN n LIMIT 100`, for example, will print the whole graph:

This graphs shows four "purchasing group". The group at the bottom left is Shopware's demo data.
The other three "purchase groups" are the demo data of this plugin: Each of these groups simulates buying behaviour
for another topic, e.g. "card and board games", "computer games" and "outdoor games". The groups would be linked in
reality, but for the purpose of debugging and experimenting around, smaller, separated groups seem to be more useful.
<br>
<br>
#### Getting recommendations for a given customer
Now let's see how to give recommendations for a specific customer based on his overall buying behaviour:

```
// find customer who ordered same items
MATCH (u:Customer)-[r1:purchased]->(p:Item)<-[r2:purchased]-(u2:Customer),
// find items of those customers
(u2:Customer)-[:purchased]->(p2:Item)
// only for this user
WHERE u.name = "Felix Frechmann"
// make sure, that the current user  didn't order that product, yet
AND not (u)-[:purchased]->(p2:Item)
// count / group by u2, so every user-path only counts once
RETURN p2.name, count(DISTINCT u2) as frequency
ORDER BY frequency DESC
```

![](/blog/img/graph_result.png)

Looking at the graph example above, you will see that this query recommends "outdoor game: water fight" and
"outdoor game: soccer" to the customer "Felix Frechmann". The reason is basically the following:

* Felix bought "rope jumping" and "golf pro"
* So did Kathrin and Max
* Kathrin and Max also bought the game "water fight"
* Kathrin bought the game "soccer"
So basically there are two paths to "water fight" and one path to "soccer" for Felix. For this reason, the
"water fight" recommendation is higher ranked than the "soccer" recommendation.

Currently the `frequency` is calculated by the number of customers who bought the same product as Felix. This could
be changed to also take the number of similar items into account: `count(u2) as frequency`
With this modification, the "water fight" game would get a frequency of 4, as every actual path is taken into account.
Both approaches could be combined by having the frequency calculated like this:

`count(u2) * count(DISTINCT u2) as frequency`

Now the "water fight" game becomes a frequency of 8, the "soccer" game a frequency of 2 - which might reflect the number
of customers and the number of common items even better.

#### Getting recommendations for a given product
Another example demonstrates how we can make a general recommendation based on a given product (without knowing the current
customer):
```
MATCH (originalItem:Item {name: 'console game: Racing 2000'})<-[:purchased]-(otherCustomer:Customer)-[:purchased]->(alsoBought:Item)
WHERE alsoBought <> originalItem
RETURN alsoBought.itemId, alsoBought.name, count(alsoBought) as frequency
ORDER BY frequency DESC
LIMIT 10
```

This query will basically start from a given item ("console game: Racing 2000") and find customers who purchased it. Then, for these customers, all purchased items which are not identical to the original item will be found.
Finally the `id` and `name` of the `alsoBought` items are returned together with the `frequency` of purchases.
The result will be ordered by `frequency`, so that the most purchased item will be returned first.

The result will look like this:

![](/blog/img/graph_result_item_reco.png)

### Implement recommendation queries in Shopware
The following sections will briefly discuss how the plugin works.

#### Syncing new orders to neo4j
All existing orders were already exported to neo4j using the export console command of this plugin. Now we just need
to make sure that new orders will also be synchronized to the graph database.
To do so, we add a little order subscriber like this:

```
class Order implements \Enlight\Event\SubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return array(
            'Shopware_Modules_Order_SaveOrder_ProcessDetails' => 'onSaveOrder'
        );
    }

    public function onSaveOrder(\Enlight_Event_EventArgs $args)
    {
        /** @var \sOrder $order */
        $order = $args->get('subject');
        $details = $args->get('details');
        $userData = $order->sUserData;

        $userName = $userData['billingaddress']['firstname'] . ' ' . $userData['billingaddress']['lastname'];
        $userId = $userData['billingaddress']['userID'];
        $items = array_map(function ($item) {
            return [
                'id' => $item['id'],
                'name' => $item['articlename']
            ];
        }, array_filter($details, function ($item) {
            return $item['modus'] == 0;
        }));

        Shopware()->Container()->get('dsn_recommendation.sync_service')->sync($userId, $userName, $items);
    }
}
```

This subscriber will be notified whenever a new order is processed. In the callback method, the user data (id and name)
as well as the product information (id and name) are read and passed to the `\Shopware\Plugins\DsnRecommendation\Components\Neo\SyncOrder`
service.

This service will basically create queries like the following and send them to the neo4j server:

```
MERGE (customer:Customer { userId: '224', name:'Jimmy Jellyfish'})
MERGE (item:Item { itemId:'670', name:'Sonnenbrille "Red"' })
CREATE UNIQUE (customer)-[:purchased]->(item);
```

So all new ordered items will be created / updated on the server and the current customers get a "purchased" reference to them.

#### Showing recommendations
In order to show the recommendations to the customer, we will need

* a post dispatch event on the detail page
* a recommendation service that will fetch the recommendations for a given item
* a template extension

The post dispatch is registered using an own `Recommendation` subscriber, which registers to the
`Enlight_Controller_Action_PostDispatchSecure_Frontend_Detail` event, so that it is notified after
any call to a detail page:

```
class Recommendation implements \Enlight\Event\SubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return array(
            'Enlight_Controller_Action_PostDispatchSecure_Frontend_Detail' => 'onPostDispatchDetail'
        );
    }

    public function onPostDispatchDetail(\Enlight_Event_EventArgs $args)
    {
        /** @var \Enlight_View $view */
        $view = $args->get('subject')->View();
        $itemId = $view->getAssign('sArticle')['articleID'];

        $view->addTemplateDir(__DIR__ . '/../Views');

        $result = $this->getRecommendationItems($itemId);

        $view->assign('dsnHasRecommendations', $result);
    }

    private function getRecommendationItems($itemId)
    {
        /** @var \Shopware\Plugins\DsnRecommendation\Components\Neo\Recommendation $recommendationService */
        $recommendationService = Shopware()->Container()->get('dsn_recommendation.recommendation');
        $recommendations = $recommendationService->recommend($itemId);

        $result = [];
        foreach ($recommendations as $recommendation => $frequency) {
            if ($promotion = Shopware()->Modules()->Articles()->sGetPromotionById('fix', 0, $recommendation)) {
                $result[] = $promotion;
            }
        }
        return $result;
    }
}
```

As you can see, it will basically read the current `articleID` from the current page and pass it to the `getRecommendationItems` method. This will query the `Recommendation` service and load additional item info using the
`sGetPromotionById` method. This data is then returned and assigned to the `dsnHasRecommendations` template variable.

The `Recommendation` service itself does not implement much of logic:

```
class Recommendation
{
    /**
     * @var \Everyman\Neo4j\Client
     */
    private $client;

    public function __construct(\Everyman\Neo4j\Client $client)
    {
        $this->client = $client;
    }

    public function recommend($itemId)
    {
        $template = <<<EOD
            MATCH (originalItem:Item {itemId: {itemId}})<-[:purchased]-(otherCustomer:Customer)-[:purchased]->(alsoBought:Item)
            WHERE alsoBought <> originalItem
            RETURN alsoBought.itemId, count(alsoBought) as frequency
            ORDER BY frequency DESC
            LIMIT 10
EOD;


        $query = new Query($this->client, $template, ['itemId' => (string)$itemId]);

        $result = [];
        foreach ($query->getResultSet() as $row) {
            $result[$row['itemId']] = $row['frequency'];
        }

        return $result;

    }
}
```

It will basically use the cypher query discussed above to request recommended items for the given itemId. Those items
are then ordered by frequency and returned.

Finally there is a little template extension in `DsnRecommendation/Views/frontend/detail/index.tpl`, which will add
a new tab to the recommendation tabs, that will show our neo4j recommendations.

![](/blog/img/graph_result_reco.png)

### Round up
As you can see, the actual logic related to neo4j is quite simple. With 3 rather simple queries we accomplished
a *full export* to neo4j, an *update query for new orders* and the *recommendation call*.
Of course there are a lot of optimizations to talk about. So currently the recommendation query will take into account
*all* ordered items of other customers, not only items also bought in the *same* order. Additional checks for the subshop or even the customer group might be useful.

### Download
The [plugin is available on github](https://github.com/dnoegel/DsnRecommendation), install instructions can be found
above or in the github repository.
