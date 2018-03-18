
Ext.define('Shopware.apps.SwagProductListingExtension.store.Variant', {
    extend: 'Shopware.store.Association',
    model: 'Shopware.apps.SwagProductListingExtension.model.Variant',
    configure: function() {
        return {
            controller: 'SwagProductListingExtension'
        };
    }
});
