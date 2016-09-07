//{block name="backend/customer/view/detail/window" append}
Ext.define('Shopware.apps.SwagExtendCustomer.view.detail.Window', {
   override: 'Shopware.apps.Customer.view.detail.Window',

   getTabs: function() {
      var me = this,
          result = me.callParent();

      result.push(Ext.create('Shopware.apps.SwagExtendCustomer.view.detail.MyOwnTab'));

      return result;
   }

});
//{/block}










