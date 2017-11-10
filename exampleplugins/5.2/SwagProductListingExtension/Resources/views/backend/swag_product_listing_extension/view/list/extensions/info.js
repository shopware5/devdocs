

Ext.define('Shopware.apps.SwagProductListingExtension.view.list.extensions.Info', {
    extend: 'Shopware.listing.InfoPanel',
    alias:  'widget.product-listing-info-panel',
    width: 270,

    configure: function() {
        var me = this;

        return {
            model: 'Shopware.apps.SwagProductListingExtension.model.Product',
//            fields: {
//                name: '<p style="padding: 2px">' +
//                         'The product name is: ' +
//                         '{literal}{name}{/literal}' +
//                      '</p>',
//
//                description: me.createDescriptionField
//            }
        };
    },

    createTemplate: function() {
        return new Ext.XTemplate(
            '<tpl for=".">',
            '<div class="item" style="">',
                '<p style="padding: 2px">',
                    'The <b>product name</b> is: {literal}{name}{/literal}',
                '</p>',
                '<p style="padding: 10px 2px">',
                    '<b>Product description</b>: {literal}{description}{/literal}',
                '</p>',
            '</div>',
            '</tpl>'
        );
    },

    createDescriptionField: function(infoPanel, field) {
        return '<p style="padding:10px 2px">' +
            'Custom function call for the description field' +
        '</p>';
    }
});




