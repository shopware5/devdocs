<?php declare(strict_types = 1);

namespace B2bAcl\Offer;

use Shopware\B2B\Acl\Framework\AclContextResolverMain;
use Shopware\B2B\Acl\Framework\AclUnsupportedContextException;
use Shopware\B2B\Contact\Framework\ContactEntity;
use Shopware\B2B\Contact\Framework\ContactIdentity;
use Shopware\B2B\StoreFrontAuthentication\Framework\OwnershipContext;

class AclTableContactContextResolver extends AclContextResolverMain
{
    /**
     * @param $context
     * @throws AclUnsupportedContextException
     * @return int
     */
    public function extractId($context): int
    {
        if ($context instanceof OwnershipContext && is_a($context->identityClassName, ContactIdentity::class, true)) {
            return $context->identityId;
        }

        if ($context instanceof ContactEntity && $context->id) {
            return $context->id;
        }

        throw new AclUnsupportedContextException();
    }
}
