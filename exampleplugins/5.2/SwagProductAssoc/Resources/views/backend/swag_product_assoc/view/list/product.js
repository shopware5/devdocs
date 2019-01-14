

Ext.define('Shopware.apps.SwagProductAssoc.view.list.Product', {
    extend: 'Shopware.grid.Panel',
    alias:  'widget.product-listing-grid',
    region: 'center',

    configure: function() {
        return {
            detailWindow: 'Shopware.apps.SwagProductAssoc.view.detail.Window'
        };
    }
});
