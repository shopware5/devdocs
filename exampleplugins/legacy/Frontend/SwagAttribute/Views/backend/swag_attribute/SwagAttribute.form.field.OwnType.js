


Ext.define('SwagAttribute.form.field.OwnType', {
    alias: 'widget.swag-attribute-type',
    extend: 'Ext.form.FieldContainer',
    layout: {
        type: 'hbox'
    },
    mixins: {
        formField: 'Ext.form.field.Base'
    },

    initComponent: function() {
        var me = this;
        me.items = me.createItems();
        me.callParent(arguments);
    },

    createItems: function() {
        var me = this;

        me.prefixField = Ext.create('Ext.form.field.Text', {
            width: 250,
            emptyText: 'sw-',
            allowBlank: false
        });

        me.valueField = Ext.create('Ext.form.field.Text', {
            flex: 1,
            allowBlank: false
        });

        me.suffixField = Ext.create('Ext.form.field.Text', {
            width: 250,
            emptyText: '-ext',
            allowBlank: false
        });

        return [ me.prefixField, me.valueField, me.suffixField ];
    },

    getValue: function() {
        var me = this;
        return {
            prefix: me.prefixField.getValue(),
            value: me.valueField.getValue(),
            suffix: me.suffixField.getValue()
        };
    },

    setValue: function(value) {
        var me = this;

        me.prefixField.setValue('');
        me.valueField.setValue('');
        me.suffixField.setValue('');

        if (!value) {
            return me;
        }

        try {
            var values = Ext.JSON.decode(value);
            me.prefixField.setValue(values.prefix);
            me.valueField.setValue(values.value);
            me.suffixField.setValue(values.suffix);

            return me;
        } catch (e) {
            return me;
        }
    },

    getSubmitData: function() {
        var value = { };
        value[this.name] = Ext.JSON.encode(this.getValue());

        return value;
    }
});


