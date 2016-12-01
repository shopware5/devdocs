---
layout: default
title: Address Management Guide
github_link: developers-guide/address-management-guide/index.md
shopware_version: 5.2.0
indexed: true
tags:
  - address
  - services
group: Developer Guides
subgroup: General Resources
menu_title: Address Management
menu_order: 80
---

The address management allows a customer to manage more than only one address which gets changed with every order. The customer is now able to create more addresses, e.g. for home and work, and use them later on in an order without losing all existing address data. He can just change the reference to the default billing address, instead of changing it entirely.

<div class="toc-list"></div>

## Address Service

The address service is used to manage all address entities in Shopware. It only works with models which makes it easy to comprehend, which properties are available and can be used. To know which properties are available, please refer to the model `\Shopware\Models\Customer\Address` in the source code.

Please don't handle the persisting using Doctrine ORM yourself as you might risk data inconsistency.

### Create an address

Like said above, you have to pass an already complete address model and an existing customer to the address service. In case of an error, you'll get an exception.

```php
$address = new \Shopware\Models\Customer\Address();
$address->fromArray($addressData);

$customer = $this->get('models')->find(\Shopware\Models\Customer\Customer::class, 1);

$this->get('shopware_account.address_service')->create($address, $customer);
```

### Update an address

Updating an address is almost the same as creating one. The only difference is, that you don't have to provide the customer since the address is already associated with it.

Pretending that you already fetched an address in `$address`, your update call might look like this:

```php
$address->setStreet('Alternative Street 5');
$this->get('shopware_account.address_service')->update($address);
```

### Delete an address

The deletion of an address also requires an existing model. In case of errors, you'll get an exception.

Pretending that you already fetched an address in `$address`, your delete call might look like this:

```php
$this->get('shopware_account.address_service')->delete($address);
```

This call might throw an exception if you are trying to delete an address, which is associated with a default billing or shipping address of a customer.


### Set as default billing or shipping address

The service includes two methods for setting an address as new default billing or shipping address.

Pretending that you already fetched an address in `$address`, you can easily call the appropriate method:

```php
$this->get('shopware_account.address_service')->setDefaultBillingAddress($address);
$this->get('shopware_account.address_service')->setDefaultShippingAddress($address);
```

## Extending the address form

The addresses are now validated by a symfony form `\Shopware\Bundle\AccountBundle\Form\Account\AddressFormType`. If you want to add your own fields to it, you can subscribe to the `Shopware_Form_Builder` event and create or modify fields inside the `additional` field.

### Attributes

The address form also supports attributes. They will automatically be mapped to the attribute model if you follow the input naming conventions. This is an example for providing attributes with an HTML input field:

```html
<input type="text" name="address[attribute][text3]" />
```

### Custom data

If you don't want to use attributes, e.g. for temporary data transfer or non-persistent data, you can use the `additional` field. The address model now contains a new property `additional` which is declared as an array, a key/value store to be exact. This array will be filled with these form fields, you've added earlier using the event (see Example #1 below).

To correctly map the submitted data to your new fields, you have to follow the convention of using the additional array as field name like:

```html
<input type="text" name="address[additional][neighboursName]" />
```

You have access to your data by using `$address->getAdditional()`. The first example will show how to add fields to the address form.

#### Example #1: Adding a new field

This example will add a new field named `neighboursName` and it should not be empty.

##### Add to install() method in Bootstrap.php
```php
$this->subscribeEvent('Shopware_Form_Builder', 'onFormBuild');
```

##### Create method onFormBuild() in Bootstrap.php
```php
public function onFormBuild(\Enlight_Event_EventArgs $event)
{
    if ($event->getReference() !== \Shopware\Bundle\AccountBundle\Form\Account\AddressFormType::class) {
        return;
    }

    /** @var \Symfony\Component\Form\FormBuilderInterface $builder */
    $builder = $event->getBuilder();

    $builder->get('additional')
            ->add('neighboursName', \Symfony\Component\Form\Extension\Core\Type\TextType::class, [
                'constraints' => [
                    new \Symfony\Component\Validator\Constraints\NotBlank()
                ]
            ]);
}
```

#### Example #2: Adding multiple fields at once

The example will add multiple fields with different validation options. If you don't provide a list of constraints to a field, it will be optional.

##### Add to install() method in Bootstrap.php
```php
$this->subscribeEvent('Shopware_Form_Builder', 'onFormBuild');
```

##### Create method onFormBuild() in Bootstrap.php
```php
public function onFormBuild(\Enlight_Event_EventArgs $event) {
    if ($event->getReference() !== \Shopware\Bundle\AccountBundle\Form\Account\AddressFormType::class) {
        return;
    }

    /** @var \Symfony\Component\Form\FormBuilderInterface $builder */
    $builder = $event->getBuilder();

    $builder->get('additional')
            ->add('neighboursName', \Symfony\Component\Form\Extension\Core\Type\TextType::class, [
                'constraints' => [
                    new \Symfony\Component\Validator\Constraints\NotBlank()
                ]
            ])
            ->add('neighboursEmail', \Symfony\Component\Form\Extension\Core\Type\EmailType::class, [
                'constraints' => [
                    new \Symfony\Component\Validator\Constraints\NotBlank(),
                    new \Symfony\Component\Validator\Constraints\Email()
                ]
            ])
            ->add('neighboursPhone', \Symfony\Component\Form\Extension\Core\Type\IntegerType::class);
});
```

#### Example #3: Handle the additional data in the AddressService

This example will decorate the address service in order to handle additional data. First, we need a new class, which implements the `AddressServiceInterface`.

##### MyAddressService.php

```php
<?php

use Shopware\Bundle\AccountBundle\Service\AddressServiceInterface;
use Shopware\Models\Customer\Address;
use Shopware\Models\Customer\Customer;

class MyAddressService implements AddressServiceInterface
{
    /**
     * @var AddressServiceInterface
     */
    private $coreAddressService;

    public function __construct(AddressServiceInterface $coreAddressService)
    {
        $this->coreAddressService = $coreAddressService;
    }

    /**
     * Handles additional data
     * @param Address $address
     */
    private function handleAdditionalData($address)
    {
        // handle additional data
        $additional = $address->getAdditional();

        if (!empty($additional['neighboursEmail'])) {
            mail($additional['neighboursEmail'], 'Address changed', 'Nice mail text');
        }
    }

    public function create(Address $address, Customer $customer)
    {
        $this->coreAddressService->create($address, $customer);
        $this->handleAdditionalData($address);

    }

    public function update(Address $address)
    {
        $this->coreAddressService->update($address);
        $this->handleAdditionalData($address);
    }

    public function delete(Address $address)
    {
        $this->coreAddressService->delete($address);
    }

    public function isValid(Address $address)
    {
        $this->coreAddressService->isValid($address);
    }

    public function setDefaultBillingAddress(Address $address)
    {
        $this->coreAddressService->setDefaultBillingAddress($address);
    }

    public function setDefaultShippingAddress(Address $address)
    {
        $this->coreAddressService->setDefaultShippingAddress($address);
    }
}
```

Now that we have created the class, we need to decorate the existing service with our new service. For this, subscribe to a new event in your `Bootstrap.php` file.

##### Add to install() method in Bootstrap.php
```php
$this->subscribeEvent('Enlight_Bootstrap_AfterInitResource_shopware_account.address_service', 'decorateService');
```

##### Create method decorateService() in Bootstrap.php
```php
public function decorateService()
{
    $coreService = $this->get('shopware_account.address_service');
    $newService = new MyAddressService($coreService);
    Shopware()->Container()->set('shopware_account.address_service', $newService);
}
```

You are now set and your service takes over the work.
