<?php declare(strict_types=1);

namespace B2bLogin\Login;

use Enlight_Event_EventArgs;
use Shopware\B2B\StoreFrontAuthentication\Framework\CredentialsBuilderInterface;
use Shopware\B2B\StoreFrontAuthentication\Framework\CredentialsEntity;

class B2BCredentialsBuilder implements credentialsBuilderInterface
{
    /**
     * @param Enlight_Event_EventArgs $args
     * @return CredentialsEntity
     */
    public function createCredentials(Enlight_Event_EventArgs $args): CredentialsEntity
    {
        $entity = new CredentialsEntity();
        $entity->staffId = $args->get('post')['staffId'];

        return $entity;
    }
}
