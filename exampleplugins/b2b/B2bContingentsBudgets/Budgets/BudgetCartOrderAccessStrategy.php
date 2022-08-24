<?php declare(strict_types=1);

namespace B2bContingentsBudgets\Budgets;

use Shopware\B2B\Budget\Framework\BudgetService;
use Shopware\B2B\Cart\Framework\CartAccessContext;
use Shopware\B2B\Cart\Framework\CartAccessResult;
use Shopware\B2B\Cart\Framework\CartAccessStrategyInterface;
use Shopware\B2B\Currency\Framework\CurrencyContext;
use Shopware\B2B\StoreFrontAuthentication\Framework\Identity;

class BudgetCartOrderAccessStrategy implements CartAccessStrategyInterface
{
    /**
     * @var Identity
     */
    private $identity;

    /**
     * @var BudgetService
     */
    private $budgetService;

    /**
     * @var CurrencyContext
     */
    private $currencyContext;

    public function __construct(Identity $identity, BudgetService $budgetService, CurrencyContext $currencyContext)
    {
        $this->identity = $identity;
        $this->budgetService = $budgetService;
        $this->currencyContext = $currencyContext;
    }

    public function checkAccess(CartAccessContext $context, CartAccessResult $cartAccessResult)
    {
        $amountNet = 0;

        if ($context->orderClearanceEntity->list->amountNet) {
            $amountNet = $context->orderClearanceEntity->list->amountNet;
        }

        $budgets = $this->budgetService->getUserSelectableBudgetsWithStatus(
            $this->identity->getOwnershipContext(),
            $amountNet,
            $this->currencyContext
        );

        if (!count($budgets)) {
            return;
        }

        $canSpend = false;
        array_walk($budgets, function ($budget) use (&$canSpend) {
            if ($budget->currentStatus->isSufficient) {
                $canSpend = true;
            }
        });

        if ($canSpend) {
            return;
        }

        $cartAccessResult->addError(
            __CLASS__,
            'BudgetSpendError'
        );
    }

    public function addInformation(CartAccessResult $cartAccessResult)
    {
        //nth
    }
}
