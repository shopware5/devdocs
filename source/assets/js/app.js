$(function() {
    // DocsSearch
    var search = docsearch({
        apiKey: '4c10d9397401c1dbbbae98ad3897c5e0',
        indexName: 'shopware',
        inputSelector: 'input#search-query',
        debug: false, // Set debug to true if you want to inspect the dropdown
        algoliaOptions: {
            hitsPerPage: 7
        }
    });

    var $overlay;
    search.autocomplete.on('autocomplete:opened', function() {
        $overlay = $('<div>', {
            'class': 'search-result--overlay'
        });

        $overlay.appendTo($('body'));
        $overlay.animate({
            opacity: 1
        }, 750);
    });

    search.autocomplete.on('autocomplete:closed', function() {
        $overlay.animate({
            opacity: 0
        }, 500, function() {
            $overlay.remove();
        })
    });

    // Anchor tag generation
    addAnchors('.content h2, .content h3, .content h4');

    // Code highlighter
    hljs.initHighlightingOnLoad();
});
