//{namespace name=backend/analytics/view/main}
//{block name="backend/analytics/swag_custom_statistics/view/chart/voucher"}
Ext.define('Shopware.apps.Analytics.swagCustomStatistics.view.chart.Voucher', {
    extend: 'Shopware.apps.Analytics.view.main.Chart',
    alias: 'widget.analytics-chart-voucher',
    animate: true,
    shadows: true,

    legend: {
        position: 'right'
    },

    initComponent: function() {
        var me = this;

        me.series = [];

        me.axes = [
            {
                type: 'Numeric',
                position: 'left',
                fields: me.getAxesFields('amount'),
                title: 'Eingelöst',
                grid: true,
                minimum: 0
            },
            {
                type: 'Category',
                position: 'bottom',
                fields: ['name'],
                title: 'Gutscheine'
            }
        ];

        this.series = [
            {
                type: 'column',
                axis: 'left',
                gutter: 80,
                xField: 'name',
                yField: me.getAxesFields('amount'),
                title: me.getAxesTitles('{s name=chart/country/sum}Total sales{/s}'),
                stacked: true,
                label: {
                    display: 'insideEnd',
                    field: 'amount',
                    orientation: 'horizontal',
                    'text-anchor': 'middle'
                },
                tips: {
                    trackMouse: true,
                    width: 300,
                    height: 60,
                    renderer: function(storeItem, barItem) {
                        var name = storeItem.get('name'),
                            field = barItem.yField;

                        this.setTitle(name + ' : ' + storeItem.get(field));
                    }
                }
            }
        ];

        me.callParent(arguments);
    }
});
//{/block}
