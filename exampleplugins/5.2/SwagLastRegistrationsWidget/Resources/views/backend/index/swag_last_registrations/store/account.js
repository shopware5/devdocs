

Ext.define('Shopware.apps.Index.swagLastRegistrationsWidget.store.Account', {
    /**
     * Extends the default Ext Store
     * @string
     */
    extend: 'Shopware.store.Listing',

    model: 'Shopware.apps.Index.swagLastRegistrationsWidget.model.Account',

    remoteSort: true,

    autoLoad: true,

    /**
    * This function is used to override the { @link #displayConfig } object of the statics() object.
    *
    * @returns { Object }
    */
    configure: function() {
        return {
            controller: 'SwagLastRegistrationsWidget'
        }
    }
});