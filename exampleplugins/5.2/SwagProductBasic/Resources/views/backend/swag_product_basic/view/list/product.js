//

Ext.define('Shopware.apps.SwagProductBasic.view.list.Product', {
    extend: 'Shopware.grid.Panel',
    alias:  'widget.product-listing-grid',
    region: 'center',

    configure: function() {
        return {
            detailWindow: 'Shopware.apps.SwagProductBasic.view.detail.Window'
        };
    }
});
