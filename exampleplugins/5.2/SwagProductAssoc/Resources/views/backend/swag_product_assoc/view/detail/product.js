

Ext.define('Shopware.apps.SwagProductAssoc.view.detail.Product', {
    extend: 'Shopware.model.Container',
    alias: 'widget.product-detail-container',
    padding: 20,

    configure: function() {
        return {
            controller: 'SwagProductAssoc',
//            associations: [ 'variants', 'categories', 'attribute' ]
        };
    }
});