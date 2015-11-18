
Ext.define('Shopware.apps.SwagStore', {
    extend: 'Enlight.app.SubApplication',

    name:'Shopware.apps.SwagStore',

    loadPath: '{url action=load}',
    bulkLoad: true,

    controllers: [ 'Main' ],

    views: [
        'list.Window',
        'list.List',

        'detail.Container',
        'detail.Window'
    ],

    models: [ 'Main' ],
    stores: [ 'Main' ],

    launch: function() {
        return this.getController('Main').mainWindow;
    }
});