;(function ($) {
    "use strict";

    /**
     * expandCode plugin
     * the plugin enables the collapse of <pre><code></code></pre> tags at a certain height.
     * 
     * @example initializing the plugin
     * $('pre').expandCode();
     *
     * @example before the plugin
     * <pre>
     *     <code>lorem</code>
     * </pre>
     *
     * @example after the plugin
     * <div class="code-wrapper">
     *     <pre>
     *         <code>Lorem</code>
     *     </pre>
     *     <span class="code-expandButton">Expand code</span>
     * </div>
     */
    
    var pluginName = 'expandCode',
        opts = {
            
            // maximum height of the code panel (also set inside fixedCode class)
            maxHeight: 370,
            
            // class when the code is expanded
            expandedClass: 'code-expanded',
            
            // class when the code is collapsed
            collapsedClass: 'code-collapsed',
            
            // class for the wrapping element
            wrapperClass: 'code-wrapper',
            
            // class for the expand button
            expandBtnClass: 'code-expandButton',
            
            // text when the code is collapsed
            collapseText: 'Expand code',
            
            // text when the code is expanded
            expandText: 'Collapse code'
        };

    /**
     * @param {Node} el
     * @param {Object} options
     * @constructor
     */
    function Plugin(el, options) {
        var me = this;

        me.opts = $.extend({}, opts, options);
        me.$el = $(el);

        if (me.$el.height() < me.opts.maxHeight) {
            return false;
        }
        
        me.init();
    }

    /**
     * Plugin init function
     */
    Plugin.prototype.init = function () {
        var me = this;

        me.$el.addClass(me.opts.collapsedClass);

        me.createExpandBtn();
        me.registerEvents();
    };

    /**
     * Registers the plugin event handlers
     */
    Plugin.prototype.registerEvents = function() {
        var me = this;
        me.$expandBtn.on('click.' + pluginName, $.proxy(me.expandPre, me));
    };

    /**
     * Creates a Wrapper that includes the Button and the <pre> tag.
     * Creates the button to expand the code and adds it to the DOM.
     * @param event
     */
    Plugin.prototype.createExpandBtn = function (event) {
        var me = this;
        
        me.$el.wrap($('<div>', {
           'class': me.opts.wrapperClass
        }));

        me.wrap = me.$el.parent();

        me.$expandBtn = $('<span>', {
            'class': me.opts.expandBtnClass,
            'html': me.opts.collapseText
        }).appendTo(me.wrap);
    };

    /**
     * Enables the collapsing of the code tag when clicking on the button.
     * @param event
     * @event click
     */
    Plugin.prototype.expandPre = function (event) {
        var me = this,
            state = me.$el.hasClass(me.opts.collapsedClass), 
            text = (state) ? me.opts.expandText : me.opts.collapseText;
        
        event.preventDefault();

        me.$el.toggleClass(me.opts.collapsedClass);
        me.$el.toggleClass(me.opts.expandedClass);
        me.$expandBtn.html(text);
    };

    /**
     * Iterates over each occurrence of the Plugin inside the DOM.
     * @param options
     * @returns {*|Boolean|Ext.dom.CompositeElement|Ext.util.HashMap|Ext.util.LruCache}
     */
    $.fn.expandCode = function (options) {
        return this.each(function() {
            new Plugin(this, options);
        });
    };

    /**
     * Function call on the DOM element.
     */
    $(function() {
        
        window.setTimeout(function() {
            $('pre').expandCode();
        }, 500);
        
    });

}) (jQuery);
