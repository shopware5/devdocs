<?php declare(strict_types=1);

namespace B2bLogin\Debtor;

use Shopware\B2B\Common\Repository\NotFoundException;
use Shopware\B2B\Debtor\Framework\DebtorAuthenticationIdentityLoader;
use Shopware\B2B\Debtor\Framework\DebtorIdentity;
use Shopware\B2B\Debtor\Framework\DebtorRepository;
use Shopware\B2B\StoreFrontAuthentication\Framework\CredentialsEntity;
use Shopware\B2B\StoreFrontAuthentication\Framework\Identity;
use Shopware\B2B\StoreFrontAuthentication\Framework\LoginContextService;

class B2bDebtorAuthenticationIdentityLoader extends DebtorAuthenticationIdentityLoader
{
    /**
     * @var B2bDebtorRepository
     */
    private $debtorRepository;

    /**
     * @param B2bDebtorRepository $debtorRepository
     */
    public function __construct(B2bDebtorRepository $debtorRepository)
    {
        $this->debtorRepository = $debtorRepository;
        parent::__construct($debtorRepository);
    }

    /**
     * @param string $email
     * @param LoginContextService $contextService
     * @param bool $isApi
     * @param string $staffId
     * @return Identity
     */
    public function fetchIdentityByStaffId(string $staffId, LoginContextService $contextService, bool $isApi = false): Identity
    {
        $entity = $this->debtorRepository->fetchOneByStaffId($staffId);

        $authId = $contextService->getAuthId(DebtorRepository::class, $entity->email);

        return new DebtorIdentity($authId, (int) $entity->id, DebtorRepository::TABLE_NAME, $entity, $isApi);
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
