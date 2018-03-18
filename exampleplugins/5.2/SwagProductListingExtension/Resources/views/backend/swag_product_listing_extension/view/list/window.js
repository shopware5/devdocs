
Ext.define('Shopware.apps.SwagProductListingExtension.view.list.Window', {
    extend: 'Shopware.window.Listing',
    alias: 'widget.product-list-window',
    height: 450,
    width: 1080,
    title : '{s name=window_title}Product listing{/s}',

    configure: function() {
        return {
            listingGrid: 'Shopware.apps.SwagProductListingExtension.view.list.Product',
            listingStore: 'Shopware.apps.SwagProductListingExtension.store.Product',

            extensions: [
                { xtype: 'product-listing-info-panel' },
                { xtype: 'product-listing-filter-panel' }
            ]
        };
    }
});