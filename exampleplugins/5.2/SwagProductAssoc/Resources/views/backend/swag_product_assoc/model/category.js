
Ext.define('Shopware.apps.SwagProductAssoc.model.Category', {

    extend: 'Shopware.apps.Base.model.Category',

    configure: function() {
        return {
            related: 'Shopware.apps.SwagProductAssoc.view.detail.Category'
        }
    }
});

