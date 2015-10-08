

Ext.define('Shopware.apps.SwagProduct.view.list.Product', {
    extend: 'Shopware.grid.Panel',
    alias:  'widget.product-listing-grid',
    region: 'center',

    configure: function() {
        return {
            detailWindow: 'Shopware.apps.SwagProduct.view.detail.Window',
            columns: {
                name: { header: 'Produkt name' },
                description: { flex: 3 },
                active: { width: 60, flex: 0 }
            }
        };
    },

    createToolbarItems: function() {
        var me = this,
            items = me.callParent(arguments);

        items = Ext.Array.insert(
            items,
            2,
            [ me.createToolbarButton() ]
        );

        return items;
    },

    createToolbarButton: function() {
        return Ext.create('Ext.button.Button', {
            text: 'Grid Panel button'
        });
    },

    createActionColumnItems: function () {
        var me = this,
            items = me.callParent(arguments);

        items.push({
            action: 'notice',
            iconCls: 'sprite-balloon',
            handler: function (view, rowIndex, colIndex, item, opts, record) {
                Shopware.Notification.createGrowlMessage(undefined, 'do some stuff in grid panel');
            }
        });
        return items;
    },

    createColumns: function() {
        var me = this,
            columns = me.callParent(arguments);

        var column = {
            xtype: 'gridcolumn',
            header: 'Created in july',
            renderer: me.columnRenderer,
            sortable: false,
            dataIndex: 'inJuly'
        };

        columns = Ext.Array.insert(
            columns,
            columns.length - 1,
            [ column ]
        );

        return columns;
    },

    columnRenderer: function(value, metaData, record) {
        var date = record.get('createDate');
        return this.booleanColumnRenderer((date.getMonth() === 6));
    }
});
