---
layout: default
title: Configuration
github_link: search/configuration.md
indexed: true
menu_title: Configuration
group: Shopware Enterprise
subgroup: Enterprise Search
menu_order: 3
---

Shopware Enterprise Search has comprehensive configuration options to configure the search as needed.

<div class="toc-list"></div>

## Basic settings
The basic settings allow some general configuration of the search:

* Highlight search terms: Will highlight the search term in the search result
* Find products for the first suggestion: In addition to products matching the actual search term, SES will also show
products matching the first suggestion for the search term. So even if SES cannot find products for the search term
based on the search configuration, it will still be able to find products for a similar search term. This makes it less
likely that no products can be found.
* Content search - Number of results on search result page: Configure the number of search results per content type
* Content search - Number of results for ajax search: Configure the number of search results for the quick search
* Min score value for ajax and content search

## Synonyms
Synonyms allow you to configure search terms which are considered to be synonym or should match the same result set,
e.g. "shoe" and "sneaker". Synonyms are managed in synonym groups, where all search terms in a synonym group are considered
synonyms.

Additionally you can limit synonyms to certain shops and add a shopping world to the search result page of search terms
belonging to that synonym group. That way you can have your "shoe shopping world" appear on searches for "shoe" as well
as "sneaker" and "boot".

## Relevance
Relevance defines the fields ElasticSearch will consider during a search as well as the relevance the have. Additionaly
you can modify how ElasticSearch handles those fields.

* Name: A meaningful name for the relevance definition, e.g. "rank exact matches higher"
* Relevance: The boost ElasticSearch will apply to products matching the search term in the given field (1-100)
* Field: The field this definition applies for, e.g. "name" for the product name. Most of a product's fields are available
here, some might even appear multiple times, e.g. `name`, `name.german_analyzer` and `name.raw`. The suffix indicates,
how ElasticSearch handles the field: `name` might have some special characters removed, `name.german_analyzer` will
apply some optimizations for german language, `name.raw` does not have any optimizations. In addition to that
there are special fields you should consider: `attributes.enterprise.product_suggestion` will split compound words
such as "skyscraper" into separate words such as "sky" and "scraper". This is especially useful for language like e.g. german.
Also there is `attributes.enterprise.product_suggestion_compound` which will search the ngram index and also match "skyscraper"
for searches like "ysc". As this might massively lower the quality of the suggestions, we usually recommend to not use
this field, if it can be avoided.
* Fuzzyness: Number of letters that will be ignored / corrected by the search:
    * `Auto`: Automatically determine the value depending on the length of the search string. Usually: 0 for 1-2 characters, 1
 for 3-5 characters and 2 for anything longer then that.
    * `None`: No correction of letters
    * `One`: One letter will be corrected
    * `Two`: Two letters are corrected
    * `Three`: Three letters are corrected
* Operator: Defined how the search term is compared to the field: `AND` will only match, if all search terms occur in the field,
`OR` matches, once one search term can be found in the field. `AND` is less likely - and therefor usually will get a higher
boosting than `OR`. E.g. By defining a higher rating for `AND`, a customer searching for "sky scraper" will first see
"sky scraper" and then "skyway" or "whisky".
* Type: Defines what is considered a match. `Default` will apply the default mechanisms of ElasticSearch, `phrase` will
force a whole word to match. `Phrase prefix` will allow to match a word, even if just the beginning matches, e.g. "skyscraper" for "sky".
* Maximum expansion: Only available for `phrase prefix`. Defines how many letters may follow after the beginning of the
word and still are considered a match. Searching "sky" will only match "skyscraper", if maximum expansion is at least 7
(the number of letters in "scraper").

Generally you will always need multiple relevance definitions for several fields. Think of the relevance definitions as
usage scenarios:

* If a word matches multiple terms in the name, rank it highest: `field: name, operator: and, relevance: 100`
* If one word matches exactly, rank it high: `field: name, operator: or, relevance: 90`
* Match medium, if part of a compound word matches: `field: attributes.enterprise.product_suggestion, relevance: 50`
* Match medium, if manufacturer matches partially: `field: manufacturer.name, operator: or, relevance: 50, type: phrase_prefix`

Usually this is the most relevant part when setting SES for a shop: Usually customers want both: Very precise and good
rankings for products matching the term properly as well as fuzzy matches for less relevant searches.


## Boost
Boost behaves similar to the relevance configuration. They allow to define additional boosts for products which meet
certain criteria. This way you can configure, that a product is especially interesting, if it is e.g. a topsellet product.

* Boosting name: Meaningful name for the boost definition, e.g. "boost topseller products"
* Relevance value: The value of the boosting (1-100)
* Table field: The boost will only apply, if the defined field of the product is `true`. E.g. `isTopSeller` or `isNew`.

By default SES will only allow you to choose from boolean product and attributes fields, as these can easily be checked
for their state. In future versions this might be extended to also support arbitrary fields and apply rules on them, such
as `product.release_date < 2017/02/03`. Feel free to give us feedback, if this might be relevant for you.

