
Ext.define('Shopware.apps.SwagProductListingExtension.store.Product', {
    extend:'Shopware.store.Listing',

    configure: function() {
        return {
            controller: 'SwagProductListingExtension'
        };
    },
    model: 'Shopware.apps.SwagProductListingExtension.model.Product'
});