
Ext.define('Shopware.apps.SwagProduct.controller.Main', {
    extend: 'Enlight.app.Controller',
    init: function() {
        var me = this;

        me.control({
            'product-listing-grid': {
                'product-before-create-action-columns': me.addColumn,
                'product-after-create-action-column-items': me.addActionColumn,
                'product-before-create-right-toolbar-items': me.addToolbarButton
            }
        });
        me.mainWindow = me.getView('list.Window').create({ }).show();
    },

    addActionColumn: function(gridPanel, items) {
        items.push({
            action: 'notice',
            iconCls: 'sprite-balloon',
            handler: function (view, rowIndex, colIndex, item, opts, record) {
                Shopware.Notification.createGrowlMessage('', 'do some stuff in main controller');
            }
        });
        return items;
    },


    addToolbarButton: function(grid, items) {
        items.push(this.createToolbarButton());
        return items;
    },

    createToolbarButton: function() {
        return Ext.create('Ext.button.Button', {
            text: 'Controller button'
        });
    },

    addColumn: function(grid, columns) {
        var me = this;

        columns.push({
            xtype: 'gridcolumn',
            header: 'Created in july',
            renderer: me.columnRenderer,
            sortable: false,
            dataIndex: 'inJuly'
        });

        return columns;
    },

    columnRenderer: function(value, metaData, record) {
        var date = record.get('createDate');
        return this.booleanColumnRenderer((date.getMonth() === 6));
    }
});