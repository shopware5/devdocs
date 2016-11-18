//{block name="backend/swag_digital_publishing/view/editor/container"}
// {$smarty.block.parent}
Ext.define('Shopware.apps.SwagDigitalPublishing.view.editor.YouTubeExtension', {

    /**
     * Override the container class of the editor to add your custom element handlers.
     */
    override: 'Shopware.apps.SwagDigitalPublishing.view.editor.Container',

    initComponent: function() {
        var me = this;

        /**
         * Create an instance of your custom element handler.
         */
        var youTubeHandler = Ext.create('Shopware.apps.SwagDigitalPublishing.view.editor.elements.YouTubeElementHandler');

        /**
         * Check if there is already a registered handler with the same name.
         */
        if (me.getElementHandlerByName(youTubeHandler.getName()) === null) {

            /**
             * Add the custom handler to the list of available handlers.
             */
            me.elementHandlers.push(youTubeHandler);
        }

        /**
         * Call the parent init method.
         */
        me.callParent(arguments);
    }
});
//{/block}