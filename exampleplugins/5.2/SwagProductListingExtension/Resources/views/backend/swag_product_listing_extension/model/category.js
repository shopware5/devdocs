
Ext.define('Shopware.apps.SwagProductListingExtension.model.Category', {

    extend: 'Shopware.apps.Base.model.Category',

    configure: function() {
        return {
            related: 'Shopware.apps.SwagProductListingExtension.view.detail.Category'
        }
    }
});

