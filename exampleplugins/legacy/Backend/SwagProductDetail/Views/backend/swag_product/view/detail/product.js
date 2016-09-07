

Ext.define('Shopware.apps.SwagProduct.view.detail.Product', {
    extend: 'Shopware.model.Container',
    alias: 'widget.product-detail-container',

//    padding: 20,

    layout: {
        type: 'hbox',
        align: 'stretch'
    },

    configure: function() {
        return {
            controller: 'SwagProduct',
            fieldSets: [{
                title: 'Product data',
                fields: {
                    name: 'Product name',
                    active: { disabled: true }
                }
            },
//            this.createCustomContainer,
            {
                title: 'Additional data',
                layout: 'fit',
                fields: {
                    description: {},
                    descriptionLong: {
                        fieldLabel: null,
                        width: 200,
                        xtype: 'tinymce'
                    }
                }
            }]
        };
    },


//    createCustomContainer: function() {
//        return Shopware.Notification.createBlockMessage(
//            'Here you can also use a Shopware.grid.Panel',
//            'notice'
//        );
//    }

//    createItems: function() {
//        var me = this,
//            items = me.callParent(arguments);
//
//        var leftContainer = Ext.create('Ext.container.Container', {
//            flex: 1,
//            margin: 20,
//            items: items
//        });
//
//        return [leftContainer, me.createSidebar()];
//    },
//
//    createSidebar: function() {
//        return Ext.create('Ext.panel.Panel', {
//            width: 200,
//            layout: {
//                type: 'accordion',
//                titleCollapse: false,
//                animate: true,
//                activeOnTop: true
//            },
//            items: [{
//                title: 'Panel 1',
//                html: 'Panel content!'
//            },{
//                title: 'Panel 2',
//                html: 'Panel content!'
//            }]
//        });
//    }


});