
Ext.define('Shopware.apps.SwagProductAssoc.store.Product', {
    extend: 'Shopware.store.Listing',

    configure: function () {
        return {
            controller: 'SwagProductAssoc'
        };
    },
    model: 'Shopware.apps.SwagProductAssoc.model.Product'
});