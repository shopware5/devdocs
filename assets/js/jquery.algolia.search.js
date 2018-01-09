(function ($) {
    var debounce = function (fn) {
        var timeout;
        var slice = Array.prototype.slice;

        return function () {
            var args = slice.call(arguments),
                    ctx = this;

            clearTimeout(timeout);

            timeout = setTimeout(function () {
                fn.apply(ctx, args);
            }, 200);
        };
    };

    var AlgoliaSearch = (function () {
        function AlgoliaSearch(elem, options) {
            this.$elem = elem;
            this.$results = $(options.results);
            this.$entries = $(options.entries, this.$results);
            this.baseUrl = options.baseUrl;

            var client = algoliasearch('DX6UMWHNHY', 'fa1f40caf3ede048e2d764f6e8b32f11');
            this.index = client.initIndex('developers.shopware.com_prod');

            this.initialize();
        }

        AlgoliaSearch.prototype.initialize = function () {
            var self = this;

            self.populateSearchFromQuery();
            self.bindKeypress();
        };

        AlgoliaSearch.prototype.bindKeypress = function () {
            var self = this;
            var oldValue = this.$elem.val();

            this.$elem.bind('keyup', debounce(function () {
                var newValue = self.$elem.val();
                if (newValue !== oldValue) {
                    self.search(newValue);
                }

                oldValue = newValue;
            }));
        };

        AlgoliaSearch.prototype.search = function (query) {
            var me = this;

            this.$elem.trigger('search', [ query ]);

            if (query.length < 2) {
                this.$results.hide();
                this.$entries.empty();
            } else {

                this.index.search(query, {
                    hitsPerPage: 10,
                    facets: '*'
                }, function (err, content) {
                    if (err) {
                        console.error(err);
                        return;
                    }

                    me.displayResults(content);
                });
            }
        };

        AlgoliaSearch.prototype.displayResults = function (content) {
            var $entries = this.$entries;
            var $results = this.$results;

            this.$elem.trigger('displayResults');

            $entries.empty();
            if (content.hits.length === 0) {
                $entries.append('<p>Nothing found.</p>');
            } else {
                content.hits.forEach(function(entry) {
                    var elem = $('<a/>', {
                        'class': "entry",
                        'href': entry.url
                    }).append($('<div/>', {
                        'class': "entry-headline",
                        'html': entry._highlightResult.title.value
                    }));

                    $entries.append(elem);
                });
            }

            $results.append($entries);
            $results.show();
        };

        // Populate the search input with 'q' querystring parameter if set
        AlgoliaSearch.prototype.populateSearchFromQuery = function () {
            var getParamByName = function getParameterByName(name) {
                name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
                var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
                        results = regex.exec(location.search);
                return results === null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
            };

            var searchQuery = getParamByName("q");
            if (searchQuery) {
                this.$elem.val(searchQuery);
                this.search(searchQuery);
            }
        };

        return AlgoliaSearch;
    })();

    $.fn.algoliaSearch = function (options) {
        // apply default options
        options = $.extend({}, $.fn.algoliaSearch.defaults, options);

        // create search object
        new AlgoliaSearch(this, options);

        return this;
    };

    $.fn.algoliaSearch.defaults = {
        baseUrl: '',     // Url to prepend
        results: '#search-results',  // selector for containing search results element
        entries: '.entries'         // selector for search entries containing element (contained within results above)
    };
})(jQuery);
