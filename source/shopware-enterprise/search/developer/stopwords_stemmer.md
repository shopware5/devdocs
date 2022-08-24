---
layout: default
title: Stopwords and Stemmer
github_link: search/developer/stopwords_stemmer.md
indexed: true
menu_title: Stopwords & Stemmer
group: Shopware Enterprise
subgroup: Enterprise Search
subsubgroup: Developer
menu_order: 2
shopware_version: 1.0.1
---

Stopwords are common words in a language, that to not add any relevance to a document but will bloat the search index
and return less meaningful results for searches. Examples are "the" or "a" in english language.
Stemmer are language specific rules that reduce a word to its basic form, such as "men => man" or "houses => house". Stemmer
usually ensures, that searches will find results, even if the grammatical number or case of the search term does not match
the grammatical number / case of the indexed term.

## Modify stemmer / stopwords
Stemmer as well as stopwords are configured while indexing in `\SwagEnterpriseSearch\Bundle\ESIndexingBundle\IndexingSettings\Settings`.
For each shop SES will find the corresponding language and choose stopwords / stemmer based on the language locale. ElasticSearch
has a lot of stemmer / stopword filters built in. In `\SwagEnterpriseSearch\Bundle\ESIndexingBundle\IndexingSettings\ElasticMapping`
SES maintains a mapping list in order to map language locales to the corresponding ElasticSearch stopword / stemmer configuration:

```
class ElasticMapping implements ElasticMappingInterface
{
    public function getStopwordMapping(): array
    {
        return [
            'de' => '_german_',
            'en' => '_english_',
            'fr' => '_french_',
            'nl' => '_dutch_',
            'it' => '_italian_',
        ];
    }

    public function getStemmerMapping(): array
    {
        return [
            'de' => 'light_german',
            'en' => 'english',
            'fr' => 'light_french',
            'nl' => 'dutch',
            'it' => 'light_italian',

        ];
    }
}
```

In order to change or extend this mapping list, you can decorate the `ElasticMapping` service and modify the stopword
mapping `getStopwordMapping` or the stemmer mapping `getStemmerMapping`.

## Own stopwords list
By default SES will use the default stopword list of ElasticSearch as mapped in `ElasticMapping`. However, you are able
to overwrite stopwords per language. In order to do so, configure a stopword directory in your `config.php`:

```
// your default config.php content
// …
'es' => [
    // your default ES configuration
    // …
    'stopword_directory' => '/var/www/stopwords/'
],
```

In this directory now create per-language stopwords files such as `en.txt`. Each line should hold one stopword:

```
# file: /var/www/stopwords/en.txt

these
are
four
stopwords
```

For more details of the implementation see `\SwagEnterpriseSearch\Bundle\ESIndexingBundle\IndexingSettings\Stopwords`.

<div class="alert alert-info">
Notice: As stopwords and stemmer are configured while indexing, changes will only apply after a full re-index.
</div>
