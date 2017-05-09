

// {block name="backend/customer/view/customer_stream/condition_panel" }


// {$smarty.block.parent}

Ext.define('Shopware.apps.Customer.SwagCustomerStreamExtension', {
    override: 'Shopware.apps.Customer.view.customer_stream.ConditionPanel',

    registerHandlers: function() {
        var me = this,
            //fetch original handlers
            handlers = me.callParent(arguments);

        //push own handler into
        handlers.push(Ext.create('Shopware.apps.Customer.swag_customer_stream_extension.ActiveCondition'));

        //return modified handlers array
        return handlers;
    }
});


//definition of you own condition
Ext.define('Shopware.apps.Customer.swag_customer_stream_extension.ActiveCondition', {

    getLabel: function() {
        return 'My active condition';
    },

    supports: function(conditionClass) {
        return (conditionClass == 'SwagCustomerSearchExtension\\Bundle\\CustomerSearchBundle\\ActiveCondition');
    },

    create: function(callback) {
        callback(this._create());
    },

    load: function(conditionClass, items, callback) {
        callback(this._create());
    },

    _create: function() {
        return {
            title: this.getLabel(),
            conditionClass: 'SwagCustomerSearchExtension\\Bundle\\CustomerSearchBundle\\ActiveCondition',
            items: [{
                xtype: 'checkbox',
                name: 'active',
                boxLabel: 'Activate for active customers, deactivate for inactive customers',
                inputValue: true,
                uncheckedValue: false
            }]
        };
    }
});

// {/block}

