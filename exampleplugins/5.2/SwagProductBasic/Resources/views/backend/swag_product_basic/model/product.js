
Ext.define('Shopware.apps.SwagProductBasic.model.Product', {
    extend: 'Shopware.data.Model',

    configure: function() {
        return {
            controller: 'SwagProductBasic',
            detail: 'Shopware.apps.SwagProductBasic.view.detail.Product'
        };
    },


    fields: [
        { name : 'id', type: 'int', useNull: true },
        { name : 'name', type: 'string' },
        { name : 'active', type: 'boolean' },
        { name : 'createDate', type: 'date' },
        { name : 'description', type: 'string', useNull: true },
        { name : 'descriptionLong', type: 'string', useNull: true },
        { name : 'lastStock', type: 'boolean' }
    ]
});

