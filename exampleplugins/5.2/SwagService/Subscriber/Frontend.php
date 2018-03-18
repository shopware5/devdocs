<?php

namespace SwagService\Subscriber;

use Enlight\Event\SubscriberInterface;
use SwagService\Components\TaxCalculator;

class Frontend implements SubscriberInterface
{
    /**
     * @var $taxCalculator TaxCalculator
     */
    private $taxCalculator;

    public function __construct(TaxCalculator $taxCalculator)
    {
        $this->taxCalculator = $taxCalculator;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            'Enlight_Controller_Action_PreDispatch_Frontend' => 'onPreDispatch'
        ];
    }

    public function onPreDispatch()
    {
        $this->taxCalculator->calculate(13.99, 1.19);
    }
}