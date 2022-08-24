<?php declare(strict_types=1);

namespace B2bLogin\Login;

use Enlight\Event\SubscriberInterface;
use Enlight_Event_EventArgs;
use Shopware\B2B\Common\Repository\NotFoundException;

class LoginSubscriber implements SubscriberInterface
{
    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            'Shopware_Modules_Admin_Login_Start' => array('updateEmail', 2),
            'Shopware_Modules_Admin_CheckUser_Failure' => array('resetEmail', 2),
            'Shopware_Modules_Admin_Login_Successful' => array('resetEmail', 2),
        ];
    }

    /**
     * @param Enlight_Event_EventArgs $args
     */
    public function updateEmail(Enlight_Event_EventArgs $args)
    {
        $credentialBuilder = Shopware()->Container()->get('b2b_front_auth.credentials_builder');
        $identityChain = Shopware()->Container()->get('b2b_front_auth.identity_chain_repository');
        $context = Shopware()->Container()->get('b2b_front_auth.login_context');
        $post = $args->get('post');
        $credential = $credentialBuilder->createCredentials($args);
        try {
            $entity = $identityChain->fetchIdentityByCredentials($credential, $context, false);
        } catch (NotFoundException $e) {
            return;
        }
        $post['email'] = $entity->getEntity()->email;
        Shopware()->Front()->Request()->setPost($post);
    }
}
