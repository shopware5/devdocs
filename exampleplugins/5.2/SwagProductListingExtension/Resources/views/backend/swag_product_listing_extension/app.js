
Ext.define('Shopware.apps.SwagProductListingExtension', {
    extend: 'Enlight.app.SubApplication',

    name:'Shopware.apps.SwagProductListingExtension',

    loadPath: '{url action=load}',
    bulkLoad: true,

    controllers: [ 'Main' ],

    views: [
        'list.Window',
        'list.Product',
        'list.extensions.Info',
        'list.extensions.Filter',

        'detail.Product',
        'detail.Window',

        'detail.Category',
        'detail.Attribute',
        'detail.Variant',
    ],

    models: [
        'Product',
        'Category',
        'Attribute',
        'Variant'
    ],
    stores: [
        'Product',
        'Variant'
    ],

    launch: function() {
        return this.getController('Main').mainWindow;
    }
});