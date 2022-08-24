---
layout: default
title: AND / OR search
github_link: search/developer/and_or.md
indexed: true
menu_title: AND / OR
group: Shopware Enterprise
subgroup: Enterprise Search
subsubgroup: Developer
menu_order: 4
---

AND / OR searches are two typical search configurations: Given a search with multiple words (e.g. "samsung screen") an AND
search will only find products which matches both teams. An OR search, however, will find all products which match
at least one of these terms.
In practice an AND search is useful to find high quality matches: A customer searching for "samsung screen" does not
want to find all screens and all products from Samsung: He wants to find all screens from the brand Samsung. OR searches
on the other hand might be useful for searches such as "display monitor" where people want to find all products matching
one of the terms.

## AND / OR searches in SES
The default configuration of SES includes both: Given a search like "samsung screen" SES will give products matching both
terms (AND) a relevance of 100. Products matching only one of the terms (OR) will get a relevance of 80. This way you
get best of both worlds: High quality matches will always be ranked first, less quality matches can still be found.

## Changing the configuration
In the following examples two products "chair" and "clock" have been defined.
### OR
In the SES backend module only one rule is defined. The `operator` configuration is set to "OR":

<img src="{{ site.url }}/assets/img/search/and_or/or_config.png" style="width: 60%"/>

Searching in the frontend for "clock chair" both products can be found.

<img src="{{ site.url }}/assets/img/search/and_or/or_result.png" style="width: 60%"/>

### AND
Now only an AND rule is configured by setting the `operator` configuration to "AND":

<img src="{{ site.url }}/assets/img/search/and_or/and_config.png" style="width: 60%"/>

Now the search will not return any results, as there are no products with both words in the name:

<img src="{{ site.url }}/assets/img/search/and_or/and_result.png" style="width: 60%"/>

If there was a product such as "clock with chair motive" it would be found.

### Recommendation
As described above, pure AND / OR searches for products can easily be configured in SES. In most cases, however,
the combined approach is more useful: It makes sure that AND matches are preferred while OR matches can still be found
as fallback.
Also notice that AND rules will only apply per field: So the "samsung screen" example will only apply as long as both terms are part
of the same field (e.g. the name). An AND search over multiple fields could be implemented, however, by decorating
`\SwagEnterpriseSearch\Bundle\SearchBundleES\SearchQueryBuilder::buildQuery` where the actual search queries are created.
Also you could index the both fields in question as one field and then configure it from the backend module.

### Other entities
Content related search results (e.g. blog, categories, shopping worlds etc) are implemented using the `completion_suggestion`
functionality of ElasticSearch and cannot be configured from the backend module. The relevant logic for these search types can
be found in `\SwagEnterpriseSearch\Bundle\SearchBundle\SuggestionFacet` as well as `\SwagEnterpriseSearch\Bundle\SearchBundleES\SuggestionHandler`.


