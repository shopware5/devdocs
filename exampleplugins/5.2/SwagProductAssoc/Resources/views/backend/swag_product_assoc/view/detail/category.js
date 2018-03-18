
Ext.define('Shopware.apps.SwagProductAssoc.view.detail.Category', {
    extend: 'Shopware.grid.Association',
    alias: 'widget.product-view-detail-category',
    height: 200,
    title: 'Category',

    configure: function() {
        return {
            controller: 'SwagProductAssoc',
            columns: {
                name: {}
            }
        };
    }
});