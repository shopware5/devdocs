
Ext.define('Shopware.apps.SwagProduct.controller.Main', {
    extend: 'Enlight.app.Controller',

    init: function() {
        var me = this;

        me.control({
            'product-listing-grid': {
                'product-before-create-action-columns': me.addColumn,
                'product-after-create-action-column-items': me.addActionColumn,
                'product-before-create-right-toolbar-items': me.addToolbarButton
            },
            'product-detail-container': {
                'product-after-create-items': me.afterCreateItems
            },
            'product-detail-window': {
                'product-after-create-tab-items': me.afterCreateTabItems,
                'product-after-create-toolbar-items': me.addDetailWindowButton
            }
        });

        me.mainWindow = me.getView('list.Window').create({ }).show();
    },

    afterCreateTabItems: function(window, items) {
        var me = this;

        items.push(me.createOwnTabItem());

        return items;
    },

    createOwnTabItem: function() {
        return Ext.create('Ext.container.Container', {
            items: [],
            title: 'My tab item'
        });
    },


    addDetailWindowButton: function(window, items) {
        console.log("test");
        items.push(this.createToolbarButton());
        return items;
    },

    afterCreateItems: function(container, items) {
        var me = this;

        //create left container to wrap the already generated items
        var leftContainer = Ext.create('Ext.container.Container', {
            flex: 1,
            margin: 20,
            items: Ext.clone(items)
        });

        //reset reference array
        items.length = 0;

        //create new items array structure
        items.push(leftContainer, me.createSidebar());
    },

    createSidebar: function() {
        return Ext.create('Ext.panel.Panel', {
            width: 200,
            layout: {
                type: 'accordion',
                titleCollapse: false,
                animate: true,
                activeOnTop: true
            },
            items: [{
                title: 'Panel 1',
                html: 'Panel content!'
            },{
                title: 'Panel 2',
                html: 'Panel content!'
            }]
        });
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