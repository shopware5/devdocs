<?php

namespace B2bSalesRepresentativePlugin\Components\Contact;

use Doctrine\DBAL\Query\QueryBuilder;
use Shopware\B2B\Contact\Framework\ContactAuthenticationIdentityLoader;
use Shopware\B2B\Contact\Framework\ContactRepository;
use Shopware\B2B\StoreFrontAuthentication\Framework\StoreFrontAuthenticationRepository;

class ExtendContactAuthenticationIdentityLoader extends ContactAuthenticationIdentityLoader
{
    public function addSubSelect(QueryBuilder $query)
    {
        $query->leftJoin(
            StoreFrontAuthenticationRepository::TABLE_ALIAS,
            '(SELECT 
                        contact.id,
                        contact.firstname,
                        contact.lastname,
                        contact.salutation,
                        contact.email,
                        contact.active,
                        address.phone,
                        address.company,
                        address.city,
                        address.zipcode,
                        s_user.customernumber
                        FROM b2b_debtor_contact as contact 
                        INNER JOIN b2b_store_front_auth auth 
                        ON contact.context_owner_id = auth.id
                        INNER JOIN s_user
                        ON auth.provider_context = s_user.id
                        INNER JOIN s_user_addresses address
                        ON s_user.default_billing_address_id = address.id)',
            'contact',
            StoreFrontAuthenticationRepository::TABLE_ALIAS . '.provider_context = contact.id AND '
            . StoreFrontAuthenticationRepository::TABLE_ALIAS . '.provider_key = :contactProviderKey'
        )
            ->setParameter('contactProviderKey', ContactRepository::class);
    }
}