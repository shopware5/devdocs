<?php

namespace B2bSalesRepresentativePlugin\Components\SalesRepresentative;

use Shopware\B2B\SalesRepresentative\Framework\SalesRepresentativeClientEntity;
use Shopware\B2B\Address\Framework\AddressEntity;
use Shopware\B2B\StoreFrontAuthentication\Framework\Identity;

class ExtendSalesRepresentativeClientEntity extends SalesRepresentativeClientEntity
{
    /**
     * @var int
     */
    public $authId;

    /**
     * @var string
     */
    public $firstName;

    /**
     * @var string
     */
    public $lastName;

    /**
     * @var string
     */
    public $email;

    /**
     * @var string
     */
    public $phone;

    /**
     * @var bool
     */
    public $active;

    /**
     * @var array
     */
    public $billingAddress;

    /**
     * @var string
     */
    public $customernumber;

    /**
     * @var string
     */
    public $zipcode;

    /**
     * @var string
     */
    public $city;

    /**
     * @var string
     */
    public $company;

    public function __construct(Identity $identity, AddressEntity $address)
    {
        $postal = $identity->getPostalSettings();

        $this->authId = $identity->getAuthId();
        $this->firstName = $postal->firstName;
        $this->lastName = $postal->lastName;
        $this->email = $postal->email;
        $this->active = $identity->getLoginCredentials()->active;
        $this->phone = $address->phone;
        $this->billingAddress = $identity->getMainBillingAddress();
        $this->zipcode = $address->zipcode;
        $this->city = $address->city;

        if (empty($identity->getEntity()->customernumber)) {
            $customernumber = $identity->getEntity()->debtor->customernumber;
        } else {
            $customernumber = $identity->getEntity()->customernumber;
        }

        $this->customernumber = $customernumber;
        $this->company = $address->company;
    }
}