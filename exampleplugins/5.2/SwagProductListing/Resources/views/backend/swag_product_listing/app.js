
Ext.define('Shopware.apps.SwagProductListing', {
    extend: 'Enlight.app.SubApplication',

    name:'Shopware.apps.SwagProductListing',

    loadPath: '{url action=load}',
    bulkLoad: true,

    controllers: [ 'Main' ],

    views: [
        'list.Window',
        'list.Product',

        'detail.Product',
        'detail.Window'
    ],

    models: [ 'Product' ],
    stores: [ 'Product' ],

    launch: function() {
        return this.getController('Main').mainWindow;
    }
});