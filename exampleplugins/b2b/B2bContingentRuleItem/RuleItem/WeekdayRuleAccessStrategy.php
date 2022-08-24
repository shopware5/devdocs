<?php declare(strict_types = 1);

namespace B2bContingentRuleItem\RuleItem;

use Shopware\B2B\Cart\Framework\CartAccessContext;
use Shopware\B2B\Cart\Framework\CartAccessStrategyInterface;
use Shopware\B2B\Cart\Framework\MessageCollection;
use Shopware\B2B\ContingentRule\Framework\ContingentRuleEntity;

class WeekdayRuleAccessStrategy implements CartAccessStrategyInterface
{
    /**
     * @var int
     */
    private $weekdayId;

    /**
     * @var ContingentRuleEntity
     */
    private $weekdayEntity;

    /**
     * @param int $weekdayId
     * @param ContingentRuleEntity $weekdayEntity
     */
    public function __construct(int $weekdayId, ContingentRuleEntity $weekdayEntity)
    {
        $this->weekdayId = $weekdayId;
        $this->weekdayEntity = $weekdayEntity;
    }

    /**
     * {@inheritdoc}
     */
    public function isAllowed(CartAccessContext $context, MessageCollection $messageCollection): bool
    {
        $allowed = (int) date('N') !== $this->weekdayId;
        if ($allowed) {
            return true;
        }

        $messageCollection->addError(
            __CLASS__,
            'WeekdayError',
            [
                'allowedValue' => $this->weekdayEntity->toArray(),
            ]
        );

        return false;
    }
}
