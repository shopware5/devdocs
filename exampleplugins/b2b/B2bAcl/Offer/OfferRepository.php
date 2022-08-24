<?php declare(strict_types = 1);

namespace B2bAcl\Offer;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use Shopware\B2B\Acl\Framework\AclRepository;
use Shopware\B2B\Acl\Framework\AclUnsupportedContextException;
use Shopware\B2B\Common\Controller\GridRepository;
use Shopware\B2B\Common\Repository\CanNotInsertExistingRecordException;
use Shopware\B2B\Common\Repository\CanNotRemoveExistingRecordException;
use Shopware\B2B\Common\Repository\CanNotUpdateExistingRecordException;
use Shopware\B2B\Common\Repository\DbalHelper;
use Shopware\B2B\StoreFrontAuthentication\Framework\OwnershipContext;

class OfferRepository implements GridRepository
{
    const TABLE_NAME = 'b2b_offer';

    const TABLE_ALIAS = 'offer';

    /**
     * @var Connection
     */
    private $connection;

    /**
     * @var DbalHelper
     */
    private $dbalHelper;

    /**
     * @var AclRepository
     */
    private $aclRepository;

    /**
     * @param Connection $connection
     * @param DbalHelper $dbalHelper
     * @param AclRepository $aclRepository
     */
    public function __construct(
        Connection $connection,
        DbalHelper $dbalHelper,
        AclRepository $aclRepository = null
    ) {
        $this->connection = $connection;
        $this->dbalHelper = $dbalHelper;
        $this->aclRepository = $aclRepository;
    }

    /**
     * @param OwnershipContext $context
     * @param OfferSearchStruct $searchStruct
     * @throws \InvalidArgumentException
     * @return OfferEntity[]
     */
    public function fetchList(OwnershipContext $context, OfferSearchStruct $searchStruct): array
    {
        $query = $this->connection->createQueryBuilder()
            ->select(self::TABLE_ALIAS . '.*')
            ->from(self::TABLE_NAME, self::TABLE_ALIAS)
            ->where(self::TABLE_ALIAS . '.s_user_id = :owner')
            ->setParameter('owner', $context->shopOwnerUserId);

        $this->applyAcl($context, $query);

        if (!$searchStruct->orderBy) {
            $searchStruct->orderBy = self::TABLE_ALIAS . '.id';
            $searchStruct->orderDirection = 'DESC';
        }

        $this->dbalHelper->applySearchStruct($searchStruct, $query);

        $statement = $query->execute();

        $offersData = $statement
            ->fetchAll(\PDO::FETCH_ASSOC);

        $offers = [];
        foreach ($offersData as $offerData) {
            $offers[] = (new OfferEntity())->fromDatabaseArray($offerData);
        }

        return $offers;
    }

    /**
     * @param OwnershipContext $context
     * @param OfferSearchStruct $searchStruct
     * @return int
     */
    public function fetchTotalCount(OwnershipContext $context, OfferSearchStruct $searchStruct): int
    {
        $query = $this->connection->createQueryBuilder()
            ->select('COUNT(*)')
            ->from(self::TABLE_NAME, self::TABLE_ALIAS)
            ->where(self::TABLE_ALIAS . '.s_user_id = :owner')
            ->setParameter('owner', $context->shopOwnerUserId);

        $this->applyAcl($context, $query);
        $this->dbalHelper->applyFilters($searchStruct, $query);

        $statement = $query->execute();

        return (int) $statement->fetchColumn(0);
    }

    /**
     * @param int $id
     * @return OfferEntity
     */
    public function fetchOneById(int $id): OfferEntity
    {
        $statement = $this->connection->createQueryBuilder()
            ->select('*')
            ->from(self::TABLE_NAME, self::TABLE_ALIAS)
            ->where(self::TABLE_ALIAS . '.id = :id')
            ->setParameter('id', $id)
            ->execute();

        $offerData = $statement->fetch(\PDO::FETCH_ASSOC);

        $offer = new OfferEntity();

        return $offer->fromDatabaseArray($offerData);
    }

    /**
     * @param OfferEntity $offerEntity
     * @throws \Shopware\B2B\Common\Repository\CanNotInsertExistingRecordException
     * @return OfferEntity
     */
    public function addOffer(OfferEntity $offerEntity): OfferEntity
    {
        if (!$offerEntity->isNew()) {
            throw new CanNotInsertExistingRecordException('The Offer provided already exists');
        }

        $this->connection->insert(
            self::TABLE_NAME,
            $offerEntity->toDatabaseArray()
        );

        $offerEntity->id = (int) $this->connection->lastInsertId();

        return $offerEntity;
    }

    /**
     * @param OfferEntity $offerEntity
     * @throws \Shopware\B2B\Common\Repository\CanNotUpdateExistingRecordException
     * @return OfferEntity
     */
    public function updateOffer(OfferEntity $offerEntity): OfferEntity
    {
        if ($offerEntity->isNew()) {
            throw new CanNotUpdateExistingRecordException('The Offer provided does not exist');
        }

        $this->connection->update(
            self::TABLE_NAME,
            $offerEntity->toDatabaseArray(),
            ['id' => $offerEntity->id]
        );

        return $offerEntity;
    }

    /**
     * @param OfferEntity $offerEntity
     * @throws \Shopware\B2B\Common\Repository\CanNotRemoveUsedRecordException
     * @throws \Shopware\B2B\Common\Repository\CanNotRemoveExistingRecordException
     * @return OfferEntity
     */
    public function removeOffer(OfferEntity $offerEntity): OfferEntity
    {
        if ($offerEntity->isNew()) {
            throw new CanNotRemoveExistingRecordException('The Offer provided does not exist');
        }

        $this->connection->delete(
            self::TABLE_NAME,
            ['id' => $offerEntity->id]
        );

        $offerEntity->id = null;

        return $offerEntity;
    }

    /**
     * @return string query alias for filter construction
     */
    public function getMainTableAlias(): string
    {
        return self::TABLE_ALIAS;
    }

    /**
     * @return string[]
     */
    public function getFullTextSearchFields(): array
    {
        return [
            'name',
            'description',
        ];
    }

    /**
     * @param OwnershipContext $context
     * @param QueryBuilder $query
     */
    private function applyAcl(OwnershipContext $context, QueryBuilder $query)
    {
        try {
            $aclQuery = $this->aclRepository->getUnionizedSqlQuery($context);

            $query->innerJoin(
                self::TABLE_ALIAS,
                '(' . $aclQuery->sql . ')',
                'acl_query',
                self::TABLE_ALIAS . '.id = acl_query.referenced_entity_id'
            );

            foreach ($aclQuery->params as $name => $value) {
                $query->setParameter($name, $value);
            }
        } catch (AclUnsupportedContextException $e) {
            // nth
        }
    }

    /**
     * @return array
     */
    public function getAdditionalSearchResourceAndFields(): array
    {
        return [];
    }
}
