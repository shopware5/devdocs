Ext.define('Shopware.apps.SwagStore.view.list.List', {
    extend: 'Shopware.grid.Panel',
    alias: 'widget.swag-store-listing-grid',
    region: 'center',

    configure: function () {
        return {
            detailWindow: 'Shopware.apps.SwagStore.view.detail.Window',
            columns: {
                name: undefined,
                address: undefined
            }
        };
    },

    createToolbarItems: function () {
        var me = this,
            items = me.callParent(arguments);

        items.splice(3, 0, Ext.create('Ext.button.Button', {
            text: 'Edit store template',
            handler: function () {
                Shopware.app.Application.addSubApplication({
                    name: 'Shopware.apps.Emotion',
                    params: {
                        // the ID has been assigned in our backend controller
                        emotionId: '{$storeTemplateEmotionId}'
                    }
                })
            }
        }));
        items.splice(4, 0, '->');

        return items;
    }
});
