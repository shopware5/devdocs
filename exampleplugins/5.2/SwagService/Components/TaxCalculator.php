<?php

namespace SwagService\Components;

use Shopware\Components\Logger;

class TaxCalculator
{
    /**
     * @var $logger Logger
     */
    private $logger;

    /**
     * @param $logger Logger
     */
    public function __construct(Logger $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @param $netPrice float
     * @param $tax float
     * @return float
     */
    public function calculate($netPrice, $tax)
    {
        $this->logger->debug('Calculating price for tax: ' . $tax);
        return $netPrice * $tax;
    }
}