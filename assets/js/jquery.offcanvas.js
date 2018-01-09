(function ($, window) {
    "use strict";

    var pluginName = 'offCanvas',

        defaults = {
            triggerSelector: '.offcanvas--trigger',

            activeCls: 'is--active'
        },

        $window = $(window);

    function Plugin(element, options) {
        var me = this;

        me.el = element;
        me.$el = $(element);

        me.opts = $.extend({}, defaults, options) ;

        me.init();

        return me;
    }

    Plugin.prototype.init =  function () {
        var me = this;

        me.$triggerElements = $(me.opts.triggerSelector);

        me.registerEvents();
    };

    Plugin.prototype.registerEvents = function () {
        var me = this;

        me.$triggerElements.on('click', $.proxy(me.onTriggerClick, me));
    };

    Plugin.prototype.onTriggerClick = function (event) {
        var me = this;

        event.preventDefault();

        me.$el.toggleClass(me.opts.activeCls);
    };

    $.fn[pluginName] = function(options) {
        return this.each(function () {
            var element = this,
                pluginData = $.data(this, 'plugin_' + pluginName);

            if (!pluginData) {
                $.data(element, 'plugin_' + pluginName, new Plugin(element, options));
            }
        });
    };

    $(function() {
        $('*[data-offcanvas]').offCanvas();
    });

})(jQuery, window);