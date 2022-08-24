<?php declare(strict_types=1);

use SwagB2bPlugin\Extension\LoginProtectedControllerProxy;

class Shopware_Controllers_Frontend_B2bCustomerApi extends LoginProtectedControllerProxy
{
    /**
     * @see \B2bCustomerFrontendApi\CustomerApi\CustomerApiController
     * @return string
     */
    protected function getControllerDiKey(): string
    {
        return 'b2b_customer_frontend_api.controller';
    }

    public function postDispatch()
    {
        parent::postDispatch();

        $this->Response()->setHeader('Content-type', 'application/json', true);
    }
}
