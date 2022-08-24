---
layout: default
title: Overview
github_link: search/developer/overview.md
indexed: true
menu_title: Overview
group: Shopware Enterprise
subgroup: Enterprise Search
subsubgroup: Developer
menu_order: 1
---

This document will present the big picture of how data is indexed and searched in SES.

<div class="toc-list"></div>


## Bundle Structure
SES replicates the Shopware Bundle structure to a certain level:

 * `ESIndexingBundle`: Services for indexing data
 * `SearchBundle`: General components which are not necessarily bound to ElasticSearch such as `Facet` and `FacetResult` objects
 * `SearchBundleES`: Components specific to ES, such as `FacetHandler`

Services specific to SES can be found in `EnterpriseSearchBundle`:

 * `AlternativeTerm`: Performs an additional ES search in order to find alternative search terms for the given search term
 * `Dictionary`: Provides language specific dictionaries used during indexing
 * `Explain`: Enables the ES "Explain" functionality for the preview search in Shopware's backend
 * `HistoryBoosting`: Defines the amount of boost for certain fields
 * `ImportExport`: Import/Export functionality for settings
 * `SearchConfig`: Representation of the backend search configuration (e.g. relevance, boosting…)
 * `Session`: Session Wrapper for the Shopware Session which is easier to inject / test
 * `SynonymSearch`: Perform searches for synonyms


## Indexing data
Indexing describes the process of making data from Shopware available in ElasticSearch and keeping it up to date.

### Indexing components
SES adds various content pages to the Shopware search. For that reason, it provides additional indexer for blogs,
shopping worlds, categories, static pages, manufacturers and synonyms. All of these services can be found in
`SwagEnterpriseSearch\Bundle\ESIndexingBundle`. The main entry point of each of these components is the so called `DataIndexer`,
which is registered in the DI container with the tag `shopware_elastic_search.data_indexer`. It will either index all entities
of a given type (e.g. blogs) in the `populate` method for full updates or just index certain entities of a given type in
the `index` method for partial updates.

Usually every `DataIndexer` will have a method called `createQuery` which reads all affected IDs for the full index. The
`Provider` service is then used to read the actual data for that entity, e.g. "name", "author" and "content" for a blog.
At this point every `Provider` needs to make sure, that all relevant information for the frontend are indexed into ElasticSearch,
so that no additional queries are needed in the frontend in order to fetch e.g. URLs, images etc.

Also every component has a `Mapping` service registered to the DI tag `shopware_elastic_search.mapping`. It provides
the ElasticSearch mapping data, such as "id is an integer" or "description is an english text field".

The so called `SuggestionBuilder` of each component is responsible for providing the search terms each entity will match
to and also provides the suggestions being shown as "search term suggestions" of the ajax search. Usually the `SuggestionBuilder`
will make use of the `SuggestionStringExploder` service, which will split compound words into individual words [based
on dictionaries](/search/developer/compound_words/).


### How indexing is triggered
By default Shopware provides commands such as `sw:es:index:populate` (full index) and `sw:es:backlog:sync` (partial update
usually run by a cronjob). These will automatically apply for SES as well. Full indexes are handled by the `populate` method
of the `DataIndexer` services, partial updates are handled by the `synchronize` method of the `Synchronizer` services.
Shopware will pass all current indexing backlog entries to these services which will then extract the IDs of those entries,
which are handled by the current services. So the `BlogSynchronizer` will only handle backlogs of the type `blog`. The
extracted IDs will then be passed to the `index` method of the `DataIndexer`.

In order to recognize which entities needs to be re-indexed, the class `SwagEnterpriseSearch\Subscriber\ORMBacklogSubscriber`
will register to all lifecycle events of the handled content types (blogs, categories etc) and write the corresponding
backlog entries for those.

### Immediate Indexing
If immediate indexing is enabled, SES will index changed entities right away
and not wait for a cronjob to run.

```php
return [
    'db' => [...],
    'es' => [
        'immediate_index' => true;
        ...
    ],
];    
```

## Search
The following section describes, how SES extends Shopware in order to make the content search available and how
SES applies the search configuration.

### General pattern
All additional information provided by SES (such as suggestions and content pages) are added by the `SwagEnterpriseSearch\Bundle\SearchBundle\SuggestionFacet`
and its handler `SwagEnterpriseSearch\Bundle\SearchBundleES\SuggestionHandler`. The handler adds
the corresponding suggestions queries to the main ElasticSearch query in the `handle` method and hydrates the
results into `SwagEnterpriseSearch\Bundle\SearchBundle\SuggestionFacetResult`. For that reason `SuggestionFacetResult`
contains all non-product search results such as search suggestions, blogs, manufacturers, categories, shopping worlds
and static pages.

The backend configuration of the search (such as relevance fields and boostings) are applied by `SwagEnterpriseSearch\Bundle\SearchBundleES\SearchQueryBuilder`.
This service decorates the default `shopware_search_es.search_term_query_builder` service and also adds the "auto suggest"
and the "history boosting" functionality.

So roughly speaking the `SuggestionFacet` and its handler are responsible for the suggest search (including content suggestions),
the `SearchQueryBuilder` is responsible for extending the default product search by the SES features and configurations.

### Ajax search
The main entry point of the ajax search is `SwagEnterpriseSearch/Controllers/Widgets/Suggest.php`. It triggers
a search using `Shopware\Bundle\SearchBundle\ProductNumberSearchInterface::search` after it added the `SuggestionFacet`
to the `Condition` object. The rest is handled by `SearchQueryBuilder` and `SuggestionHandler` as described above.
As quick responses are key for a "search as you type" functionality, the ajax search disables the template engine and prints
out a JSON representation of the search directly. The actual rendering of the results to the search overlay is performed
by the JavaScript stack.

### Search result page
The search result page generally operates by the same patterns: The `SuggestionFacet` is added by the
`SwagEnterpriseSearch\Bundle\SearchBundle\CriteriaRequestHandler`, so all content hits are also available by the `SuggestionFacetResult`.
Additionally it adds a `SwagEnterpriseSearch\Bundle\SearchBundle\SynonymFacet` to the `Criteria` object. The corresponding
`SwagEnterpriseSearch\Bundle\SearchBundleES\SynonymHandler` will then add a `SwagEnterpriseSearch\Bundle\SearchBundle\SynonymFacetResult`
to the result, if a matching synonym group for the current search term was found. The `SynonymFacetResult` is then used
to display shopping worlds or product streams for the current search, if configured.

You should notice, however, that SES replaces the default search controller in `SwagEnterpriseSearch/Controllers/Frontend/Search.php`.
This is needed, as configured SynonymGroups might replace the entire search result with a product stream or redirect the
user to another page, if `RedirectURL` was defined. For that reason, SES will perform a lookup for a matching SynonymGroup
before hands and only triggers the default search as described above, if no redirect and no product stream are configured
for the current SynonymGroup.


### Index time vs search time
Dealing with ElasticSearch there are usually concerns handled while indexing and concerns handled during the actual search.
Roughly speaking you rather want your indexing to be slow than your search. For that reason, compound words, ngrams and
synonyms are dealt with while indexing.
Concerns such as relevance, boosting, auto suggest and history boosting are applied while searching as described above.
