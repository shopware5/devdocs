<?php declare(strict_types=1);

use Shopware\B2B\Contact\Framework\ContactRepository;
use Shopware\B2B\Debtor\Framework\DebtorIdentity;
use Shopware\B2B\StoreFrontAuthentication\Framework\AuthenticationService;

class Shopware_Controllers_Frontend_B2bCustomerDirectApi extends \Enlight_Controller_Action
{
    public function preDispatch()
    {
        /** @var AuthenticationService $authenticationService */
        $authenticationService = $this->get('b2b_front_auth.authentication_service');
        if (!$authenticationService->isB2b()) {
            $this->forward('index', 'account');
        }
    }

    /**
     * @throws \InvalidArgumentException
     */
    public function indexAction()
    {
        $request = $this->Request();

        $identity = $this->get('b2b_front_auth.authentication_service')->getIdentity();

        if (!($identity instanceof DebtorIdentity)) {
            throw new \InvalidArgumentException('No debtor identity');
        }

        $contactId = (int) $request->getParam('contactId');

        if (!$contactId) {
            throw new \InvalidArgumentException('No contact id given');
        }

        /** @var ContactRepository $contactRepository */
        $contactRepository = $this->get('b2b_contact.repository');

        $contact = $contactRepository->fetchOneById($contactId);

        $this->View()->assign(
            'data',
            [
                'success' => true,
                'contact' => $contact,
            ]
        );
    }

    public function postDispatch()
    {
        parent::postDispatch();

        $this->Response()->setHeader('Content-type', 'application/json', true);
    }
}
