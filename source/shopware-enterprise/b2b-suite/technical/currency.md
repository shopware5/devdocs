---
layout: default
title: Currency
github_link: shopware-enterprise/b2b-suite/technical/currency.md
indexed: true
menu_title: Currency
menu_order: 15
menu_style: numeric
menu_chapter: true
group: Shopware Enterprise
subgroup: B2B-Suite
subsubgroup: Technical Documentation
---

<div class="toc-list"></div>

## Introduction

The Currency component provides the means for currency calculation in the B2B-Suite. The following graph shows components depending on this component:

![image](/assets/img/b2b/currency-usage.svg)


##  The Context

The Currency component provides an additional Context object (`\Shopware\B2B\Currency\Framework\CurrencyContext`) containing a currency factor.
You can retrieve the default context which always contains the currently selected currency factor through the `\Shopware\B2B\Currency\Framework\CurrencyService`.

```php
use Shopware\B2B\Currency\Framework\CurrencyContext;
use Shopware\B2B\Currency\Framework\CurrencyService;

class TestController
{
    /**
     * @var CurrencyService
     */
    private $currencyService;

    /**
     * @param CurrencyService $currencyService
     */
    public function __construct(
        CurrencyService $currencyService
    ) {
        $this->currencyService = $currencyService;
    }

    /**
      * @return array
      */
    public function testAction(): array
    {
        $currencyContext = $this->currencyService
            ->createCurrencyContext();
    }

```

This way you can either store the currency factor with a newly provided amount or retrieve recalculated data from your repository.

##  The Entity

All recalculatable entities must implement the interface `\Shopware\B2B\Currency\Framework\CurrencyAware`.

```php
use Shopware\B2B\Currency\Framework\CurrencyAware;

class MyEntity implements CurrencyAware
{
    /**
     * @var float
     */
    public $amount1;

    /**
     * @var float
     */
    public $amount2;

    /**
     * @var float
     */
    private $factor;

    /**
     * @return float
     */
    public function getCurrencyFactor(): float
    {
        return $this->factor;
    }

    /**
     * @param float $factor
     * @return float
     */
    public function setCurrencyFactor(float $factor)
    {
        $this->factor = $factor;
    }

    /**
     * @return string[]
     */
    public function getAmountPropertyNames(): array
    {
        return [
            'amount1',
            'amount2',
        ];
    }
}
```

Which provides the means to access the currency data.

## The Repository

The Repository has to guarantees that every entity retrieved from storage has valid and if necessary recalculated money values. The Currency component provides `\Shopware\B2B\Currency\Framework\CurrencyCalculator` to help with this promise.
So a typical repository looks like this:

```php

use \Shopware\B2B\Currency\Framework\CurrencyCalculator;

class Repository
{
    /**
     * @var CurrencyCalculator
     */
    private $currencyCalculator;

    /**
     * [...]
     * @param CurrencyCalculator $currencyCalculator
     */
    public function __construct(
        [...]
        CurrencyCalculator $currencyCalculator $calculator
    ) {
        $this-currencyCalculator = $calculator;
    }
}
```

#### Calculating in PHP (prefered)

To recalculate an entity amount the calculator provides two convenient functions.

`recalculateAmount` for a single entity:
```php
    /**
     * [...]
     * @param CurrencyContext $currencyContext
     * @return CurrencyAware
     */
    public function fetchOneById(int $id, CurrencyContext $currencyContext): CurrencyAware
    {
        [...] // load entity from Database

        $this->currencyCalculator->recalculateAmount($entity, $currencyContext);

        return $entity;
    }
```

And `recalculateAmounts` to recalculate an array of entities:

```php
        /**
         * [...]
         * @param CurrencyContext $currencyContext
         * @return CurrencyAware[]
         */
        public function fetchList([...], CurrencyContext $currencyContext): array
        {
            [...] // load entities from Database

            //recalculate with current amount
            $this>currencyCalculator->recalculateAmounts($entities, $currencyContext);

            return $entities;
        }

```

#### Calculating in SQL

Although calculation in PHP is the preferred way, it may sometimes be necessary to recalculate the amounts in SQL. This is the case if you for example use a `GROUP BY` statement and try to create a sum. For this case the Currency component creates a SQL calculation snippet.

So if your original snippet looked like this:


```php

    /**
     * @param int $budgetId
     * @return float
     */
    public function fetchAmount(int $budgetId): float
    {
        return (float) $this-connection->fetchColumn(
            'SELECT SUM(amount) AS sum_amount FROM b2b_budget_transaction WHERE budget_id=:budgetId',
            ['budgetId' => $budgetId]
        )
    }
```

It really should look like this:

```php
    /**
     * @param int $budgetId
     * @param CurrencyContext $currencyContext
     * @return float
     */
    public function fetchAmount(int $budgetId, CurrencyContext $currencyContext): float
    {
        $transactionSnippet = $this->currencyCalculator
            ->getSqlCalculationPart('amount', 'currency_factor', $currencyContext);

        return (float) $this-connection->fetchColumn(
            'SELECT SUM(' . $transactionSnippet . ') AS sum_amount FROM b2b_budget_transaction WHERE budget_id=:budgetId',
            ['budgetId' => $budgetId]
        )
    }
```
