$(function() {
    // DocsSearch
    var search = docsearch({
        apiKey: '4c10d9397401c1dbbbae98ad3897c5e0',
        indexName: 'shopware',
        inputSelector: 'input#search-query',
        debug: true, // Set debug to true if you want to inspect the dropdown
        algoliaOptions: {
            hitsPerPage: 7
        }
    });

    // Anchor tag generation
    addAnchors('.content h2, .content h3, .content h4');

    // Code highlighter
    hljs.initHighlightingOnLoad();
});
