<?php

namespace B2bSalesRepresentativePlugin\Components\SalesRepresentative;

use Shopware\B2B\SalesRepresentative\Framework\SalesRepresentativeRepository;

class ExtendSalesRepresentativeRepository extends SalesRepresentativeRepository
{
    public function getAdditionalSearchResourceAndFields(): array
    {
        return [
            '%2$s' => [
                'contact' => [
                    'phone',
                    'email',
                    'firstname',
                    'lastname',
                    'customernumber',
                    'zipcode',
                    'city',
                    'company',
                ],
                'debtor' => [
                    'phone',
                    'email',
                    'firstname',
                    'lastname',
                    'customernumber',
                    'zipcode',
                    'city',
                    'company',
                ],
            ],
        ];
    }
}