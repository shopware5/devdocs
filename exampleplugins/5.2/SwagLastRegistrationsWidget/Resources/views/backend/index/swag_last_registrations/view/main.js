//{namespace name=backend/index/view/widgets}

Ext.define('Shopware.apps.Index.swagLastRegistrationsWidget.view.Main', {
    /**
     * Extend the base widget view
     */
    extend: 'Shopware.apps.Index.view.widgets.Base',

    /**
     * Set alias so the widget can be identified per widget name
     */
    alias: 'widget.swag-last-registrations',

    /**
     * Set the south handle so the widget height can be resized.
     */
    resizable: {
        handles: 's'
    },

    /**
     * Minimum / Default height of the widget
     */
    minHeight: 250,

    /**
     * Maximum height that the widget can have
     */
    maxHeight: 600,

    /**
     * Initializes the widget.
     * Creates the account store and the Grid for showing the newest registrations.
     * Adds a refresh button to the header to manually refresh the grid.
     *
     * @public
     * @return void
     */
    initComponent: function() {
        var me = this;

        me.accountStore = Ext.create('Shopware.apps.Index.swagLastRegistrationsWidget.store.Account');

        me.items = [
            me.createAccountGrid()
        ];

        me.tools = [{
            type: 'refresh',
            scope: me,
            handler: me.refreshView
        }];

        me.callParent(arguments);
    },

    /**
     * Creates the main Widget grid and its columns
     *
     * @returns { Ext.grid.Panel }
     */
    createAccountGrid: function() {
        var me = this;

        return Ext.create('Ext.grid.Panel', {
            border: 0,
            store: me.accountStore,
            columns: me.createColumns()
        });
    },

    /**
     * Helper method which creates the columns for the
     * grid panel in this widget.
     *
     * @return { Array } - generated columns
     */
    createColumns: function() {
        return [{
            dataIndex: 'date',
            header: '{s name=swag-last-registrations/date}{/s}',
            flex: 1
        }, {
            dataIndex: 'customer',
            header: '{s name=swag-last-registrations/customer}{/s}',
            flex: 1
        }, {
            dataIndex: 'customergroup',
            header: '{s name=swag-last-registrations/customer-group}{/s}',
            flex: 1
        }, {
            xtype: 'actioncolumn',
            width: 50,
            items: [{
                iconCls:'sprite-user--arrow',
                tooltip: '{s name=swag-last-registrations/customer-open}{/s}',
                handler: function(view, rowIndex, colIndex, item, event, record) {
                    openNewModule('Shopware.apps.Customer', {
                        action: 'detail',
                        params: {
                            customerId: ~~(record.get('id'))
                        }
                    });
                }
            }]
        }]
    },

    /**
     * Refresh the account store if its available
     */
    refreshView: function() {
        var me = this;

        if(!me.accountStore) {
            return;
        }

        me.accountStore.reload();
    }
});