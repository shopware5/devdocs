

Ext.define('Shopware.apps.SwagProductListingExtension.view.list.Product', {
    extend: 'Shopware.grid.Panel',
    alias:  'widget.product-listing-grid',
    region: 'center',

    configure: function() {
        return {
            detailWindow: 'Shopware.apps.SwagProductListingExtension.view.detail.Window'
        };
    }
});
