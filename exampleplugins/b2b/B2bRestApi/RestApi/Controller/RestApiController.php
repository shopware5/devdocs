<?php declare(strict_types=1);

namespace B2bRestApi\RestApi\Controller;

use Shopware\B2B\Common\MvcExtension\Request;

class RestApiController
{
    public function helloAction($name, Request $request)
    {
        return ['message' => 'hello ' . $name];
    }
}
