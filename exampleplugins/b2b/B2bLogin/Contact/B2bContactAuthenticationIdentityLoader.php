<?php declare(strict_types=1);

namespace B2bLogin\Contact;

use Shopware\B2B\Common\Repository\NotFoundException;
use Shopware\B2B\Contact\Framework\ContactAuthenticationIdentityLoader;
use Shopware\B2B\Contact\Framework\ContactIdentity;
use Shopware\B2B\Contact\Framework\ContactRepository;
use Shopware\B2B\Debtor\Framework\DebtorIdentity;
use Shopware\B2B\Debtor\Framework\DebtorRepository;
use Shopware\B2B\StoreFrontAuthentication\Framework\CredentialsEntity;
use Shopware\B2B\StoreFrontAuthentication\Framework\Identity;
use Shopware\B2B\StoreFrontAuthentication\Framework\LoginContextService;

class B2bContactAuthenticationIdentityLoader extends ContactAuthenticationIdentityLoader
{
    /**
     * @var B2bContactRepository
     */
    private $contactRepository;

    /**
     * @var DebtorRepository
     */
    private $debtorRepository;

    /**
     * @param B2bContactRepository $contactRepository
     * @param DebtorRepository $debtorRepository
     */
    public function __construct(B2bContactRepository $contactRepository, DebtorRepository $debtorRepository)
    {
        $this->contactRepository = $contactRepository;
        $this->debtorRepository = $debtorRepository;
        parent::__construct($contactRepository, $debtorRepository);
    }

    public function fetchIdentityByStaffId(string $staffId, LoginContextService $contextService, bool $isApi = false): Identity
    {
        $entity = $this->contactRepository->fetchOneByStaffId($staffId);

        /** @var DebtorIdentity $debtorIdentity */
        $debtorIdentity = $this
            ->debtorRepository
            ->fetchIdentityById($entity->debtor->id, $contextService);

        $authId = $contextService->getAuthId(ContactRepository::class, $entity->email, $debtorIdentity->getAuthId());

        $this->contactRepository->setAuthId($entity->id, $authId);

        return new ContactIdentity($authId, (int) $entity->id, ContactRepository::TABLE_NAME, $entity, $debtorIdentity);
    }

    /**
     * @param CredentialsEntity $credentialsEntity
     * @param LoginContextService $contextService
     * @param bool $isApi
     * @return Identity
     */
    public function fetchIdentityByCredentials(CredentialsEntity $credentialsEntity, LoginContextService $contextService, bool $isApi = false): Identity
    {
        if (!$credentialsEntity->staffId) {
            throw new NotFoundException('Unable to handle context');
        }

        return $this->fetchIdentityByStaffId($credentialsEntity->staffId, $contextService);
    }
}
