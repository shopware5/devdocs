<?php declare(strict_types = 1);

namespace B2bContingentRuleItem\RuleItem;

use Doctrine\DBAL\Connection;
use Shopware\B2B\Cart\Framework\CartAccessStrategyInterface;
use Shopware\B2B\ContingentRule\Framework\ContingentRuleEntity;
use Shopware\B2B\ContingentRule\Framework\ContingentRuleTypeInterface;
use Shopware\B2B\ContingentRule\Framework\ContingentRuleTypeRepositoryInterface;
use Shopware\B2B\ContingentRule\Framework\ContingentRuleTypeValidationExtender;
use Shopware\B2B\ContingentRule\Framework\UnsupportedContingentRuleEntityTypeException;
use Shopware\B2B\StoreFrontAuthentication\Framework\OwnershipContext;

class WeekdayRuleType implements ContingentRuleTypeInterface
{
    const NAME = 'Weekday';

    /**
     * {@inheritdoc}
     */
    public function getTypeName(): string
    {
        return self::NAME;
    }

    /**
     * {@inheritdoc}
     */
    public function createEntity(): ContingentRuleEntity
    {
        return new WeekdayRuleEntity($this->getTypeName());
    }

    /**
     * {@inheritdoc}
     */
    public function createValidationExtender(ContingentRuleEntity $entity): ContingentRuleTypeValidationExtender
    {
        return new WeekdayRuleValidationExtender($entity);
    }

    /**
     * {@inheritdoc}
     * @throws \Shopware\B2B\ContingentRule\Framework\UnsupportedContingentRuleEntityTypeException
     */
    public function createCartAccessStrategy(
        OwnershipContext $ownershipContext,
        ContingentRuleEntity $entity
    ): CartAccessStrategyInterface {
        if (!$entity instanceof WeekdayRuleEntity) {
            throw new UnsupportedContingentRuleEntityTypeException($entity);
        }

        return new WeekdayRuleAccessStrategy($entity->weekdayId, $entity);
    }

    /**
     * {@inheritdoc}
     */
    public function getRepository(Connection $connection): ContingentRuleTypeRepositoryInterface
    {
        return new WeekdayRuleRepository($connection);
    }

    /**
     * @return string[]
     */
    public function getRequestKeys(): array
    {
        return [
            'weekdayId',
        ];
    }
}
