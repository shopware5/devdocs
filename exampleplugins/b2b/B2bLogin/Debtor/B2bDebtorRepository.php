<?php declare(strict_types=1);

namespace B2bLogin\Debtor;

use Doctrine\DBAL\Connection;
use Shopware\B2B\Common\Repository\NotFoundException;
use Shopware\B2B\Debtor\Framework\DebtorEntity;
use Shopware\B2B\Debtor\Framework\DebtorRepository;

class B2bDebtorRepository extends DebtorRepository
{
    const TABLE_NAME = 's_user';

    const TABLE_ALIAS = 'user';

    /**
     * @var Connection
     */
    private $connection;

    /**
     * @param Connection $connection
     */
    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
        parent::__construct($connection);
    }

    /**
     * @param string $staffId
     * @throws NotFoundException
     * @return DebtorEntity
     */
    public function fetchOneByStaffId(string $staffId): DebtorEntity
    {
        $statement = $this->connection->createQueryBuilder()
            ->select(self::TABLE_ALIAS . '.*')
            ->from(self::TABLE_NAME, 'user')
            ->innerJoin(self::TABLE_ALIAS, 's_user_attributes', 'attributes', 'attributes.userID = user.id')
            ->where('attributes.staff_id = :staffId')
            ->andWhere('attributes.b2b_is_debtor = 1')
            ->setParameter('staffId', $staffId)
            ->execute();

        $user = $statement->fetch(\PDO::FETCH_ASSOC);

        if (!$user) {
            throw new NotFoundException(sprintf('Debtor not found for %s', $staffId));
        }

        return (new DebtorEntity())->fromDatabaseArray($user);
    }
}
