<?php

namespace Shopware\SwagService\Component;

class TaxCalculator
{
    /** @var \Shopware\Components\Logger  */
    private $logger;

    public function __construct(\Shopware\Components\Logger $logger)
    {
        $this->logger = $logger;
    }

    public function calculate($netPrice, $tax)
    {
        // Bypass the two fingers crossed handler, by setting an `alert` message
        // By default Shopware would not show `debug` or `info` level debug messages in the log
        $this->logger->alert('Calculating price for tax: ' . $tax);
        return $netPrice * $tax;
    }
}