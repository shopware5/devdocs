<?php declare(strict_types=1);

namespace B2bContingentsBudgets\Tests;

use B2bContingentsBudgets\Budgets\BudgetCartOrderAccessStrategy;
use PHPUnit\Framework\TestCase;
use Shopware\B2B\Cart\Framework\CartAccessContext;
use Shopware\B2B\Cart\Framework\CartAccessResult;
use Shopware\B2B\LineItemList\Framework\LineItemList;
use Shopware\B2B\OrderClearance\Framework\OrderClearanceEntity;
use Shopware\B2BTest\Common\KernelTestCaseTrait;
use Shopware\B2BTest\Currency\CurrencyFactoryTrait;
use Shopware\B2BTest\Debtor\DebtorFactoryTrait;

/**
 * @coversDefaultClass \B2bContingentsBudgets\Budgets\BudgetCartOrderAccessStrategy
 */
class BudgetCartOrderAccessStrategyTest extends TestCase
{
    use KernelTestCaseTrait;
    use DebtorFactoryTrait;
    use CurrencyFactoryTrait;

    /**
     * @covers ::checkAccess
     */
    public function test_return_null_and_no_error()
    {
        $budgetService = self::getKernel()->getContainer()->get('b2b_budget.service');

        $strategry = new BudgetCartOrderAccessStrategy(self::createContactIdentity(), $budgetService, self::createCurrencyContext());

        $result = new CartAccessResult();
        $conext = new CartAccessContext();

        $conext->orderClearanceEntity = new OrderClearanceEntity();
        $conext->orderClearanceEntity->list = new LineItemList();
        $conext->orderClearanceEntity->list->amountNet = 0;

        $strategry->checkAccess($conext, $result);

        self::assertEquals(0, $result->errorCount);
    }
}
