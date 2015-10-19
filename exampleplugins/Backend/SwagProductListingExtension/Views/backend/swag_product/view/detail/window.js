

Ext.define('Shopware.apps.SwagProduct.view.detail.Window', {
    extend: 'Shopware.window.Detail',
    alias: 'widget.product-detail-window',
    title : '{s name=title}Product details{/s}',
    height: 270,
    width: 680,
    configure: function() {
        return {
            associations: [ 'attribute', 'categories', 'variants' ]
        }
    }
});
