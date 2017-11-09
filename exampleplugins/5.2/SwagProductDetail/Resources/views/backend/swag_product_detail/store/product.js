
Ext.define('Shopware.apps.SwagProductDetail.store.Product', {
    extend:'Shopware.store.Listing',
    configure: function() {
        return {
            controller: 'SwagProductDetail'
        };
    },
    model: 'Shopware.apps.SwagProductDetail.model.Product'
});