
Ext.define('Shopware.apps.SwagProduct.model.Attribute', {

    extend: 'Shopware.data.Model',

    configure: function() {
        return {
            detail: 'Shopware.apps.SwagProduct.view.detail.Attribute'
        };
    },

    fields: [
        { name: 'id', type: 'int' },
        { name: 'attr1', type: 'string' },
        { name: 'attr2', type: 'string' },
        { name: 'attr3', type: 'string' },
        { name: 'attr4', type: 'string' },
        { name: 'attr5', type: 'string' },
        { name: 'attr6', type: 'string' },
        { name: 'attr7', type: 'string' }
    ]

});

