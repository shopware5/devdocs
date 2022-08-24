<?php declare(strict_types=1);

namespace B2bContingentsBudgets\Budgets;

use Shopware\B2B\Budget\Framework\BudgetCartListingAccessStrategy;
use Shopware\B2B\Budget\Framework\BudgetService;
use Shopware\B2B\Cart\Framework\CartAccessFactoryInterface;
use Shopware\B2B\Cart\Framework\CartAccessStrategyAlwaysAllowed;
use Shopware\B2B\Cart\Framework\CartAccessStrategyInterface;
use Shopware\B2B\Cart\Framework\CartService;
use Shopware\B2B\Currency\Framework\CurrencyService;
use Shopware\B2B\StoreFrontAuthentication\Framework\Identity;

class BudgetCartAccessFactory implements CartAccessFactoryInterface
{
    /**
     * @var BudgetService
     */
    private $budgetService;

    /**
     * @var CurrencyService
     */
    private $currencyService;

    public function __construct(BudgetService $budgetService, CurrencyService $currencyService)
    {
        $this->budgetService = $budgetService;
        $this->currencyService = $currencyService;
    }

    public function createCartAccessForIdentity(Identity $identity, string $environmentName): CartAccessStrategyInterface
    {
        switch ($environmentName) {
            case CartService::ENVIRONMENT_NAME_ORDER:
                return new BudgetCartOrderAccessStrategy($identity, $this->budgetService, $this->currencyService->createCurrencyContext());
            case CartService::ENVIRONMENT_NAME_LISTING:
                return new BudgetCartListingAccessStrategy($this->budgetService, $identity->getOwnershipContext(), $this->currencyService->createCurrencyContext());
            case CartService::ENVIRONMENT_NAME_MODIFY:
            default:
                return new CartAccessStrategyAlwaysAllowed();
        }
    }
}
