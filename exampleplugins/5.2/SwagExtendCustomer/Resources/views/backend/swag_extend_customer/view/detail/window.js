//{block name="backend/customer/view/detail/window"}
// {$smarty.block.parent}
Ext.define('Shopware.apps.SwagExtendCustomer.view.detail.Window', {
    override: 'Shopware.apps.Customer.view.detail.Window',

    getTabs: function () {
        var me = this,
            result = me.callParent();

        result.push(Ext.create('Shopware.apps.SwagExtendCustomer.view.detail.MyOwnTab'));

        return result;
    },

    /**
     * Replace the textBox for field "title" with a comboBox
     *
     * @returns { Ext.form.FieldSet }
     */
    createPersonalFieldSet: function () {
        var me = this,
            fieldSet = me.callParent(arguments),
            indexA, indexB;


        fieldSet.items.each(function (item, firstIndex) {
            item.items.each(function (field, secondIndex) {

                if(field.name === 'title') {
                    indexA = firstIndex;
                    indexB = secondIndex;
                }
            });
        });

        fieldSet.items.items[indexA].items.items[indexB] = Ext.create('Ext.form.field.ComboBox', {
            labelWidth: 155,
            anchor: '95%',
            name: 'title',
            displayField: 'name',
            valueField: 'name',
            store: me.createTitleStore(),
            fieldLabel: 'Title'
        });

        return fieldSet;
  
    },

    /**
     * @returns { Ext.data.Store }
     */
    createTitleStore: function () {
        return Ext.create('Ext.data.Store', {
            fields: [
                { name: 'name' }
            ],
            data: [
                { name: 'Sir' },
                { name: 'Madame' },
                { name: 'Lord' },
                { name: 'Dr.' },
                { name: 'Prof.' },
                { name: 'Prof. Dr.' }
            ]
        })
    }

});
//{/block}










