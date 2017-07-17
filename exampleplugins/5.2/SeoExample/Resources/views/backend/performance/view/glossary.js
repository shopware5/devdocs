
//{block name="backend/performance/view/main/multi_request_tasks" append}
Ext.define('Shopware.apps.Performance.view.main.Glossary', {
    override: 'Shopware.apps.Performance.view.main.MultiRequestTasks',

    initComponent: function() {
        this.addProgressBar(
            {
                initialText: 'Glossary URLs',
                progressText: '[0] of [1] glossary URLs',
                requestUrl: '{url controller=glossary action=generateSeoUrl}'
            },
            'glossary',
            'seo'
        );

        this.callParent(arguments);
    }
});
//{/block}
