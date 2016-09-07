
Ext.define('Shopware.apps.SwagProduct.store.Variant', {
    extend: 'Shopware.store.Association',
    model: 'Shopware.apps.SwagProduct.model.Variant',
    configure: function() {
        return {
            controller: 'SwagProduct'
        };
    }
});
