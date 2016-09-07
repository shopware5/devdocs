
Ext.define('Shopware.apps.SwagStore.store.Main', {
    extend:'Shopware.store.Listing',

    configure: function() {
        return {
            controller: 'SwagStore'
        };
    },
    model: 'Shopware.apps.SwagStore.model.Main'
});