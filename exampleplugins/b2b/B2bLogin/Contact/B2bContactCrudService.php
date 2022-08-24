<?php declare(strict_types=1);

namespace B2bLogin\Contact;

use Shopware\B2B\Acl\Framework\AclRepository;
use Shopware\B2B\Common\Service\CrudServiceRequest;
use Shopware\B2B\Contact\Framework\ContactCrudService;
use Shopware\B2B\Contact\Framework\ContactEntity;
use Shopware\B2B\Contact\Framework\ContactPasswordProviderInterface;
use Shopware\B2B\Contact\Framework\ContactRepository;
use Shopware\B2B\Contact\Framework\ContactValidationService;
use Shopware\B2B\StoreFrontAuthentication\Framework\Identity;

class B2bContactCrudService extends ContactCrudService
{
    /**
     * @var ContactRepository
     */
    private $contactRepository;

    /**
     * @var ContactValidationService
     */
    private $validationService;

    /**
     * @var AclRepository
     */
    private $aclRepository;

    /**
     * @var ContactPasswordProviderInterface
     */
    private $passwordProvider;

    /**
     * @param ContactRepository $contactRepository
     * @param ContactValidationService $validationService
     * @param AclRepository $aclRepository
     * @param ContactPasswordProviderInterface $passwordProvider
     */
    public function __construct(
        ContactRepository $contactRepository,
        ContactValidationService $validationService,
        AclRepository $aclRepository,
        ContactPasswordProviderInterface $passwordProvider
    ) {
        parent::__construct($contactRepository, $validationService, $aclRepository, $passwordProvider);
        $this->contactRepository = $contactRepository;
        $this->validationService = $validationService;
        $this->aclRepository = $aclRepository;
        $this->passwordProvider = $passwordProvider;
    }

    /**
     * @param CrudServiceRequest $request
     * @param Identity $identity
     * @throws \Shopware\B2B\Common\Validator\ValidationException
     * @return ContactEntity
     */
    public function create(CrudServiceRequest $request, Identity $identity): ContactEntity
    {
        $data = $request->getFilteredData();

        $contact = new ContactEntity();

        $contact->setData($data);

        $this->checkPassword($contact, $request, true);
        $this->passwordProvider->setPassword($contact, $request->requireParam('passwordNew'));

        $validation = $this->validationService
            ->createInsertValidation($contact);

        $this->testValidation($contact, $validation);

        if (empty($contact->email)) {
            $contact->email = uniqid('', true);
        }

        $contact = $this->contactRepository
            ->addContact($contact);

        $this->aclRepository
            ->allowAll(
                $contact,
                [
                    $identity->getMainShippingAddress()->id,
                    $identity->getMainBillingAddress()->id,
                ]
            );

        return $contact;
    }

    /**
     * @param CrudServiceRequest $request
     * @throws \Shopware\B2B\Common\Validator\ValidationException
     * @return ContactEntity
     */
    public function update(CrudServiceRequest $request): ContactEntity
    {
        $data = $request->getFilteredData();
        $contact = new ContactEntity();
        $contact->setData($data);

        $this->checkPassword($contact, $request, false);

        if ($request->hasValueForParam('passwordNew')) {
            $this->passwordProvider->setPassword($contact, $request->requireParam('passwordNew'));
        }

        $validation = $this->validationService
            ->createUpdateValidation($contact);

        $this->testValidation($contact, $validation);

        if (empty($contact->email)) {
            $contact->email = uniqid('', true);
        }

        $this->contactRepository
            ->updateContact($contact);

        return $contact;
    }
}
