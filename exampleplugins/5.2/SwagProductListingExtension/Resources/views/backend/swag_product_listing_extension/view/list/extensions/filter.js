

Ext.define('Shopware.apps.SwagProductListingExtension.view.list.extensions.Filter', {
    extend: 'Shopware.listing.FilterPanel',
    alias:  'widget.product-listing-filter-panel',
    width: 270,

    configure: function() {
        return {
            controller: 'SwagProductListingExtension',
            model: 'Shopware.apps.SwagProductListingExtension.model.Product',
            fields: {
                name: {},
                taxId: 'Tax rate',
                active: this.createActiveField
            }
        };
    },

    createActiveField: function(model, formField) {
        formField.fieldLabel = 'Active products';
        return formField;
    }
});




