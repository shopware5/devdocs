
Ext.define('Shopware.apps.SwagStore.view.detail.Container', {
    extend: 'Shopware.model.Container',
    padding: 20,

    configure: function() {
        return {
            controller: 'SwagStore',
            fieldSets: [
                {
                    layout: 'fit',
                    title: 'Store details',
                    fields: {
                        name: undefined,
                        address: undefined,
                        description: {
                            xtype: 'tinymce'
                        },
                        openInfo: {
                            xtype: 'tinymce'
                        }
                    }
                }
            ]
        };
    }
});