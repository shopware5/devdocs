<?php declare(strict_types = 1);

namespace B2bContingentRuleItem\RuleItem;

use Doctrine\DBAL\Connection;
use Shopware\B2B\ContingentRule\Framework\ContingentRuleTypeRepositoryInterface;

class WeekdayRuleRepository implements ContingentRuleTypeRepositoryInterface
{
    const TABLE_NAME = 'b2b_contingent_group_rule_weekday';

    const TABLE_ALIAS = 'weekdayType';

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
    }

    /**
     * @return string
     */
    public function createSubQuery(): string
    {
        return $this->connection->createQueryBuilder()
            ->select(self::TABLE_ALIAS . '.*')
            ->from(self::TABLE_NAME, self::TABLE_ALIAS)
            ->getSQL();
    }

    /**
     * @return string
     */
    public function getTableName(): string
    {
        return self::TABLE_NAME;
    }
}
