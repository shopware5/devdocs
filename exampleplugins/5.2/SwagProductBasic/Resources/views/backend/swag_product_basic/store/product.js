//

Ext.define('Shopware.apps.SwagProductBasic.store.Product', {
    extend:'Shopware.store.Listing',

    configure: function() {
        return {
            controller: 'SwagProductBasic'
        };
    },

    model: 'Shopware.apps.SwagProductBasic.model.Product'
});