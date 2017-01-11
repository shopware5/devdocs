---
layout: default
title: Custom shopping world elements
github_link: developers-guide/custom-shopping-world-elements/index.md
shopware_version: 5.2.0
indexed: true
tags:
  - shopping worlds
  - widgets
group: Developer Guides
subgroup: Tutorials
menu_title: Create custom shopping worlds elements
menu_order: 10
---

<img src="img/screenshot_emotion_elements.jpg" alt="Shopping worlds" />

The shopping worlds are one highlight feature of Shopware. Here you can edit various impressions of your shop within one design view in the backed, where you can freely position products, images, banners, HTML text, videos and much more. As a developer you are able to extend the module with new interesting elements which the user can place on his page.

<div class="toc-list"></div>

## Registering a new element ##
For creating custom shopping world elements Shopware provides some helper functions which can be used in the `Bootstrap.php` of a [Shopware plugin](/developers-guide/plugin-quick-start/). So all you have to do is to create a simple plugin where you can register one ore more elements via the `createEmotionComponent()` method. As an example for this tutorial we will create a Vimeo element for adding videos to the shopping world.

```php
$vimeoElement = $this->createEmotionComponent([
    'name' => 'Vimeo Video',
    'xtype' => 'emotion-components-vimeo',
    'template' => 'emotion_vimeo',
    'cls' => 'emotion-vimeo-element',
    'description' => 'A simple vimeo video element for the shopping worlds.'
]);
```
In the `install()` method of our plugin we register a new element and save it in the variable `$vimeoElement` for later use. The `createEmotionComponent()` method expects a configuration array with the following properties:

<table cellpadding="0" cellspacing="0" width="100%">
    <tr>
        <th>Property</th>
        <th>Type</th>
        <th>Required</th>
        <th>Description</th>
    </tr>
    <tr>
        <td><code>name</code></td>
        <td><code>string</code></td>
        <td>required</td>
        <td>The name for the element</td>
    </tr>
    <tr>
        <td><code>template</code></td>
        <td><code>string</code></td>
        <td>required</td>
        <td>The name of the template file which should be used for the frontend theme.</td>
    </tr>
    <tr>
        <td><code>xtype</code></td>
        <td><code>string</code></td>
        <td>optional</td>
        <td>The xtype of a custom ExtJS component which will be used for the element settings in the backend. When you set the xtype you have to provide the corresponding ExtJS component, otherwise the element will throw an error.</td>
    </tr>
    <tr>
        <td><code>cls</code></td>
        <td><code>string</code></td>
        <td>optional</td>
        <td>Define a CSS class which will be used for the element template in the frontend theme.</td>
    </tr>
    <tr>
        <td><code>description</code></td>
        <td><code>string</code></td>
        <td>optional</td>
        <td>A short description which will be shown for your element in the shopping world module.</td>
    </tr>
</table>

## Adding configuration fields to the element ##
After registering the new element we can add different form fields to the element which can be filled by the user to configure the element. For each type of field there is a helper function which can be called on the newly registered component. We will add some configuration fields to our example element for the different embed options the Vimeo platform offers. 

```php
$vimeoElement->createTextField([
    'name' => 'vimeo_video_id',
    'fieldLabel' => 'Video ID',
    'supportText' => 'Enter the ID of the video you want to embed.',
    'allowBlank' => false
]);

$vimeoElement->createHiddenField([
    'name' => 'vimeo_video_thumbnail'
]);

$vimeoElement->createTextField([
    'name' => 'vimeo_interface_color',
    'fieldLabel' => 'Interface Color',
    'supportText' => 'Enter the #hex color code for the video player interface.',
    'defaultValue' => '#0096FF'
]);

$vimeoElement->createCheckboxField([
    'name' => 'vimeo_autoplay',
    'fieldLabel' => 'Autoplay',
    'defaultValue' => false
]);

$vimeoElement->createCheckboxField([
    'name' => 'vimeo_loop',
    'fieldLabel' => 'Loop',
    'defaultValue' => false
]);

$vimeoElement->createCheckboxField([
    'name' => 'vimeo_show_title',
    'fieldLabel' => 'Show title',
    'defaultValue' => false
]);

$vimeoElement->createCheckboxField([
    'name' => 'vimeo_show_portrait',
    'fieldLabel' => 'Show portrait',
    'defaultValue' => false
]);

$vimeoElement->createCheckboxField([
    'name' => 'vimeo_show_author',
    'fieldLabel' => 'Show author',
    'defaultValue' => false
]);
```

There are several methods for nearly any kind of form field. Here is a list of all available methods and their possible options:

<table cellpadding="0" cellspacing="0" width="100%">
    <tr>
        <th>Field / Method</th>
        <th width="220">Options</th>
        <th>Example / Info</th>
    </tr>
    <tr>
        <td><h5>Text field</h5><code>createTextField()</code></td>
        <td>
            <ul>
                <li><code>name</code></li>
                <li><code>defaultValue</code></li>
                <li><code>fieldLabel</code></li>
                <li><code>supportText</code></li>
                <li><code>helpTitle</code></li>
                <li><code>helpText</code></li>
                <li><code>allowBlank</code></li>
            </ul>
        </td>
        <td><img src="img/screen_textfield.jpg" alt="text field" /></td>
    </tr>
    <tr>
        <td><h5>Checkbox</h5><code>createCheckboxField()</code></td>
        <td>
            <ul>
                <li><code>name</code></li>
                <li><code>fieldLabel</code></li>
                <li><code>supportText</code></li>
                <li><code>helpTitle</code></li>
                <li><code>helpText</code></li>
                <li><code>allowBlank</code></li>
            </ul>
        </td>
        <td><img src="img/screen_checkbox.jpg" alt="checkbox" /></td>
    </tr>
    <tr>
        <td><h5>Radio field</h5><code>createRadioField()</code></td>
        <td>
            <ul>
                <li><code>name</code></li>
                <li><code>defaultValue</code></li>
                <li><code>fieldLabel</code></li>
                <li><code>supportText</code></li>
                <li><code>helpTitle</code></li>
                <li><code>helpText</code></li>
                <li><code>allowBlank</code></li>
            </ul>
        </td>
        <td>
            <img src="img/screen_radiofield.jpg" alt="radio field" />
            <br /><br />
            <p>You can create several radio fields with the same name to create a radio group.<br />
            The <code>defaultValue</code> can be used to define the input value of each field.<br />
            The <code>supportText</code> can be used as the box label of each single field.</p>
        </td>
    </tr>
    <tr>
        <td><h5>Combobox</h5><code>createComboBoxField()</code></td>
        <td>
            <ul>
                <li><code>name</code></li>
                <li><code>fieldLabel</code></li>
                <li><code>defaultValue</code></li>
                <li><code>store</code></li>
                <li><code>displayField</code></li>
                <li><code>valueField</code></li>
                <li><code>supportText</code></li>
                <li><code>helpTitle</code></li>
                <li><code>helpText</code></li>
                <li><code>allowBlank</code></li>
            </ul>
        </td>
        <td>
            <img src="img/screen_combobox.jpg" alt="combobox" />
            <br /><br />
            <p>Define the class name of a <code>store</code> you want to use for the combobox. It can either be one of the Shopware base stores in the <code>Shopware.apps.Base.store</code> namespace, or a custom store you will provide in your own ExtJS component for the element.</p>
        </td>
    </tr>
    <tr>
        <td><h5>Numberfield</h5><code>createNumberField()</code></td>
        <td>
            <ul>
                <li><code>name</code></li>
                <li><code>defaultValue</code></li>
                <li><code>fieldLabel</code></li>
                <li><code>supportText</code></li>
                <li><code>helpTitle</code></li>
                <li><code>helpText</code></li>
                <li><code>allowBlank</code></li>
            </ul>
        </td>
        <td><img src="img/screen_numberfield.jpg" alt="number field" /></td>
    </tr>
    <tr>
        <td><h5>Time field</h5><code>createTimeField()</code></td>
        <td>
            <ul>
                <li><code>name</code></li>
                <li><code>defaultValue</code></li>
                <li><code>fieldLabel</code></li>
                <li><code>supportText</code></li>
                <li><code>helpTitle</code></li>
                <li><code>helpText</code></li>
                <li><code>allowBlank</code></li>
            </ul>
        </td>
        <td><img src="img/screen_timefield.jpg" alt="time field" /></td>
    </tr>
    <tr>
        <td><h5>Date field</h5><code>createDateField()</code></td>
        <td>
            <ul>
                <li><code>name</code></li>
                <li><code>defaultValue</code></li>
                <li><code>fieldLabel</code></li>
                <li><code>supportText</code></li>
                <li><code>helpTitle</code></li>
                <li><code>helpText</code></li>
                <li><code>allowBlank</code></li>
            </ul>
        </td>
        <td><img src="img/screen_datefield.jpg" alt="date field" /></td>
    </tr>
    <tr>
        <td><h5>Text area</h5><code>createTextAreaField()</code></td>
        <td>
            <ul>
                <li><code>name</code></li>
                <li><code>defaultValue</code></li>
                <li><code>fieldLabel</code></li>
                <li><code>supportText</code></li>
                <li><code>helpTitle</code></li>
                <li><code>helpText</code></li>
                <li><code>allowBlank</code></li>
            </ul>
        </td>
        <td><img src="img/screen_textarea.jpg" alt="text area" /></td>
    </tr>
    <tr>
        <td><h5>Text editor</h5><code>createTinyMceField()</code></td>
        <td>
            <ul>
                <li><code>name</code></li>
                <li><code>defaultValue</code></li>
                <li><code>fieldLabel</code></li>
                <li><code>supportText</code></li>
                <li><code>helpTitle</code></li>
                <li><code>helpText</code></li>
                <li><code>allowBlank</code></li>
            </ul>
        </td>
        <td><img src="img/screen_texteditor.jpg" alt="text editor" /></td>
    </tr>
    <tr>
        <td><h5>HTML editor</h5><code>createHtmlEditorField()</code></td>
        <td>
            <ul>
                <li><code>name</code></li>
                <li><code>defaultValue</code></li>
                <li><code>fieldLabel</code></li>
                <li><code>supportText</code></li>
                <li><code>helpTitle</code></li>
                <li><code>helpText</code></li>
                <li><code>allowBlank</code></li>
            </ul>
        </td>
        <td><img src="img/screen_htmleditor.jpg" alt="html editor" /></td>
    </tr>
    <tr>
        <td><h5>Media field</h5><code>createMediaField()</code></td>
        <td>
            <ul>
                <li><code>name</code></li>
                <li><code>defaultValue</code></li>
                <li><code>fieldLabel</code></li>
                <li><code>supportText</code></li>
                <li><code>helpTitle</code></li>
                <li><code>helpText</code></li>
                <li><code>allowBlank</code></li>
                <li><code>valueField (since Shopware 5.2.14)</code></li>
            </ul>
        </td>
        <td>
            <img src="img/screen_mediafield.jpg" alt="media field" />
            <br /><br />
            <p>The <code>valueField</code> allows you to control which property of the MediaModel is returned as value</p>
            <p><strong>Example:</strong></p>
            <pre>
$emotionElement->createMediaField([
    'name' => 'preview_image',
    'fieldLabel' => 'The preview image',
    'valueField' => 'virtualPath'
]);
            </pre>
            <p>You can find possible properties in this file: <code>../themes/Backend/ExtJs/backend/media_manager/model/media.js</code></p>
        </td>
    </tr>
    <tr>
        <td><h5>Display field</h5><code>createDisplayField()</code></td>
        <td>
            <ul>
                <li><code>name</code></li>
                <li><code>defaultValue</code></li>
                <li><code>fieldLabel</code></li>
                <li><code>supportText</code></li>
                <li><code>helpTitle</code></li>
                <li><code>helpText</code></li>
            </ul>
        </td>
        <td>
            <img src="img/screen_displayfield.jpg" alt="display field" />
            <br /><br />
            <p>The <code>defaultValue</code> will be used for the displayed value.</p>
        </td>
    </tr>
    <tr>
        <td><h5>Hidden field</h5><code>createHiddenField()</code></td>
        <td>
            <ul>
                <li><code>name</code></li>
                <li><code>defaultValue</code></li>
                <li><code>valueType</code></li>
                <li><code>allowBlank</code></li>
            </ul>
        </td>
        <td>
            <p>You can create hidden fields to save additional data, for example from other custom fields of your ExtJS component. Set the <code>valueType</code> to <code>json</code> to save some JSON encoded data in the field.</p>
        </td>
    </tr>
</table>

## Creating a frontend template for the element ##
<img src="img/screen_template_structure.jpg" class="is-float-right" alt="template directory structure" />
After registering the element and creating all the configuration fields we already see a full functional shopping world element in the backend module which can be placed on the design canvas. All we have to do now is to provide a frontend template to define the layout in the store. In the `Views` directory of our plugin we create the necessary directory structure to the file. Template files for shopping world elements can automatically be added by creating the hierarchy structure in the special directory called `emotion_components`. The full path to the template file would be `Views/emotion_components/widgets/emotion/components/{name}.tpl`.
 
The name of the file has to match the definition in your `createEmotionComponent()` method. You can access your configuration fields inside the template file as properties of the `$Data` smarty variable. Let's create the embed code for displaying the Vimeo video.

```
{block name="widgets_emotion_components_vimeo_element"}

    {$videoURL = "https://player.vimeo.com/video/{$Data.vimeo_video_id}?color={$Data.vimeo_interface_color|substr:1}"}

    {if !$Data.vimeo_show_title}
        {$videoURL = "{$videoURL}&title=0"}
    {/if}

    {if !$Data.vimeo_show_portrait}
        {$videoURL = "{$videoURL}&portrait=0"}
    {/if}

    {if !$Data.vimeo_show_author}
        {$videoURL = "{$videoURL}&byline=0"}
    {/if}

    {if $Data.vimeo_loop}
        {$videoURL = "{$videoURL}&loop=1"}
    {/if}

    {if $Data.vimeo_autoplay}
        {$videoURL = "{$videoURL}&autoplay=1"}
    {/if}

    <iframe src="{$videoURL}"
            width="100%"
            height="100%"
            frameborder="0"
            webkitallowfullscreen
            mozallowfullscreen
            allowfullscreen>
    </iframe>
{/block}
```

## Process the element data before output ##
When you have to process the saved element data before it is passed to the frontend, you have the possibility to register to the `Shopware_Controllers_Widgets_Emotion_AddElement` controller event. Here you get the original data to manipulate the output.

```php
public function install()
{
    // ...
    
    $this->subscribeEvent(
        'Shopware_Controllers_Widgets_Emotion_AddElement',
        'onEmotionAddElement'
    );
}

public function onEmotionAddElement(Enlight_Event_EventArgs $args)
{
    $element = $args->get('element');

    if ($element['component']['xType'] !== 'emotion-components-vimeo') {
        return;
    }

    $data = $args->getReturn();
    
    // Do some stuff with the element data
    
    $args->setReturn($data);
}
```

Because the event is called for every element we have to do a check before processing the data. You can get the element info from the event arguments with `$args->get('element')`. To test for a specific element we can validate the defined `xType`. When the element is the right one we can get the data of the configuration form with `$args->getReturn()`. After processing the data we have to set the new output for the frontend with `$args->setReturn($data)`.

## Advanced: Adding a custom emotion component in ExtJS ##
<img src="img/screen_component_structure.jpg" class="is-float-right" alt="backend component directory structure" />
If you want to go a little further by creating custom configuration fields for your element you have the possibility to create your own ExtJS component for the element. Here you have full access to the configuration form in ExtJS. You can manipulate existing fields or add new fields which are more complex than the standard form elements.

The file for the component is also located in the `emotion_components` directory, so it will be detected automatically. The complete path to the file is `Views/emotion_components/backend/{name}.js`.

For the Vimeo example we use the custom ExtJS component to make a call to the Vimeo api for receiving information about the preview image of the video and save it in the hidden input we already created via the helper functions.

```
//{block name="emotion_components/backend/vimeo_video"}
Ext.define('Shopware.apps.Emotion.view.components.VimeoVideo', {

    extend: 'Shopware.apps.Emotion.view.components.Base',

    alias: 'widget.emotion-components-vimeo',

    initComponent: function () {
        var me = this;

        me.callParent(arguments);

        me.videoThumbnailField = me.getForm().findField('vimeo_video_thumbnail');
        me.videoIdField = me.getForm().findField('vimeo_video_id');

        me.videoIdField.on('change', Ext.bind(me.onIdChange, me));
    },

    onIdChange: function (field, value) {
        var me = this;

        me.setVimeoPreviewImage(value);
    },

    setVimeoPreviewImage: function (vimeoId) {
        var me = this;

        if (!vimeoId) {
            return false;
        }

        var url = Ext.String.format('https://vimeo.com/api/v2/video/[0].json', vimeoId),
            xhr = new XMLHttpRequest(),
            response;

        xhr.onreadystatechange =  function () {
            if (xhr.readyState === 4 && xhr.status === 200) {
                response = Ext.JSON.decode(xhr.responseText);

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
```

The component always has to extend the base class `Shopware.apps.Emotion.view.components.Base`. In the `alias` property it is important to set the `xtype` we already defined during the creation of the element.

In the component you can get access to the fields which you already created by using the `findField()` method on the `form` object which can be received by `this.getForm()`.

## Advanced: Adding a custom designer component in ExtJS ##
<img src="img/screen_grid_elements.jpg" class="is-float-right" alt="grid elements" />
Since Shopware 5.2 you are able to create a custom ExtJS component for the designer elements. Here you have the possibility to add an icon and a preview template for the element, which gets shown in the grid of the designer.

For extending the designer components we have to do a classic template extension of the backend files. So we create a new file in the necessary template hierarchy `Views/backend/emotion/{pluginName}/view/detail/elements`. 

Otherwise than the custom emotion component we have to register the template manually by extending the template inheritance system with our new file. We can subscribe to the `PostDispatch` event of the emotion module in the `install()` method of our plugin to do so.

```php
public function install()
{
    // ...
    
    $this->subscribeEvent(
        'Enlight_Controller_Action_PostDispatchSecure_Backend_Emotion',
        'onPostDispatchBackendEmotion'
    );
}

public function onPostDispatchBackendEmotion(Enlight_Controller_ActionEventArgs $args)
{
    $controller = $args->getSubject();
    $view = $controller->View();

    $view->addTemplateDir($this->Path() . 'Views/');
    $view->extendsTemplate('backend/emotion/vimeo_element/view/detail/elements/vimeo_video.js');
}
```

Now that we added our template file we can add our custom component by extending the Smarty `{block}` of the base class.

```
//
//{block name="backend/emotion/view/detail/elements/base"}
//{$smarty.block.parent}
Ext.define('Shopware.apps.Emotion.view.detail.elements.VimeoVideo', {

    extend: 'Shopware.apps.Emotion.view.detail.elements.Base',

    alias: 'widget.detail-element-emotion-components-vimeo',

    componentCls: 'emotion--vimeo-video',

    icon: 'data:image/png;base64,...',

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
```

<div class="alert alert-info">
When you want to use a custom component for the designer, it is necessary to define a xtype in the createEmotionComponent() method for your element.
</div>

In the component we can extend the base class of the designer elements `Shopware.apps.Emotion.view.detail.elements.Base`. It is important to set the `alias` property to the correct xtype of the element with the prefix `widget.detail-element-`. In the component you have the possibility to override the following options:

<table cellpadding="0" cellspacing="0" width="100%">
    <tr>
        <th width="160">Property</th>
        <th width="100">Type</th>
        <th>Description</th>
    </tr>
    <tr>
        <td><code>icon</code></td>
        <td><code>string</code></td>
        <td>The path to an image which will be used for the <code>src</code> attribute of the icon.<br />For example it could also be a base64 string of the image data.</td>
    </tr>
    <tr>
        <td><code>compCls</code></td>
        <td><code>string</code></td>
        <td>A CSS class which will be added to the element in the designer grid.</td>
    </tr>
    <tr>
        <td><code>createPreview</code></td>
        <td><code>function</code></td>
        <td>A method for creating the preview of the element.<br />It will be shown in the grid of the designer.<br />Returns a HTML string.</td>
    </tr>
    <tr>
        <td><code>minRows</code></td>
        <td><code>number</code></td>
        <td>The minimum number of rows the element can be aligned to in the grid.</td>
    </tr>
    <tr>
        <td><code>maxRows</code></td>
        <td><code>number</code></td>
        <td>The maximum number of rows the element can be aligned to in the grid.</td>
    </tr>
    <tr>
        <td><code>minCols</code></td>
        <td><code>number</code></td>
        <td>The minimum number of columns the element can be aligned to in the grid.</td>
    </tr>
    <tr>
        <td><code>maxCols</code></td>
        <td><code>number</code></td>
        <td>The maximum number of columns the element can be aligned to in the grid.</td>
    </tr>
</table>

## Downloads ##
You can download the complete example plugin with documented code here:

**Example Plugin**: <a href="{{ site.url }}/exampleplugins/SwagVimeoElement.zip">Download</a>.
