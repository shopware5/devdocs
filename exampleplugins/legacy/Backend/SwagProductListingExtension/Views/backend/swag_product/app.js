
Ext.define('Shopware.apps.SwagProduct', {
    extend: 'Enlight.app.SubApplication',

    name:'Shopware.apps.SwagProduct',

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