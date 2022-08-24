<?php declare(strict_types = 1);

namespace B2bContingentRuleItem\RuleItem;

use Shopware\B2B\Common\Validator\ValidationBuilder;
use Shopware\B2B\ContingentRule\Framework\ContingentRuleTypeValidationExtender;

class WeekdayRuleValidationExtender implements ContingentRuleTypeValidationExtender
{
    /**
     * @var WeekdayRuleEntity
     */
    private $weekdayEntity;

    /**
     * @param WeekdayRuleEntity $weekdayEntity
     */
    public function __construct(WeekdayRuleEntity $weekdayEntity)
    {
        $this->weekdayEntity = $weekdayEntity;
    }

    /**
     * @param ValidationBuilder $validationBuilder
     * @return ValidationBuilder
     */
    public function extendValidator(ValidationBuilder $validationBuilder): ValidationBuilder
    {
        return $validationBuilder
            ->validateThat('weekdayId', $this->weekdayEntity->weekdayId)
            ->isNotBlank();
    }
}
