<?php declare(strict_types=1);

namespace B2bSalesRepresentativePlugin\Components\Debtor;

use Doctrine\DBAL\Query\QueryBuilder;
use Shopware\B2B\StoreFrontAuthentication\Framework\StoreFrontAuthenticationRepository;
use Shopware\B2B\Debtor\Framework\DebtorAuthenticationIdentityLoader;
use Shopware\B2B\Debtor\Framework\DebtorRepository;

class ExtendDebtorAuthenticationIdentityLoader extends DebtorAuthenticationIdentityLoader
{
    /**
     * {@inheritdoc}
     */
    public function addSubSelect(QueryBuilder $query)
    {

        $query->leftJoin(
            StoreFrontAuthenticationRepository::TABLE_ALIAS,
            '(SELECT 
                        s_user.id,
                        s_user.firstname,
                        s_user.lastname,
                        s_user.salutation,
                        s_user.email,
                        s_user.active,
                        s_user.customernumber,
                        address.`phone`,
                        address.company,
                        address.zipcode,
                        address.city
                        FROM s_user 
                        INNER JOIN s_user_addresses address 
                        ON s_user.default_billing_address_id = address.id)',
            'debtor',
            StoreFrontAuthenticationRepository::TABLE_ALIAS . '.provider_context = debtor.id AND '
            . StoreFrontAuthenticationRepository::TABLE_ALIAS . '.provider_key = :debtorProviderKey'
        )
            ->setParameter('debtorProviderKey', DebtorRepository::class);
    }
}
