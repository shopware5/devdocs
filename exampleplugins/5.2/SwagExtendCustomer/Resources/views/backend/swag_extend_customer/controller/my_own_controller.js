// This is the controller


Ext.define('Shopware.apps.SwagExtendCustomer.controller.MyOwnController', {
    /**
     * Override the customer main controller
     * @string
     */
    override: 'Shopware.apps.Customer.controller.Main',

    init: function () {
        var me = this;

        // me.callParent will execute the init function of the overridden controller
        me.callParent(arguments);
    }
});
