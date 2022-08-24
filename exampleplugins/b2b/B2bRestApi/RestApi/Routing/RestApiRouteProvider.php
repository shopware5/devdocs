<?php declare(strict_types=1);

namespace B2bRestApi\RestApi\Routing;

use Shopware\B2B\Common\Routing\RouteProvider;

class RestApiRouteProvider implements RouteProvider
{
    /**
     * {@inheritdoc}
     */
    public function getRoutes(): array
    {
        return [
            [
                'GET',
                '/rest/api/{name}',
                'rest_api.controller',
                'hello',
                ['name'],
            ],
        ];
    }
}
