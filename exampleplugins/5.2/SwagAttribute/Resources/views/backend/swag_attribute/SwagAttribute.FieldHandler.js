//
Ext.define('SwagAttribute.FieldHandler', {

    extend: 'Shopware.attribute.FieldHandlerInterface',

    /**
     * @override
     * @param { Shopware.model.AttributeConfig } attribute
     * @returns { boolean }
     */
    supports: function(attribute) {
        var name = attribute.get('columnName');
        if (attribute.get('tableName') !== 's_articles_attributes') {
            return false;
        }

        return (name === 'my_own_validation' || name === 'my_own_type');
    },

    /**
     * @override
     * @param { Object } field
     * @param { Shopware.model.AttributeConfig } attribute
     * @returns { object }
     */
    create: function(field, attribute) {
        var name = attribute.get('columnName');

        switch (name) {
            case 'my_own_type':
                return this.createOwnTypeField(field);
            case 'my_own_validation':
                return this.createOwnValidationField(field);
        }
        return null;
    },

    createOwnTypeField: function(field) {
        return Ext.apply(field, {
            xtype: 'swag-attribute-type'
        });
    },

    createOwnValidationField: function(field) {
        return Ext.apply(field, {
            xtype: 'textfield',
            emptyText: 'My default value',
            validateOnBlur: true,
            validate: function() {
                //validate some stuff
                return true;
            }
        });
    }
});