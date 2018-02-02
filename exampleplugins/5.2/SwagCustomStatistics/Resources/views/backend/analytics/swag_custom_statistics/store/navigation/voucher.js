//{block name="backend/analytics/swag_custom_statistics/store/navigation/voucher"}
Ext.define('Shopware.apps.Analytics.swagCustomStatistics.store.navigation.Voucher', {
    extend: 'Ext.data.Store',
    alias: 'widget.analytics-store-voucher',
    remoteSort: true,

    fields: [
        'amount',
        'name'
    ],

    proxy: {
        type: 'ajax',

        url: '{url controller=SwagCustomStatistics action=getVoucherStatistics}',

        reader: {
            type: 'json',
            root: 'data',
            totalProperty: 'total'
        }
    },

    constructor: function(config) {
        var me = this;
        config.fields = me.fields;

        if (config.shopStore) {
            config.shopStore.each(function(shop) {
                config.fields.push('amount' + shop.data.id);
            });
        }

        me.callParent(arguments);
    }
});
//{/block}
