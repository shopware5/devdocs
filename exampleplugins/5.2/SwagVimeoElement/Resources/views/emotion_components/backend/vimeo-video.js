// {namespace name="backend/emotion/swag_vimeo_element"}
//{block name="emotion_components/backend/vimeo_video"}
Ext.define('Shopware.apps.Emotion.view.components.VimeoVideo', {

    /**
     * Extend from the base class for the emotion components
     */
    extend: 'Shopware.apps.Emotion.view.components.Base',

    /**
     * Create the alias matching the xtype you defined in your `createEmotionComponent()` method.
     * The pattern is always 'widget.' + xtype
     */
    alias: 'widget.emotion-components-vimeo',

    /**
     * Contains the translations of each input field which was created with the EmotionComponentInstaller.
     * Use the name of the field as identifier
     */
    snippets: {
        'vimeo_interface_color': {
            'fieldLabel': '{s name=interfaceColorFieldLabel}{/s}',
            'supportText': '{s name=interfaceColorSupportText}{/s}'
        }
    },

    /**
     * The constructor method of each component.
     */
    initComponent: function () {
        var me = this;

        /**
         * Call the original method of the base class.
         */
        me.callParent(arguments);

        /**
         * Get single fields you've created with the helper functions in your `Bootstrap.php` file.
         */
        me.videoThumbnailField = me.getForm().findField('vimeo_video_thumbnail');
        me.videoIdField = me.getForm().findField('vimeo_video_id');

        /**
         * For example you can register additional event listeners on your fields to handle some data.
         */
        me.videoIdField.on('change', Ext.bind(me.onIdChange, me));
    },

    /**
     * Event listener for the change event of the video id field.
     *
     * @param field
     * @param value
     */
    onIdChange: function (field, value) {
        var me = this;

        me.setVimeoPreviewImage(value);
    },

    /**
     * Does a request to the vimeo api to get the preview image of the video.
     * We will use the hidden input field we created via the helper functions to
     * store the data we receive from the api.
     *
     * @param vimeoId
     * @returns { boolean }
     */
    setVimeoPreviewImage: function (vimeoId) {
        var me = this;

        if (!vimeoId) {
            return false;
        }

        /**
         * Create the url to the correct api endpoint for the given video id.
         */
        var url = Ext.String.format('https://vimeo.com/api/v2/video/[0].json', vimeoId),
            xhr = new XMLHttpRequest(),
            response;

        /**
         * Request additional information about the video.
         */
        xhr.onreadystatechange =  function () {
            if (xhr.readyState === 4 && xhr.status === 200) {
                response = Ext.JSON.decode(xhr.responseText);

                /**
                 * Save the preview image in the hidden input field.
                 */
                if (response[0]) {
                    me.videoThumbnailField.setValue(response[0]['thumbnail_large']);
                }
            }
        };

        xhr.open('GET', url, true);
        xhr.send();
    }
});
//{/block}
