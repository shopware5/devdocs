<?php declare(strict_types = 1);

namespace B2bContingentRuleItem\RuleItem;

use Shopware\B2B\Common\CrudEntity;
use Shopware\B2B\ContingentRule\Framework\ContingentRuleEntity;

class WeekdayRuleEntity extends ContingentRuleEntity
{
    /**
     * @var int
     */
    public $weekdayId;

    /**
     * {@inheritdoc}
     */
    public function toDatabaseArray(): array
    {
        return array_merge(
            parent::toDatabaseArray(),
            ['weekday_id' => $this->weekdayId]
        );
    }

    /**
     * {@inheritdoc}
     */
    public function fromDatabaseArray(array $data): CrudEntity
    {
        $this->weekdayId = (int) $data['weekday_id'];

        return parent::fromDatabaseArray($data);
    }
}
