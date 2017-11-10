
Ext.define('Shopware.apps.SwagProductListingExtension.view.detail.Category', {
    extend: 'Shopware.grid.Association',
    alias: 'widget.product-view-detail-category',
    height: 200,
    title: 'Category',

    configure: function() {
        return {
            controller: 'SwagProductListingExtension',
            columns: {
                name: {}
            }
        };
    }
});