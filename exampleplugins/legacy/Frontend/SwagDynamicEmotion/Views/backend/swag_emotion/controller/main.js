//{block name="backend/emotion/controller/main"}
//{$smarty.block.parent}
Ext.define('Shopware.apps.SwagEmotion.controller.Main', {
    override: 'Shopware.apps.Emotion.controller.Main',

    init: function() {
        var me = this;

        me.callParent(arguments);

        if (me.subApplication.params && me.subApplication.params.emotionId > 0) {
            me.mainWindow.hide();
            me.getStore('Library').getProxy().extraParams.showStoreComponents = true;
            me.getController('Detail').loadEmotionRecord(me.subApplication.params.emotionId, function(record) {
                me.getController('Detail').openDetailWindow(record);
            });
        }
    }
});
//{/block}