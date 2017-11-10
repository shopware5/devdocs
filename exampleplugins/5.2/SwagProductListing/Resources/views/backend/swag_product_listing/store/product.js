
Ext.define('Shopware.apps.SwagProductListing.store.Product', {
    extend:'Shopware.store.Listing',
    configure: function() {
        return {
            controller: 'SwagProductListing'
        };
    },
    model: 'Shopware.apps.SwagProductListing.model.Product'
});