---
layout: default
title: Migration of the hybrid plugin
github_link: shopware-enterprise/b2b-suite/technical/migration.md
indexed: true
menu_title: Migration
menu_order: 24
menu_style: numeric
menu_chapter: true
group: Shopware Enterprise
subgroup: B2B-Suite
subsubgroup: Technical Documentation
---

# B2B the hybrid plugin
Recently we launched the nearly feature-complete version of the B2B-Suite.
Hence, in this article, we want to take a look at the changes which were made to support Shopware 6. 
Thereby we want to focus on the breaking changes and the resulting changes for plugin developers.

## Introduction to hybrid plugins

In the blog article "[Large Scale Plugin Architecture](https://developers.shopware.com/blog/2016/12/05/large-scale-plugin-architecture/)", my colleague Jan Philipp talked about the macro architecture of hybrid plugins like the B2B-Suite.
Sadly, we still had to introduce breaking changes to make the B2B-Suite compatible with both Shopware 6 and Shopware 5.
This is especially important for migrating an existing B2B-Suite project from Shopware 5 to Shopware 6.

More importantly, we want to list up the different hard breaks, which were not deprecated previously. 

## The new "bridge" layer

As described in the previously mentioned blog post, we use the dependency inversion principle to depend on an interface to wrap the data access, which is owned by our domain.

While just working with Shopware 5, the architecure was built like shown in the image below.

![image](/assets/img/b2b/DIP_SW5.svg)

Because of this, we only had to duplicate our bridge layer and adapt it accordingly to Shopware 6.

It was mostly rewriting the bridge or moving existing framework logic to the bridge layer, which is already implemented in Shopware 6.

![image](/assets/img/b2b/DIP_sw6.svg)


## The frontend bridge

Also, because of the usage of a frontend bridge, it was possible to do the same.

![image](/assets/img/b2b/DIP_SW5_Front.svg)

![image](/assets/img/b2b/DIP_SW6_Front.svg)

## The Plugin

As shown in our component guide, each components consist of multiple layers.

![image](/assets/img/b2b/component-layers.svg)

- Plugin
- Controllers
- Framework
- Shop bridge

Due to the changes in the plugin system, it was not possible to reuse the plugin itself. Therefore we had to rewrite it.

It resulted in the following layering:

![image](/assets/img/b2b/components-layers-sw6.svg)

## IdValue

As seen in the previously shown graphics, we had to change multiple places to support Shopware 6, but this is not all.

Due to the field changes of ids from `int` to `binary`, we had to change our internal logics as well.

Unfortunately, we could not deprecate them, because it would require to deprecate all repositories as well, to be consistent.

E.g., one of our main tables, which is used to assign contacts to debtors, is `b2b_store_front_auth` table.

It uses a combination between `provider_key` and `provider_context`.

Thereby the `context` shows the table and the `key` is the corresponding id.

We had to introduce a way to use our current architecture with both id types for a hybrid plugin.

The new `IdValue` is an abstraction of id values.

It can represent three different values:

```
IntIdvalue -> Int
UUIdValue -> UUID
NullIdValue -> Null
```

If you would like to create a new `IdValue`, you just have to provide the `value` as a parameter to `Showpare\B2B\Common\Idvalue::create()`.

Thereby the IdValue creates the corresponding class based on the Shopware version and the value type.

Afterwards, you can receive the internal value via `$idValue->getValue()` and the storage value `$idVlaue->getStorageValue();`

For example a `UUIdValue->getValue()` would return the `Hex` value of the UUID and `UUIdValue->getStorageValue()` the corresponding `binary` value.

In contrast `IntIdValue->getValue()` and `IntIdValue->getStorageValue()` return `int` values.

### Changes in the Repository

So, but how does it look like in practice?

Often we use functions like `fetchOneById(int $id, ...): Entity;` in our repositories.

These had to chang to: 

```php
public function fetchOneById(IdValue $id, OwnershipContext $ownershipContext): ContactEntity
{
    $query = $this->connection->createQueryBuilder()
        ->select('*')
        ->from(self::TABLE_NAME, self::TABLE_ALIAS)
        ->where(self::TABLE_ALIAS . '.id = :id')
        ->setParameter('id', $id->getStorageValue());

    $this->filterByContextOwner($ownershipContext, $query);

    $contactData = $query->execute()->fetch(PDO::FETCH_ASSOC);

    return $this->createContactByContactData($contactData, (string) $id->getValue());
}
```

The most important part here is the change of the parameter `fetchOneById(IdValue $id, ...)` and that the usage of the storage value for filtering

```php
    ->setParameter('id', $id->getStorageValue());
```

### In your plugins

Since these changes are hard breaks, you have to change your code accordingly.

Let's take a look at a controller action that used the `fetchOneById` in the past.

The function `Shopware\B2B\Contact\Frontend\ContactCrontroller:detailAction`.

```php
public function detailAction(Request $request): array
{
    $id = (int) $request->requireParam('id');
    $ownershipContext = $this->authenticationService->getIdentity()->getOwnershipContext();

    return ['contact' => $this->contactRepository->fetchOneById($id, $ownershipContext)];
}
```

Here, you just had to change the `$id` variable to an `IdValue`, and we are done.

```php
public function detailAction(Request $request): array
{
    $id = IdValue::create($this->requireParam('id'));
    $ownershipContext = $this->authenticationService->getIdentity()->getOwnershipContext();

    return ['contact' => $this->contactRepository->fetchOneById($id, $ownershipContext)];
}
```

### Views

The introduction of the IdValues does not only affect the PHP-Code but also the usage in the templates.

Therefore it is not possible to just use the id.

You have to access the value via the function `IntIdValue->getValue()`.

In twig templates, it looks like this:

```html
<input
    name="id"
    value="{{ row.id.getValue }}"
>
```

In smarty templates:

```html
<input
    name="id"
    value="{$row->id->getValue()}"
>
```

## Views

The new Shopware version involves Twig as the template engine.
Therefore we changed our template accordingly to twig.
Smarty is still used for Shopware 5.

### JavaScript

The Shopware 5 plugin system had a state manager capable of handling jQuery plugins provided by a plugin.

Since Shopware 6 is just using jQuery slim, it does not include the complete functionality which we require. 

Therefore we added different jQuery polyfills to complete the functionality.
Also, we added a new `PluginInstance`, which provides the prototype of the Shopware 5 plugins.

Currently, we are working on replacing these plugins with a TypeScript approach to ensure a higher quality and stability - while making full use of the new storefront plugin system of Shopware 6.
The existing plugins can be extended simply by accessing the jQuery object, which contains each plugin instance as an object.
```JavaScript
$.ajaxPanel.init = () => {
	// Your code here
};
```
In the future, the new TypeScript based plugins can be extended just by following the [official Shopware 6 documentation](https://docs.shopware.com/en/shopware-platform-dev-en/how-to/extend-core-js-storefront-plugin?category=shopware-platform-dev-en/how-to).

## Database

With Shopware 6, a new custom field management was introduced, so we had to change the storage of our customer-related attributes. 

For this, we introduced the new table `b2b_customer_data` for the customer's attributes.

It is the counterpart to the added `s_user_attributes` fields in Shopware 5 and shows debtors and sales representatives.

## Administration/Backend

Since the administration interface implementation is related to Shopware, we had to recreate the administration module with Vue.

For the implementation, we added a new controller layer that depends on Shopware for the administration.

It behaves like the Shopware 5 backend controllers.

It is shown in the [plugin chapter](#the-plugin) of this article.

## PHP version

With the new B2B-Suite release, we dropped some PHP version. So the minimal PHP version is 7.2.

With the drop, we also applied some of the latest PHP features.

The most recognizable change is the appliance of the `void` return type.

We know that this is a breaking change, but because of the amount of deprecations this would involve, because of the already existing breaking changes and because of improvement of security, we think this is bearable.

This change results in a fatal error if you extend an existing function, which got a new `void` return.

```
FATAL ERROR Declaration of bar::foo() must be compatible with betterBar::foo(): void on line number x
```

### How to fix the fatal error

For plugin developers, fixing the problem should be easy as long you have programmed according to our doc types.

So

```php
public function foo() 
{
}
```

becomes

```php
public function foo(): void
{
}
```

# Conclusion

As seen in this article, the work for migrating the B2B-Suite got much easier because of our chosen architecture.

Nevertheless, we couldn't do it without breaking changes.

Hopefully, this article helped you to overcome the changes which were introduced with the migration.
