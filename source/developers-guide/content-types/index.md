---
layout: default
title: Content Types
github_link: developers-guide/content-types/index.md
shopware_version: 5.6.0
indexed: true
menu_title: Content-Types
menu_order: 100
group: Developer Guides
subgroup: General Resources
---

Content Types allows users to create own simple entities with a crud, own api and frontend controller.

## General

Content Types can be created using an interface in the administration in "Settings" => "Content Types" or with a 'contenttypes.xml' in the Resources folder of your Plugin. If a Content Type is created using a plugin, it can't be modified in the administration. In this guide we will create a Content Type using a plugin.

## Creating a Content Type

A plugin Content Type can be created with a 'Resources/contenttypes.xml' in your plugin directory. A XML file can contain multiple Content Types.

```xml
<?xml version="1.0" encoding="utf-8"?>
<contentTypes xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
xsi:noNamespaceSchemaLocation="../../../../engine/Shopware/Bundle/ContentTypeBundle/Resources/contenttypes.xsd">
    <types>

    </types>
</contentTypes>
```

Now we add a Content Type in the `types` tag.

A Content Type needs as a requirement a typeName (technical name, will be used for the controllers, table, etc. with the Prefix 'Custom'), a name for the Menu and Storefront if enabled.

In this example we will create a Content Type with the technical name 'AwesomeRecipeContentType', display name 'My most favorite recipes by name' with a single field 'name' as textfield.

```xml
<?xml version="1.0" encoding="utf-8"?>
<contentTypes xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
xsi:noNamespaceSchemaLocation="../../../../engine/Shopware/Bundle/ContentTypeBundle/Resources/contenttypes.xsd">
    <types>
        <type>
            <typeName>AwesomeRecipeContentType</typeName>
            <name>My most favorite recipes by name</name>
            <fieldSets>
                <fieldSet>
                    <field name="name" type="text">
                    <label>Name</label>
                    <showListing>true</showListing>
                    </field>
                </fieldSet>
            </fieldSets>
        </type>
    </types>
</contentTypes>
```

After the plugin installation and activation, will be a new menu entry in the 'Content' section.

### Default elements

| Name          | DBAL Type | ExtJs Type                                 |
|---------------|-----------|--------------------------------------------|
| textarea      | text      | Ext.form.field.TextArea                    |
| text          | string    | Ext.form.field.Text                        |
| aceeditor     | text      | Shopware.form.field.AceEditor              |
| integer       | int       | Ext.form.field.Number                      |
| tinymce       | text      | Shopware.form.field.TinyMCE                |
| media         | int       | Shopware.form.field.Media                  |
| combobox      | string    | Ext.form.field.Combobox                    |
| checkbox      | int       | Ext.form.field.Checkbox                    |
| date          | date      | Ext.form.field.Date                        |
| media-grid    | string    | Shopware.form.field.MediaGrid              |
| product-field | int       | Shopware.form.field.ProductSingleSelection |
| product-grid  | string    | Shopware.form.field.ProductGrid            |
| shop-field    | int       | Shopware.form.field.SingleSelection        |
| shop-grid     | string    | Shopware.form.field.ShopGrid               |

### Dynamic elements

Content types can also reference each other. For this reason we have dynamic elements.

With '[technicalName]-field' can you create an association to a single selection of content type 'technicalName'

With '[technicalName]-grid' can you create an association to a multi selection of content type 'technicalName'

### Possible tags in the '&lt;type&gt;' tag

| Name                     | Description                                                                                                                             |
|--------------------------|-----------------------------------------------------------------------------------------------------------------------------------------|
| typeName                 | Technical name of the content type                                                                                                      |
| name                     | Display name                                                                                                                            |
| showInFrontend           | Create a frontend controller, allow usage of emotion element. Requires viewTitleFieldName, viewDescriptionFieldName, viewImageFieldName |
| menuIcon                 | Menu icon                                                                                                                               |
| menuPosition             | Menu position                                                                                                                           |
| menuParent               | Parent controller name                                                                                                                  |
| viewTitleFieldName       | Fieldname for title fields in storefront                                                                                                |
| viewDescriptionFieldName | Fieldname for description fields in storefront                                                                                          |
| viewImageFieldName       | Fieldname for image fields in storefront                                                                                                |
| seoUrlTemplate           | SEO URL template for the URL generation                                                                                                 |
### Possible tags in '&lt;field&gt;'
| Name          | Description                                                   |
|---------------|---------------------------------------------------------------|
| name          | Technical fieldname in table                                  |
| label         | Label for the user                                            |
| type          | Field type                                                    |
| helpText      | Field helpText                                                |
| description   | Field description                                             |
| translateable | Field is translateable?                                       |
| required      | Field is required?                                            |
| options       | Can be used to pass variables to extjs                        |
| custom        | Can be used to store custom variables                         |
| store         | Options for combobox selection                                |
| showListing   | Show the field in the extjs listing window                    |
| searchAble    | Field should be searchable in the extjs listing window search |

## Accesing using the API

All Content Types have an API endpoint generated automatically. It will be accessible using the route '/api/Custom[TechnicalName]' (e.g '/api/CustomAwesomeRecipeContentType') and follows the default Shopware API schema.

In default, it reads the data in raw format, without resolving the associated data and translation.

To resolve the associations, you can pass a GET parameter '?resolve=1'. To load the translations, can you pass a GET parameter '?loadTranslations=1' to the list and get one call.

The data being passed to create or update an entity has to be in the raw format. Multi selection fields values have to been splitted by pipe (e.g '|1|2|').

## Usage internal in PHP

### Getting the Content Type configuration
The Content Type configuration will be saved as struct of type 'Shopware\Bundle\ContentTypeBundle\Structs\Type'. To get the configuration of your Content Type you can use the service 'shopware.bundle.content_type.type_provider' and call the method 'getType' with your technical name.

### Fetching the Content Type data

Every Content Types has an own Repository which implements the interface 'Shopware\Bundle\ContentTypeBundle\Services\RepositoryInterface'. These repositories are registered dynamically in the DI with following naming scheme 'shopware.bundle.content_type.[technicalName]'.

#### Example usages

```php

/** @var \Shopware\Bundle\ContentTypeBundle\Services\RepositoryInterface $repository */
$repository = $this->container->get('shopware.bundle.content_type.store');

$criteria = new \Shopware\Bundle\ContentTypeBundle\Structs\Criteria();
$criteria->limit = 5;
$criteria->loadTranslations = true;
$criteria->loadAssociations = true;
$criteria->calculateTotal = true;

/** @var \Shopware\Bundle\ContentTypeBundle\Structs\SearchResult $result */
$result = $repository->findAll($criteria);

var_dump($result->total); // 5
var_dump($result->type); // Type struct
var_dump($result->items); // Fetched data

// Delete a record
$repository->delete($id);

// If an id is passed in $data, it will be updated, otherwise it will create a new record
$repository->save($data);
```

## Displaying in Frontend

To enable the Frontend controller, you have to set 'showInFrontend' in the 'type' to 'true' and fill the fields 'viewTitleFieldName', 'viewDescriptionFieldName', 'viewImageFieldName'.

These view fields will be used for SEO information in the '&lt;head&gt;'-tag, as well as in the listing of the contents and in the emotion world.

The controller name is generated, like in the API, with the same schema 'Custom[TechnicalName]'.
By default the controller tries to load the template in the default directory structure (`frontend/controller/action.tpl`) and if that template is missing, it willl fall back to the folder `frontend/content_type/action.tpl`).

In the default template, only fields are visible in the frontend, which implements the interface 'Shopware\Bundle\ContentTypeBundle\Field\TemplateProvidingFieldInterface'

## Translation in Backend

To translate the extjs field names can you create a new snippet file in namespace 'backend/custom[technicalName]/main'.

| Snippet-Name | Description |
|-------------------------|------------------------------------------|
| name | content type name in backend |
| [fieldName]_label | field label for the frontend and backend |
| [fieldName]_helpText | field helpText for the backend |
| [fieldName]_description | field description for the backend |

## Translation in Frontend

To translate the extjs field names can you create a new snippet file in namespace 'frontend/custom[technicalName]/index'.

| Snippet-Name | Description |
|----------------------|------------------------------|
| IndexMetaDescription | meta description in '&lt;head&gt;' |
| IndexMetaImage | meta image in '&lt;head&gt;' |
| IndexMetaTitle | meta title in '&lt;head&gt;' |

## Creating a new Field

To create a new field, you have to create a new class which implements the 'Shopware\Bundle\ContentTypeBundle\Field\FieldInterface'.

Here we have an example field MediaField.

```php

use Doctrine\DBAL\Types\Type;
use Shopware\Bundle\ContentTypeBundle\Structs\Field;

class MediaField implements FieldInterface
{
public static function getDbalType(): string
{
return Type::INTEGER;
}

public static function getExtjsField(): string
{
return 'shopware-media-field';
}

public static function getExtjsType(): string
{
return 'int';
}

public static function getExtjsOptions(Field $field): array
{
return [];
}

public static function isMultiple(): bool
{
return false;
}
}
```

Overview of the methods

| Method | Description |
|-----------------|--------------------------------------------------------|
| getDbalType | Returns the DBAL Type for the column |
| getExtjsField | Returns the extjs xtype for the field |
| getExtjsType | Returns the extjs model type |
| getExtjsOptions | Returns an array of options for the extjs configuration |
| isMultiple | Returns that this field holds multiple values |

After the creating of the class, we have to register our new field in the 'services.xml' with the tag 'shopware.bundle.content_type.field' and a 'fieldName'.

```xml
<service id="Shopware\Bundle\ContentTypeBundle\Field\MediaField" class="Shopware\Bundle\ContentTypeBundle\Field\MediaField">
<tag name="shopware.bundle.content_type.field" fieldName="media"/>
</service>
```

The 'fieldName' is the unique identifier of your new field and can be used in the 'contenttypes.xml'. This also extends the selection in the extjs interface for the creation of a Content Type.

### ResolveableFieldInterface

If you want to populate the data after it has been read from the database, can you implement the interface 'Shopware\Bundle\ContentTypeBundle\Field\ResolveableFieldInterface' in your field .

This interface requires that you have to implement the method `getResolver`. In the `getResolver` method have you to return a service id of your resolver. The resolver will handle the processing of the saved data.

```php
public static function getResolver(): string
{
return MediaResolver::class;
}
```

### Resolver

A resolver has to extend 'Shopware\Bundle\ContentTypeBundle\FieldResolver\AbstractResolver' and needs to be registered in the DI container. The abstract class requires that you implement the method 'resolve'. The Content Type's repository reads all information from the database and then adds all fields that have to be resolved in the resolver using the 'add' method, defined in the 'AbstractResolver'. After all IDs are added, it will call the 'resolve()' method where the Resolver should fetch and store the data by the added IDs.

Here we have an example for the 'MediaField':

```php
public function resolve(): void
{
$medias = $this->mediaService->getList($this->resolveIds, $this->contextService->getShopContext());

foreach ($medias as $id => $media) {
$this->storage[$id] = $this->structConverter->convertMediaStruct($media);
}

$this->resolveIds = [];
}
```

The 'resolveIds' property contains all requested IDs of the values. After fetching these, we write the data back into the 'storage' property with the keys we had in 'propertyIds'.
In the last step the Repository loads the values back from the 'get' method implemented by the 'AbstractResolver' with the values in the 'storage' property.
This concept also has a simple cache inside, if the requested ID is already in the 'storage' proeprty, it won't be added to the 'resolveIds' property.

### TemplateProvidingFieldInterface

With the 'TemplateProvidingFieldInterface' interface can you mark your field as frontend ready with a specific template. This template will be loaded with the default template of the detail page.
