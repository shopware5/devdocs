<?php

namespace SwagPaymentExample\Components\ExamplePayment;

class PaymentResponse
{
    /**
     * @var int
     */
    public $transactionId;

    /**
     * @var string
     */
    public $token;

    /**
     * @var string
     */
    public $status;
}
