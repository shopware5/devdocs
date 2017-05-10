//{block name="backend/swag_digital_publishing/view/editor/abstract_element_handler"}
// {$smarty.block.parent}
Ext.define('Shopware.apps.SwagDigitalPublishing.view.editor.elements.YouTubeElementHandler', {

    extend: 'Shopware.apps.SwagDigitalPublishing.view.editor.elements.AbstractElementHandler',

    name: 'youtube',

    label: 'YouTube Video',

    iconCls: 'sprite-film-youtube',

    createFormItems: function(elementRecord, data) {
        var me = this;

        me.generalFieldset = Ext.create('Ext.form.FieldSet', {
            title: 'YouTube Settings',
            layout: 'anchor',
            defaults: {
                anchor : '100%',
                labelWidth: 100
            },
            items: [{
                xtype: 'textfield',
                name: 'youTubeId',
                translatable: true,
                fieldLabel: 'YouTube ID',
                value: data['youTubeId'] || '',
                listeners: {
                    change: Ext.bind(me.updateElementRecord, me, [ me.formPanel, elementRecord ])
                }
            }, {
                xtype: 'numberfield',
                name: 'maxWidth',
                fieldLabel: 'Max width',
                value: data['maxWidth'] || 280,
                allowDecimals: false,
                minValue: 0,
                listeners: {
                    change: Ext.bind(me.updateElementRecord, me, [ me.formPanel, elementRecord ])
                }
            }, {
                xtype: 'numberfield',
                name: 'maxHeight',
                fieldLabel: 'Max height',
                value: data['maxHeight'] || 158,
                allowDecimals: false,
                minValue: 0,
                listeners: {
                    change: Ext.bind(me.updateElementRecord, me, [ me.formPanel, elementRecord ])
                }
            }, {
                xtype: 'checkbox',
                name: 'controls',
                fieldLabel: 'Show Controls',
                checked: !!data['controls'],
                listeners: {
                    change: Ext.bind(me.updateElementRecord, me, [ me.formPanel, elementRecord ])
                }
            }, {
                xtype: 'checkbox',
                name: 'showinfo',
                fieldLabel: 'Show Info',
                checked: !!data['showinfo'],
                listeners: {
                    change: Ext.bind(me.updateElementRecord, me, [ me.formPanel, elementRecord ])
                }
            }]
        });

        return me.generalFieldset;
    }
});
//{/block}