

Ext.define('Shopware.apps.Index.swagLastRegistrationsWidget.store.Account', {
    /**
     * Extends the default Ext Store
     * @string
     */
    extend: 'Ext.data.Store',

    model: 'Shopware.apps.Index.swagLastRegistrationsWidget.model.Account',

    remoteSort: true,

    pageSize: 25,

    autoLoad: true,

    /**
     * Configure the data communication
     * @object
     */
    proxy: {
        type: 'ajax',

        /**
         * Configure the url mapping for the different
         * store operations based on
         * @object
         */
        url: '{url controller="SwagLastRegistrationsWidget" action="getLastRegistrations"}',

        /**
         * Configure the data reader
         * @object
         */
        reader: {
            type: 'json',
            root: 'data',
            totalProperty: 'total'
        }
    }
});