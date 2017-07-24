
//{block name="backend/performance/view/main/multi_request_tasks" append}
Ext.define('Shopware.apps.Performance.view.main.Glossary', {
    override: 'Shopware.apps.Performance.view.main.MultiRequestTasks',

    height: 475,

    initComponent: function() {
        var me = this;

        me.callParent(arguments);

        me.snippets.seo.glossary = '[0] of [1] glossary urls';
    },

    createSeoItems: function() {
        var me = this,
            items = me.callParent(arguments),
            progressCt = items[1];

        me.glossaryProgress = me.createProgressBar('glossary', 'Glossary URLs');

        progressCt.items.push(me.glossaryProgress);

        return items;
    }
});
//{/block}