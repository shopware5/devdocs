;(function ($,window,document,undefined ) {
    "use strict";
    
    var pluginName = 'expandSearch',
        opts = {
            search: '#searchBox',
            inputForm: 'search-query',
            animationSpeed: 200,
            results: '#search-results',
            entries: '.entries',
            activeClass: 'active'
        };
    
    function Plugin(el, options) {
        var me = this;
        
        me.opts = $.extend({}, opts, options);
        
        me.$el = $(el);
        me.$body = $('body');
        me.$search = $(me.opts.search);
        me.$result = $(me.opts.results);
        me.$entries = $(me.opts.entries);
        me.$input = $('#' + me.opts.inputForm);
        
        this.init();
    }
    
    Plugin.prototype.init = function () {
        var me = this,
            query = me.getParameterByName('q');
        
        me.$el.on('click.' + pluginName, $.proxy(me.onClick, me));
        me.$body.on('click.' + pluginName, $.proxy(me.onBodyClick, me));
        me.$body.on('keydown.' + pluginName, $.proxy(me.navigateResults, me));
        
        if(query) {
            me.$search.show();
        }
    };
    
    Plugin.prototype.onClick = function() {
        var me = this;
        
        me.$search.animate({width:'toggle'}, me.opts.animationSpeed, function () {
            $('#' + me.opts.inputForm).focus();
        });
        
        if (me.$result.is(':visible')) {
            me.$result.hide();
        }
    };
    
    Plugin.prototype.onBodyClick = function (event) {
        var me = this;
        var $target = $(event.target);
        
        if ($target.attr('id') === me.opts.inputForm
            || $target.hasClass('searchButton')
            || $target.hasClass('icon-search')) {
            return;
        }

        if (!me.$input.val().length || me.$result.is(':visible')) {
            me.$search.hide();
            me.$result.hide();
            me.$input.val("");
        }
    };

    Plugin.prototype.navigateResults = function (event) {
        var me = this,
            key = event.keyCode || event.which,
            activeElement,
            entry;
        
        if (me.$entries.children().length) {
            activeElement = $(me.opts.entries).find('.' + me.opts.activeClass);

            if (activeElement.length === 1) {
                if (key === 40) {
                    event.preventDefault();
                    if (!activeElement.next().length) {
                        var entry = me.$entries.children().first();
                        entry.addClass(me.opts.activeClass);
                        activeElement.removeClass(me.opts.activeClass);
                    } else {
                        activeElement.next().addClass(me.opts.activeClass);
                        activeElement.removeClass(me.opts.activeClass);
                    }
                } else if (key === 38) {
                    event.preventDefault();
                    if (!activeElement.prev().length) {
                        var entry = me.$entries.children().last();
                        entry.addClass(me.opts.activeClass);
                        activeElement.removeClass(me.opts.activeClass);
                    } else {
                        activeElement.prev().addClass(me.opts.activeClass);
                        activeElement.removeClass(me.opts.activeClass);
                    }
                }
            } else {
                if (key === 40) {
                    entry = $(me.opts.entries).children().first();
                    entry.addClass(me.opts.activeClass);
                } else if (key === 38) {

                    entry = $(me.opts.entries).children().last();
                    entry.addClass(me.opts.activeClass);
                }
            }
        }
        
        if(key === 13) {
            event.preventDefault();
            
            entry = $(me.opts.entries).find('.' + me.opts.activeClass);
            if(entry.length) {
                window.location.href = entry.attr('href');
            }
        }
    };
    
    Plugin.prototype.getParameterByName = function (name) {
        name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
        var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
            results = regex.exec(location.search);
        return results === null ? undefined : decodeURIComponent(results[1].replace(/\+/g, " "));
    };
    
    $.fn.expandSearch = function (options) {
        return this.each(function() {
           new Plugin(this, options); 
        });
    };
    
    $(function() {
        $('.searchButton').expandSearch();
    });

}) (jQuery, window, document);