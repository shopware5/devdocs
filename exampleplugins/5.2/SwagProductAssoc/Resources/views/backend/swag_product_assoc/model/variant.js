
Ext.define('Shopware.apps.SwagProductAssoc.model.Variant', {

    extend: 'Shopware.data.Model',

    configure: function() {
        return {
            listing: 'Shopware.apps.SwagProductAssoc.view.detail.Variant'
        };
    },

    fields: [
        { name: 'id', type: 'int' },
        { name: 'productId', type: 'int' },
        { name: 'number', type: 'string' },
        { name: 'additionalText', type: 'string' },
        { name: 'active', type: 'boolean' },
        { name: 'inStock', type: 'int' },
        { name: 'stockMin', type: 'int' },
        { name: 'weight', type: 'float' }
    ]

});

