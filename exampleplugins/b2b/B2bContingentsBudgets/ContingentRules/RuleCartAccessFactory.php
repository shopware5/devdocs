<?php declare(strict_types=1);

namespace B2bContingentsBudgets\ContingentRules;

use Shopware\B2B\Cart\Framework\BlackListCartAccess;
use Shopware\B2B\Cart\Framework\CartAccessFactoryInterface;
use Shopware\B2B\Cart\Framework\CartAccessStrategyInterface;
use Shopware\B2B\Cart\Framework\WhiteListCartAccess;
use Shopware\B2B\ContingentGroup\Framework\ContingentGroupRepository;
use Shopware\B2B\ContingentRule\Framework\ContingentRuleRepository;
use Shopware\B2B\ContingentRule\Framework\ContingentRuleTypeFactory;
use Shopware\B2B\Currency\Framework\CurrencyService;
use Shopware\B2B\StoreFrontAuthentication\Framework\Identity;

class RuleCartAccessFactory implements CartAccessFactoryInterface
{
    /**
     * @var array
     */
    private $allowedTypes;

    /**
     * @var ContingentGroupRepository
     */
    private $contingentGroupRepository;

    /**
     * @var ContingentRuleRepository
     */
    private $contingentRuleRepository;

    /**
     * @var ContingentRuleTypeFactory
     */
    private $typeFactory;

    /**
     * @var CurrencyService
     */
    private $currencyService;

    public function __construct(
        ContingentGroupRepository $contingentGroupRepository,
        ContingentRuleRepository $contingentRuleRepository,
        ContingentRuleTypeFactory $typeFactory,
        CurrencyService $currencyService,
        array $allowedTypes
    ) {
        $this->contingentGroupRepository = $contingentGroupRepository;
        $this->contingentRuleRepository = $contingentRuleRepository;
        $this->typeFactory = $typeFactory;
        $this->allowedTypes = $allowedTypes;
        $this->currencyService = $currencyService;
    }

    public function createCartAccessForIdentity(Identity $identity, string $environmentName): CartAccessStrategyInterface
    {
        $context = $identity->getOwnershipContext();

        $contingentGroupIds = $this->contingentGroupRepository
            ->fetchContingentGroupIdsForContact($context->identityId);

        $groupStrategies = [];
        foreach ($contingentGroupIds as $contingentGroupId) {
            $types = $this->contingentGroupRepository
                ->fetchRuleTypesFromContingentGroup($contingentGroupId);

            $types = array_filter($types, function (string $typeName) {
                return in_array($typeName, $this->allowedTypes, true);
            });

            $typeStrategies = [];
            foreach ($types as $type) {
                $ruleData = $this->contingentRuleRepository
                    ->fetchActiveRuleItemsForRuleType($type, $contingentGroupId, $this->currencyService->createCurrencyContext());

                $ruleStrategies = [];
                foreach ($ruleData as $rule) {
                    $ruleStrategies[] = $this->typeFactory
                        ->createCartAccessStrategy($type, $context, $rule);
                }

                if (!$ruleStrategies) {
                    continue;
                }

                $typeStrategies[] = new BlackListCartAccess(... $ruleStrategies);
            }

            if (!$typeStrategies) {
                continue;
            }

            $groupStrategies[] = new BlackListCartAccess(... $typeStrategies);
        }

        return new WhiteListCartAccess(... $groupStrategies);
    }
}
