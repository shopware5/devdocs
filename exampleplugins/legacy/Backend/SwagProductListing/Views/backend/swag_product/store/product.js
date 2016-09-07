
Ext.define('Shopware.apps.SwagProduct.store.Product', {
    extend:'Shopware.store.Listing',
    configure: function() {
        return { controller: 'SwagProduct' };
    },
    model: 'Shopware.apps.SwagProduct.model.Product'
});