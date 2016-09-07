
Ext.define('Shopware.apps.SwagStore.view.list.Window', {
    extend: 'Shopware.window.Listing',
    alias: 'widget.swag-store-list-window',
    height: 450,
    title : '{s name=window_title}SwagStore listing{/s}',

    configure: function() {
        return {
            listingGrid: 'Shopware.apps.SwagStore.view.list.List',
            listingStore: 'Shopware.apps.SwagStore.store.Main'
        };
    }
});