
Ext.define('Shopware.apps.SwagProductAssoc.model.Product', {
    extend: 'Shopware.data.Model',

    configure: function() {
        return {
            controller: 'SwagProductAssoc',
            detail: 'Shopware.apps.SwagProductAssoc.view.detail.Product'
        };
    },

    fields: [
        { name : 'id', type: 'int', useNull: true },
        { name : 'name', type: 'string' },
        { name : 'taxId', type: 'int' },
        { name : 'active', type: 'boolean' },
        { name : 'createDate', type: 'date' },
        {
            name : 'description',
            type: 'string',
            useNull: true
        },
        {
            name : 'descriptionLong',
            type: 'string',
            useNull: true
        },
        { name : 'lastStock', type: 'boolean' }
    ],


    associations: [
        {
            relation: 'ManyToOne',
            field: 'taxId',

            type: 'hasMany',
            model: 'Shopware.apps.Base.model.Tax',
            name: 'getTax',
            associationKey: 'tax'
        },
        {
            relation: 'ManyToMany',

            type: 'hasMany',
            model: 'Shopware.apps.SwagProductAssoc.model.Category',
            name: 'getCategory',
            associationKey: 'categories'
        },
        {
            relation: 'OneToOne',

            type: 'hasMany',
            model: 'Shopware.apps.SwagProductAssoc.model.Attribute',
            name: 'getAttribute',
            associationKey: 'attribute'
        },
        {
            relation: 'OneToMany',
            storeClass: 'Shopware.apps.SwagProductAssoc.store.Variant',
            loadOnDemand: true,

            type: 'hasMany',
            model: 'Shopware.apps.SwagProductAssoc.model.Variant',
            name: 'getVariants',
            associationKey: 'variants'
        },
    ]
});

