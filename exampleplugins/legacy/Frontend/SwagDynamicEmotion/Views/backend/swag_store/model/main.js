
Ext.define('Shopware.apps.SwagStore.model.Main', {
    extend: 'Shopware.data.Model',

    configure: function() {
        return {
            controller: 'SwagStore',
            detail: 'Shopware.apps.SwagStore.view.detail.Container'
        };
    },


    fields: [
        { name : 'id', type: 'int', useNull: true },
        { name : 'name', type: 'string', useNull: false },
        { name : 'description', type: 'string', useNull: false },
        { name : 'address', type: 'string', useNull: false },
        { name : 'openInfo', type: 'string', useNull: false }
    ]
});

