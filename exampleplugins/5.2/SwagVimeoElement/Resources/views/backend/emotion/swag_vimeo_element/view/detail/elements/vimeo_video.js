//
//{block name="backend/emotion/view/detail/elements/base"}
//{$smarty.block.parent}
Ext.define('Shopware.apps.Emotion.view.detail.elements.VimeoVideo', {

    /**
     * Extend from the base class for the grid elements.
     */
    extend: 'Shopware.apps.Emotion.view.detail.elements.Base',

    /**
     * Create the alias matching with the xtype you defined for your element.
     * The pattern is always 'widget.detail-element-' + xtype
     */
    alias: 'widget.detail-element-emotion-components-vimeo',

    /**
     * You can define an additional CSS class which will be used for the grid element.
     */
    componentCls: 'emotion--vimeo-video',

    /**
     * Define the path to an image for the icon of your element.
     * You could also use a base64 string.
     */
    icon: 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACQAAAAkCAYAAADhAJiYAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAyZpVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+IDx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDUuNi1jMDY3IDc5LjE1Nzc0NywgMjAxNS8wMy8zMC0yMzo0MDo0MiAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvIiB4bWxuczp4bXBNTT0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL21tLyIgeG1sbnM6c3RSZWY9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9zVHlwZS9SZXNvdXJjZVJlZiMiIHhtcDpDcmVhdG9yVG9vbD0iQWRvYmUgUGhvdG9zaG9wIENDIDIwMTUgKFdpbmRvd3MpIiB4bXBNTTpJbnN0YW5jZUlEPSJ4bXAuaWlkOkI4MUE3MUYxMzE0OTExRTY5RTAxREU4QzRENjE3OTVFIiB4bXBNTTpEb2N1bWVudElEPSJ4bXAuZGlkOkI4MUE3MUYyMzE0OTExRTY5RTAxREU4QzRENjE3OTVFIj4gPHhtcE1NOkRlcml2ZWRGcm9tIHN0UmVmOmluc3RhbmNlSUQ9InhtcC5paWQ6QjgxQTcxRUYzMTQ5MTFFNjlFMDFERThDNEQ2MTc5NUUiIHN0UmVmOmRvY3VtZW50SUQ9InhtcC5kaWQ6QjgxQTcxRjAzMTQ5MTFFNjlFMDFERThDNEQ2MTc5NUUiLz4gPC9yZGY6RGVzY3JpcHRpb24+IDwvcmRmOlJERj4gPC94OnhtcG1ldGE+IDw/eHBhY2tldCBlbmQ9InIiPz6h+p8gAAAFjklEQVR42rRY208cVRj/mJ3d5X6/lUJBQQxqKW2hxWBFbeITvNQYtb5ArabRRx/6N/RP0KrQEC94S0pbY6NSlAqEi6VES0uhtCDlToG9sezN73eY3SyzM+wA8iVfzsw5Z875ne9+Jqal9TKpyb2+TiaTifx+P8XExIg+n89HsiyjfY7HTnL7Io8VMxfwcILyqSMQCEwyj0mS1MXft3N7h9/FOmA8Y12s5XS6IvaWyTg1gHmDWiwKwGhVlMyb7uM5x3jsHW7R18F8ibnZyCaSgTn1zEMMoIk3qQ12aoAhnbFXGGQTt7eVtXYF6CJzGy94kHZP5Qy0jdtPdwIojbmfgZyNJo3tEq/5Pjd9zKlGASUxgD7mo7QHpNhfpWwyAVRihFGvud2bO2S5k42xmPaQAMrr85VYLOZO5sNSjKQroSZ2yUNBV99LUsJIBT9+oaeykyzKBqNgjNoVNgZr2JKIR16vr5FfXw1pCPFE2aAZcQOTdIxxI/I5nbS2tk54tVgslJSYsOmb4Dyb3UFu9zrFx8WKvpVVOyUmxFNsrDViDw7EzTynMDwwvs0d+XpgAHSdo/eT5VUqLMijioNllBAfRwO3/6FHk48pJTkpFNW9Xq+YV3RgP5U//yzl5WaL/smpaersHhDROY5BhkuYxw9w8xZzq6zo8ryeCgDG7nCQy+Wm116upsqKF0Jj+Xm59FnLdzy2RlarRahmecVGtTVVdOxI+aZ1ykqLKTM9jb78/gpL1kxKFA+pn9/P86Fa0fsMd1TogVm12cnj8dK7b9ZvAhMkSAtzMHfpyQq9VH00AkyQsjLT6anCfHLyAdTmwBgO82MJC0eu05JOUDIYazx9SqhFiwr27xN5DaCgzurKQ1saOewIatURQJ3s8XiqYZxq+1lbc5NZNtO5hjcoaPhalJGWKjaBER/Iz6P5hSVx4syMNM35mIdMr+O51fCyUi0JoQ8bAczU9Cw9nJiimuNHIssF2SQ8DfPvjY6z4faLw3343mnRryYAtrIAdKhU5tPkaAGCezpdLmr66kdWnZNmZueptLhI2IGaUlOSaG5hkcxmWUhmbn6R3dwWAQgeCQ/UWkOhXEkrnwQlZDab2d09IpakpiTT8MiY5irZWRliHqSJQ2BDeKCa/h4eEaC3oAQpWjQOuijUN/Hv9JaGDUIAhKepCVK+e39c1znCU4fdSAqACqGWxaXliLEcllBaarKwteKiAqFaNf32R7eI7uHxR4McEkth1kj+2ojCPhp7OKE5DlAIkKfqXo8Yg+0M3xtj0CnRcuCMxNF1xAggLBTP6QLepkWI4h9/dEbTRq5cvyFUZWCfEYljQo9eDlNTHKttZm6BE6xLYyxWqE1NX/9wbSPJ8mEMVAg9kNBVozUMDBdqGRufMDT/evtNdoTHIhQYPPRVWNh95kGjNRAy9agBQB1/9tJfQ3dESIgGRpHcLeZRCVbPHReMSgllx6PJKRF39Ki9s4e6+waFoRt1GOYLaIM++A3zpFG14UQ//fq75njbz+3UOzAkwCiHNQIIIm8VcQheAeac1GBUbemcUEcfTNAvHV2h8hQ5quXby3R35AHl5mRtBwyyfwMqAHC4j7YzKC7yA43YJJqoYahIBTBaqHGBAyYA5GRnGjLg4HXcJEmfM5AbereOMzw4uFW5EVwMjEDn8/lFYYaQoK6vo6me94Ihn91UPdhsDvXcE7FW6y1PIFASTeQbCVgOBUOjNxFInyUz6vJ4T6AajXZztfsD/ipWW/9e3clYir0+v78Sucvo3R4ZtIoN8+L/DYal8wk3x1EY7OTvxwe8QL3yK2W31+dB1Mz8eG63/4eQWiqUH1YdO8DSoXyLW8W1aJO38wftksJlSO7MNcxPoz4LqzrtSoBFadmFUMI8vB30/wkwANX0n3B5XHA1AAAAAElFTkSuQmCC',

    /**
     * You can override the original `createPreview()` method
     * to create a custom grid preview for your element.
     *
     * @returns { string }
     */
    createPreview: function () {
        var me = this,
            preview = '',
            image = me.getConfigValue('vimeo_video_thumbnail'),
            style;

        if (Ext.isDefined(image)) {
            style = Ext.String.format('background-image: url([0]);', image);

            preview = Ext.String.format('<div class="x-emotion-banner-element-preview" style="[0]"></div>', style);
        }

        return preview;
    }
});
//{/block}