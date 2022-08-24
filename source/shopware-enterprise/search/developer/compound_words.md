---
layout: default
title: Handling Compound Words
github_link: search/developer/compound_words.md
indexed: true
menu_title: Handling Compound Words
group: Shopware Enterprise
subgroup: Enterprise Search
subsubgroup: Developer
menu_order: 4
---

Compound words (such as "skyscraper") are words actually compound from two or more other words ("sky" and "scraper").
For searching these kind of words are quite relevant, as most search engines will easily be able to look up words
beginning with a string ("sky") but not with a string at other places (such as "scraper" in "skyscraper").

For SES there are several mechanisms in place to handle this kind of words.

<div class="toc-list"></div>

## Dictionaries
When indexing your product catalogue, SES will search for language dictionaries at several places. By default
it will handle dictionaries in `/usr/share/hunspell/` and `%PLUGIN_DIR%/Resources/assets/`. Furthermore
you are able to maintain custom dictionaries in `%PLUGIN_DIR%/Resources/assets/custom_dictionaries/`.

During the indexing process, SES will build the [ngram](https://en.wikipedia.org/wiki/N-gram) of each indexable field
and will compare each ngram with the dictionary. This will make indexing a bit slower - but makes sure that only high
quality search terms are indexed. With this mechanism, "skyscaper" can be decompound into "sky" and "scraper" and therefore
be indexed.

### Dictionary format
By default SES expects the dictionaries to be available in the three folders mentioned above. Each dictionary should
be named by the convention `%LANGUAGE_CODE%.dic`, for example `de_DE.dic`. Within the dictionary SES expects the words
to be line separated, for example:

```
sky
scraper
```

### Extending the dictionary
The default dictionaries SES considers are the dictionaries provided by hunspell. SES also comes with dictionaries
for de_DE and en_GB if hunspell is not available on your system. In order to extend these dictionaries or add custom
ones, you can just create the corresponding files in  `%PLUGIN_DIR%/Resources/assets/custom_dictionaries/`. SES will
handle those files as *additions* to the default dictionaries. For that reason there should be no need to modify
any other dictionary files in SES directly.

### Custom dictionary handler
If you want to provide dictionaries in another format, you can do so by implementing the interface `\SwagEnterpriseSearch\Bundle\EnterpriseSearchBundle\Dictionary\DictionaryInterface`.
Then just set your implementation into `\SwagEnterpriseSearch\Bundle\EnterpriseSearchBundle\Dictionary\DictionaryManager` using
`setDictionaries`. `DictionaryManager` will - for every given language key - search for an implementation supporting
the given language key. The first implementation matching, will be responsible for handling the language in question.

If you want your implementation to be considered before SES's default dictionary implementation, you should make it the
first one in the array passed to `setDictionaries`.

### Using the dictionary index
In order to use the decompound words from the dictionaries, make use of the search field `attributes.enterprise.product_suggestion`
in the relevance configuration of SES.

## n-gram
ElasticSearch also allows to index [n-grams](https://en.wikipedia.org/wiki/N-gram) directly. On the one hand, this
will not require an additional lookup against dictionaries while indexing, on the other hand, this will massively bloat
the search index with non meaningful search terms such as "ysc" or "ape" for "skyscraper".
By default Shopware will index ngrams with a width from 3 to 8. If you want to change this (to have larger ngrams or to
turn them of generally in order to speed up indexing), you can do this as follows:

```
// config.php
return [
    'db' => // your database config,
    'es' => [
        // your default ES config
        'compound_filter' => [
            'enabled' => true,                  // enable / disable ngrams
            'type' => 'ngram',                  // type of ngram, e.g. 'ngram' or 'edge_ngram'
            'min_gram' => 3,                    // minimum width
            'max_gram' => 8,                    // maximum width
            'token_chars' => ['letter'],        // characters to tokenize
        ],
    ]
];

```

### Using n-grams
In order to use the n-gram index, make use of the `*.ngram` fields in the relevance configuration of SES.
