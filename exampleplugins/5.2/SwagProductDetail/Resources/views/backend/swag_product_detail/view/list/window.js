
Ext.define('Shopware.apps.SwagProductDetail.view.list.Window', {
    extend: 'Shopware.window.Listing',
    alias: 'widget.product-list-window',
    height: 340,
    width: 600,
    title : '{s name=window_title}Product listing{/s}',

    configure: function() {
        return {
            listingGrid: 'Shopware.apps.SwagProductDetail.view.list.Product',
            listingStore: 'Shopware.apps.SwagProductDetail.store.Product'
        };
    }
});