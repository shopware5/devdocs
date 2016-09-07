
Ext.define('Shopware.apps.SwagProduct.model.Category', {

    extend: 'Shopware.apps.Base.model.Category',

    configure: function() {
        return {
            related: 'Shopware.apps.SwagProduct.view.detail.Category'
        }
    }
});

