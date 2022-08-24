---
layout: default
title: Features
github_link: search/features.md
indexed: true
menu_title: Features
group: Shopware Enterprise
subgroup: Enterprise Search
menu_order: 1
---

Overview of the feature set of Shopware Enterprise Search (SES)

<div class="toc-list"></div>

## Content search
SES does not only searches the default product catalog: It will also search content pages such as:

* Shopping worlds
* Static pages
* Manufacturers
* Categories
* Blog postings

Content pages matching the user's search term will be shown in the quick search as well as on the search result page.

<img src="{{ site.url }}/assets/img/search/overlay.png" style="width: 60%"/>

## Performance
SES makes use of ElasticSearch - a well known high performing, scalable search engine. For that
reason Shopware Enterprise Search will search million of products in short time. This will take load from your database
and therefore improve the scalability of your overall server setup.

The good performance of Shopware Enterprise Search especially can be seen in our quick search functionality: Every key
stroke will show the users better suggestions, content pages and products for his search - with almost no impact to
the database.

## Behavioural boosting
Behavioural boosting will track the customer journey and boost products depending on the customer's behaviour.
Viewing a product, browsing a category or filtering for manufacturers or properties will in future boost products,
which matches these criteria. So if a customer filtered for t-shirts in "XL" and "red", products matching these criteria
will get an improved ranking.


## Synonyms
Synonyms allow you to define groups of words, which are considered to be synonym: So searching for "car" will also find
products which are only optimized for "vehicle".

<img src="{{ site.url }}/assets/img/search/synonyms.png" style="width: 60%"/>

## Shopping worlds
With SES you can define shopping worlds for certain searches. Therefore you are able to prepare high quality search
result pages in order to optimize conversion for certain parts of your catalogue.

<img src="{{ site.url }}/assets/img/search/serp.png" style="width: 60%"/>

## Understanding your words
In addition to fuzzy searches (searches being tolerant to typos to a certain degree) SES is also able to split compound
words into individual words, which is especially relevant for german languages. Imagine a product called "skyscraper":
SES "understands", that this word is compound from "sky" and "scraper" - and will find it without falling back to fuzzy
mechanisms. This massively improves the quality of your search results in comparison to mechanisms such as
[n-gram](https://www.elastic.co/guide/en/elasticsearch/reference/current/analysis-ngram-tokenizer.html).

## Helps you optimizing your searches
The preview functionality will not only allow you to quickly check certain searches - it also helps you to understand,
why certain results are ranked higher than others.

<img src="{{ site.url }}/assets/img/search/preview.png" style="width: 60%"/>

## Highly configurable
With SES the search can be configured for the customer's needs. Highlighting of search terms and number of results
per content type can be configured, of course. Also the minimum ranking value can be determined.

<img src="{{ site.url }}/assets/img/search/settings.png" style="width: 60%"/>

A suggestion blacklist and the minimum word length for suggestions can be configured in the indexing settings tab.

<img src="{{ site.url }}/assets/img/search/indexing-settings.png" style="width: 60%"/>


### Relevance
Additionally you can define the relevance of each
product's field individually and specify fuziness, search operators and type of search to be performed. So a product
matching a search term perfectly will get a better ranking than a product where the search term needs to substitute a letter.
Products matching multiple search terms will get a better ranking than products matching just a few words. And a product
where the search term matches manufacturer as well as product name will get a better ranking than products only matching
one of both.

<img src="{{ site.url }}/assets/img/search/relevance.png" style="width: 60%"/>


### Boosting
Usually you want to boost certain groups of items. In addition to the relevance system, you can boost items matching
certain criteria, e.g. topseller items, items from certain manufacturers or categories etc. Of course custom attributes / free
text fields are also supported.

<img src="{{ site.url }}/assets/img/search/boosting_detail.png" style="width: 60%"/>

Boosting rules can even be nested, so that you are able to create boosting rules such as:

```
IF
    product.manufacturer = Samsung
    AND
    product.name CONTAINS "Smartphone"
OR
    product.highlight = TRUE
    OR
        product.attr17 = promote
        AND
        product.inStock > 100
```

Furthermore each boosting can be applied for certain period of times, so that you are able to create boostings
for e.g. black friday promotions beforehand and only have them active from friday to saturday evening, for example.

### Per-entity optimization
In addition to that, any searchable content (products, shopping worlds, static pages, categories etc) can be optimized
individually: So you can define, that your product "red glove" will get an especially good ranking for "glove", e.g. for
sale. On the other hand you can also exclude content individually - for example, if you are having a sales promotion on
"red glove" and don't want other gloves to show up in the search.

<img src="{{ site.url }}/assets/img/search/per-entity.png" style="width: 60%"/>

### Profiles
Profiles help you to organize sets of configuration: You can have different profiles for different shops or even prepare
configurations for certain promotions and activate them with just a single mouse click.

<img src="{{ site.url }}/assets/img/search/profiles.png" style="width: 60%"/>
