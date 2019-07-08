

Ext.define('Shopware.apps.SwagProductAssoc.view.detail.Product', {
    extend: 'Shopware.model.Container',
    alias: 'widget.product-detail-container',
    padding: 20,

    configure: function () {
        return {
            controller: 'SwagProductAssoc',
            fieldSets: [
                {
                    title: '',
                    fields: {
                        name: {

                        },
                        taxId: {
                            allowBlank: false
                        },
                        active: {

                        },
                        description: {

                        },
                        descriptionLong: {

                        },
                        lastStock: {

                        }
                    }
                },
            ]
//            associations: [ 'variants', 'categories', 'attribute' ]
        };
    }
});
