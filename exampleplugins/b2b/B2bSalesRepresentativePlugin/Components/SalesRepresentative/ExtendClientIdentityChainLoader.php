<?php declare(strict_types=1);

namespace B2bSalesRepresentativePlugin\Components\SalesRepresentative;

use Shopware\B2B\SalesRepresentative\Framework\ClientIdentityChainLoader;

class ExtendClientIdentityChainLoader extends ClientIdentityChainLoader
{
    private $fieldNames = ['id', 'firstname', 'lastname', 'salutation', 'phone', 'email', 'active', 'company', 'city', 'zipcode', 'customernumber'];

    protected function getAdditionalSelect(array $authJoins): array
    {
        $additionalSelect = [];
        $countIdentities = 0;
        foreach ($authJoins as $joinTable) {
            foreach ($this->fieldNames as $fieldName) {
                if (!array_key_exists($fieldName, $additionalSelect)) {
                    $additionalSelect[$fieldName] = '';
                }
                $additionalSelect[$fieldName] .= 'IFNULL(' . $joinTable['joinAlias'] . '.' . $fieldName . ',';
            }
            $countIdentities += 1;
        }

        return $this->formatAdditionalSelect($additionalSelect, $countIdentities);
    }
}