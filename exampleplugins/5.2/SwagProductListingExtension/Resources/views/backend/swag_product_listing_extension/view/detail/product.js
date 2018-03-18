

Ext.define('Shopware.apps.SwagProductListingExtension.view.detail.Product', {
    extend: 'Shopware.model.Container',
    alias: 'widget.product-detail-container',
    padding: 20,

    configure: function() {
        return {
            controller: 'SwagProductListingExtension',
//            associations: [ 'variants', 'categories', 'attribute' ]
        };
    }
});