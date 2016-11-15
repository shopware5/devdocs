//{block name="backend/customer/view/detail/billing"}
// {$smarty.block.parent}
Ext.define('Shopware.apps.SwagExtendCustomer.view.detail.Billing', {
    override:'Shopware.apps.Customer.view.detail.Billing',

    /**
     * This extjs override will call the original method first
     * and then change the xtype of the 3rd field
     */
    createBillingFormRight: function() {
        var me = this,
            result = me.callParent(arguments);

        result[2].xtype = 'numberfield';

        return result;
    }

});
//{/block}