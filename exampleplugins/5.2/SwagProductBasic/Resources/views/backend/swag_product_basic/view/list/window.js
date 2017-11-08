//

Ext.define('Shopware.apps.SwagProductBasic.view.list.Window', {
    extend: 'Shopware.window.Listing',
    alias: 'widget.product-list-window',
    height: 450,
    title : '{s name=window_title}Product listing{/s}',

    configure: function() {
        return {
            listingGrid: 'Shopware.apps.SwagProductBasic.view.list.Product',
            listingStore: 'Shopware.apps.SwagProductBasic.store.Product'
        };
    }
});