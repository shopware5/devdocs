<?php declare(strict_types = 1);

namespace B2bAcl\Offer;

use Shopware\B2B\Acl\Framework\AclTable;

class OfferAclTable extends AclTable
{
    /**
     * {@inheritdoc}
     */
    public function __construct()
    {
        parent::__construct(
            'contact_offer',
            'b2b_debtor_contact',
            'id',
            'b2b_offer',
            'id'
        );
    }

    /**
     * {@inheritdoc}
     */
    protected function getContextResolvers(): array
    {
        return [
            new AclTableContactContextResolver(),
        ];
    }
}
