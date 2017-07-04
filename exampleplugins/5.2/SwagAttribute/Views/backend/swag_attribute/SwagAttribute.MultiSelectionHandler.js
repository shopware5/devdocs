



Ext.define('SwagAttribute.MultiSelectionHandler', {
    extend: 'Shopware.attribute.AbstractEntityFieldHandler',
    entity: "SwagAttribute\\Models\\SwagAttribute",
    singleSelectionClass: 'Shopware.form.field.SingleSelection',
    multiSelectionClass: 'Shopware.form.field.SwagAttributeGrid'
});



