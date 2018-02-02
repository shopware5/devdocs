//{namespace name=backend/analytics/view/main}
//{block name="backend/analytics/swag_custom_statistics/view/table/voucher"}
Ext.define('Shopware.apps.Analytics.swagCustomStatistics.view.table.Voucher', {
    extend: 'Shopware.apps.Analytics.view.main.Table',
    alias: 'widget.analytics-table-voucher',

    initComponent: function() {
        var me = this;

        me.columns = {
            items: me.getColumns(),
            defaults: {
                flex: 1,
                sortable: false
            }
        };

        me.initStoreIndices('amount', 'Anzahl: [0]');

        me.callParent(arguments);
    },

    getColumns: function() {
        return [
            {
                dataIndex: 'name',
                text: 'Name'
            },
            {
                dataIndex: 'amount',
                text: 'Anzahl'
            }
        ];
    }
});
//{/block}
