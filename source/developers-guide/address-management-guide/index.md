---
layout: default
title: Address Management Guide
github_link: developers-guide/address-management-guide/index.md
shopware_version: 5.2.0
indexed: true
tags:
  - address
  - management
  - services
---

The address management allows a customer to manage more than only one address which gets changed with every order. The customer is now able to create more address, e.g. for home and work, and use them later on in an order without loosing all existing address data. He can just change the reference to the default billing address, instead of changing it entirely.

# The address service

The address service is used to manage all address entities in Shopware. It only works with models which makes it easy to comprehend, which properties are available and can be used. To know which properties are available, please refer to the model `\Shopware\Models\Customer\Address` in our source code.

## Creation

Like said above, you have to pass an already complete address model and an existing customer to the address service. In case of a validation error or other errors, you'll get an exception.

**Example**

```php
<?php
$customer = $this->get('models')->find(Customer::class, 1);

$address = new Address();
$address->fromArray($addressData);

$this->get('shopware_account.address_service')->create($address, $customer);
```



# Changes



**Example: Extending the address form**

```php
<?php
$this->subscribeEvent('Shopware_Form_Builder', function(\Enlight_Event_EventArgs $event) {
    if ($event->getReference() !== AddressFormType::class) {
        return;
    }

    /** @var \Symfony\Component\Form\FormBuilderInterface $builder */
    $builder = $event->getBuilder();

    $builder->add('addressLine3', TextType::class, [
        'constraints' => [new NotBlank()]
    ]);
});
```