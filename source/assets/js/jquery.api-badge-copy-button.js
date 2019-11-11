;(function ($) {
    "use strict";

    var pluginName = 'apiBadgeCopyButton',
        defaults = {
            rawSelector: '.api-badge .icon-copy',
        };

    function Plugin(element, options) {
        this.el = element;
        this.$el = $(element);
        this.opts = $.extend({}, defaults, options);

        this.init();

        return this;
    }

    Plugin.prototype.init = function () {
        this.addEventListeners();
    };

    Plugin.prototype.addEventListeners = function () {
        this.$el.on('click', $.proxy(this.onClick, this));
    };

    Plugin.prototype.onClick = function (event) {
        var me = this;

        navigator.clipboard.writeText(me.getBodyText()).then(
            function () {
                me.$el.children('span').text('Copied');

                window.setTimeout(function () {
                    me.$el.children('span').text('');
                }, 2500);
            }
        );
    };

    Plugin.prototype.getBodyText = function () {
        return this.$el.parent().next('pre').find('code.hljs').text();
    };

    $.fn[pluginName] = function (options) {
        return this.each(function () {
            var element = this,
                pluginData = $.data(this, 'plugin_' + pluginName);

            if (!pluginData) {
                $.data(element, 'plugin_' + pluginName, new Plugin(element, options));
            }
        });
    };

    $(function () {
        $(defaults.rawSelector).apiBadgeCopyButton();
    });
})(jQuery);
