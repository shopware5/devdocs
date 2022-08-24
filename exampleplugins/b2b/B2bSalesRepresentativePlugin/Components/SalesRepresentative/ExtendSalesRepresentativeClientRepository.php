<?php declare(strict_types=1);

namespace B2bSalesRepresentativePlugin\Components\SalesRepresentative;

use Shopware\B2B\Address\Framework\AddressRepositoryInterface;
use Shopware\B2B\SalesRepresentative\Framework\SalesRepresentativeClientEntity;
use Shopware\B2B\SalesRepresentative\Framework\SalesRepresentativeClientRepository;
use Doctrine\DBAL\Connection;
use Shopware\B2B\Common\Repository\DbalHelper;
use Shopware\B2B\Common\Repository\NotFoundException;
use Shopware\B2B\SalesRepresentative\Framework\SalesRepresentativeEntity;
use Shopware\B2B\SalesRepresentative\Framework\SalesRepresentativeSearchStruct;
use Shopware\B2B\StoreFrontAuthentication\Framework\AuthenticationIdentityLoaderInterface;
use Shopware\B2B\StoreFrontAuthentication\Framework\LoginContextService;
use Shopware\B2B\StoreFrontAuthentication\Framework\StoreFrontAuthenticationRepository;

class ExtendSalesRepresentativeClientRepository extends SalesRepresentativeClientRepository
{

    const TABLE_NAME = 'b2b_sales_representative_clients';

    const TABLE_ALIAS = 'clients';

    /**
     * @var Connection
     */
    private $connection;

    /**
     * @var DbalHelper
     */
    private $dbalHelper;

    /**
     * @var AuthenticationIdentityLoaderInterface
     */
    private $authenticationIdentityLoader;

    /**
     * @var LoginContextService
     */
    private $loginContextService;

    /**
     * @var StoreFrontAuthenticationRepository
     */
    private $authRepository;

    /**
     * @var AddressRepositoryInterface
     */
    private $addressRepository;

    public function __construct(
        Connection $connection,
        AuthenticationIdentityLoaderInterface $authenticationIdentityLoader,
        DbalHelper $dbalHelper,
        LoginContextService $loginContextService,
        StoreFrontAuthenticationRepository $authRepository,
        AddressRepositoryInterface $addressRepository
    )
    {
        $this->connection = $connection;
        $this->dbalHelper = $dbalHelper;
        $this->authenticationIdentityLoader = $authenticationIdentityLoader;
        $this->loginContextService = $loginContextService;
        $this->authRepository = $authRepository;
        $this->addressRepository = $addressRepository;

        parent::__construct($connection, $authenticationIdentityLoader, $dbalHelper, $loginContextService, $authRepository, $addressRepository);
    }

    /**
     * @return SalesRepresentativeClientEntity[]
     */
    public function fetchClientsList(SalesRepresentativeSearchStruct $searchStruct, SalesRepresentativeEntity $salesRepresentativeEntity): array
    {
        $query = $this->connection->createQueryBuilder()
            ->select('*')
            ->from(self::TABLE_NAME, self::TABLE_ALIAS)
            ->innerJoin(
                self::TABLE_ALIAS,
                StoreFrontAuthenticationRepository::TABLE_NAME,
                StoreFrontAuthenticationRepository::TABLE_ALIAS,
                StoreFrontAuthenticationRepository::TABLE_ALIAS . '.id = ' . self::TABLE_ALIAS . '.client_id'
            )
            ->where(self::TABLE_ALIAS . '.sales_representative_id = :id')
            ->setParameter('id', $salesRepresentativeEntity->id);

        if (!$searchStruct->orderBy) {
            $searchStruct->orderBy = self::TABLE_ALIAS . '.client_id';
            $searchStruct->orderDirection = 'DESC';
        }

        $this->authenticationIdentityLoader->addSubSelect($query);

        $this->dbalHelper->applySearchStruct($searchStruct, $query);

        $clientIds = $query->execute()->fetchAll(\PDO::FETCH_COLUMN, 2);

        return $this->fetchClientsByAuthIds($clientIds);
    }

    /**
     * @internal
     * @param int[] $clientIds
     * @return SalesRepresentativeClientEntity[]
     */
    protected function fetchClientsByAuthIds(array $clientIds): array
    {
        $clients = [];
        foreach ($clientIds as $authId) {
            try {
                $auth = $this->authRepository->fetchAuthenticationById((int) $authId);

                $identity = $this->authenticationIdentityLoader
                    ->fetchIdentityByAuthentication($auth, $this->loginContextService);

                $client = new ExtendSalesRepresentativeClientEntity(
                    $identity,
                    $this->addressRepository->fetchOneById($identity->getMainShippingAddress()->id, $identity)
                );
                $clients[] = $client;
            } catch (NotFoundException $e) {
                continue;
            }
        }

        return $clients;
    }
}