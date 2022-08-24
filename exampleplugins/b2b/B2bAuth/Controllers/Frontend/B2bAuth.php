<?php declare(strict_types = 1);

use Shopware\B2B\StoreFrontAuthentication\Framework\AuthenticationService;
use Shopware\B2B\StoreFrontAuthentication\Framework\LoginService;
use SwagB2bPlugin\Extension\LoginProtectedControllerProxy;

class Shopware_Controllers_Frontend_B2bAuth extends LoginProtectedControllerProxy
{
    /**
     * @return string
     */
    protected function getControllerDiKey(): string
    {
        throw new \BadMethodCallException('Should not get called');
    }

    protected function getController()
    {
        return new class($this->get('b2b_front_auth.authentication_service'), $this->get('b2b_front_auth.login_service')) {
            /**
             * @var AuthenticationService
             */
            private $authenticationService;

            /**
             * @var LoginService
             */
            private $loginService;

            /**
             * @param AuthenticationService $authenticationService
             * @param LoginService $loginService
             */
            public function __construct(
                AuthenticationService $authenticationService,
                LoginService $loginService
            ) {
                $this->authenticationService = $authenticationService;
                $this->loginService = $loginService;
            }

            public function indexAction()
            {
                //nth
            }

            /**
             * @return array
             */
            public function contactAction(): array
            {
                $this->loginService->setIdentityFor('contact1@example.com');

                $identity = $this->authenticationService->getIdentity();

                return [
                    'identity' => $identity,
                    'contextOwner' => $this->authenticationService->getIdentityByAuthId($identity->getOwnershipContext()->contextOwnerId),
                ];
            }

            /**
             * @return array
             */
            public function debtorAction(): array
            {
                $this->loginService->setIdentityFor('debtor@example.com');

                $identity = $this->authenticationService->getIdentity();

                return [
                    'identity' => $identity,
                    'contextOwner' => $this->authenticationService->getIdentityByAuthId($identity->getOwnershipContext()->contextOwnerId),
                ];
            }
        };
    }
}
