
//{block name="backend/performance/controller/multi_request" append}
Ext.define('Shopware.apps.Performance.controller.Glossary', {
    override: 'Shopware.apps.Performance.controller.MultiRequest',

    init: function () {
        var me = this;

        me.requestConfig.seo.requestUrls.glossary = '{url controller="glossary" action="generateSeoUrl"}';

        me.callParent(arguments);
    },

    updateProgressBars: function (window) {
        var me = this,
            taskConfig = window.taskConfig;

        me.window = window;

        me.callParent(arguments);

        if (!Ext.isEmpty(taskConfig.totalCounts.glossary)) {
            window.glossaryProgress.updateProgress(
                0, Ext.String.format(window.snippets[taskConfig.snippetResource].glossary, 0, taskConfig.totalCounts.glossary)
            );
        }
    },

    onStartSeoIndex: function (window) {
        var me = this, configs = [];

        me.updateProgressBars(window);

        configs.push(me.getSeoInitRequestConfig(window, me.requestConfig.seo));

        configs.push(me.getRequestConfig(window, 'articleProgress', 'seo', 'article'));
        configs.push(me.getRequestConfig(window, 'categoryProgress', 'seo', 'category'));
        configs.push(me.getRequestConfig(window, 'emotionProgress', 'seo', 'emotion'));
        configs.push(me.getRequestConfig(window, 'blogProgress', 'seo', 'blog'));
        configs.push(me.getRequestConfig(window, 'staticProgress', 'seo', 'static'));
        configs.push(me.getRequestConfig(window, 'contentProgress', 'seo', 'content'));
        configs.push(me.getRequestConfig(window, 'supplierProgress', 'seo', 'supplier'));
        configs.push(me.getRequestConfig(window, 'glossaryProgress', 'seo', 'glossary'));

        window.startButton.hide();
        window.cancelButton.show();
        window.cancelButton.enable();
        me.cancelOperation = false;

        me.runRequest(0, window, null, configs);
    }
});
//{/block}