
Ext.define('Shopware.apps.SwagProductAssoc.store.Variant', {
    extend: 'Shopware.store.Association',
    model: 'Shopware.apps.SwagProductAssoc.model.Variant',
    configure: function() {
        return {
            controller: 'SwagProductAssoc'
        };
    }
});
