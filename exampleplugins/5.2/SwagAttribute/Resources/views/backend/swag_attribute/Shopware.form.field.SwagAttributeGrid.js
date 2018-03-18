//
Ext.define('Shopware.form.field.SwagAttributeGrid', {
    extend: 'Shopware.form.field.Grid',
    alias: 'widget.shopware-form-field-swag-attribute-grid',

    createColumns: function() {
        return [
            this.createSortingColumn(),
            { dataIndex: 'name', flex: 1 },
            { dataIndex: 'description', flex: 1 },
            this.createActionColumn()
        ];
    },

    createSearchField: function() {
        return Ext.create('Shopware.form.field.SingleSelection', this.getComboConfig());
    }
});