---
layout: default
title: Create your own REST API endpoint
github_link: developers-guide/rest-api/plugin-api-extension/index.md
shopware_version: 5.2.0
indexed: true
tags:
  - extend
  - api
  - Plugin
group: Developer Guides
subgroup: REST API
menu_title: Create your own endpoint
menu_order: 130
---

<div class="toc-list"></div>

## Introduction
This article describes how to extend the REST API and create an API endpoint. We create an exmaple plugin which 
provides functions for managing manufacturer.

Normally every basic API resource contains of two parts:
* a controller which handles the different request types(POST, GET, PUT, DELETE)
* the actual resource that takes care of the [CRUD](https://en.wikipedia.org/wiki/Create,_read,_update_and_delete)
operations

### The File and directory structure
<img src="img/file_structure.jpg" alt="API endpoint file structure"/>

## Plugin files
For our REST API example we only need a few files. For more information about necessary files and the 5.2 plugin system
see the [5.2 plugin guide](/developers-guide/plugin-system).

### plugin.xml
contains the base information about the plugin, the label in englisch and german, the plugin version and the required
shopware version.

```
<?xml version="1.0" encoding="utf-8"?>
<plugin xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
        xsi:noNamespaceSchemaLocation="../../../engine/Shopware/Components/Plugin/schema/plugin.xsd">
    <label lang="de">Swag Hersteller API</label>
    <label lang="en">Swag Producer API</label>
    <version>1.0.0</version>
    <compatibility minVersion="5.2.0"/>
</plugin>

```

### SwagProducerApi.php

```
<?php

namespace SwagProducerApi;

use Shopware\Components\Plugin;

class SwagProducerApi extends Plugin
{
    /**
     * @inheritdoc
     */
    public static function getSubscribedEvents()
    {
        return [
            'Enlight_Controller_Dispatcher_ControllerPath_Api_Producer' => 'onGetProducerApiController',
            'Enlight_Controller_Front_StartDispatch' => 'onEnlightControllerFrontStartDispatch'
        ];
    }

    /**
     * @return string
     */
    public function onGetProducerApiController()
    {
        return $this->getPath() . '/Controllers/Api/Producer.php';
    }

    /**
     *
     */
    public function onEnlightControllerFrontStartDispatch()
    {
        $this->container->get('loader')->registerNamespace('Shopware\Components', $this->getPath() . '/Components/');
    }
}
```
This is our plugin bootstrap which subscribes to two events. For the one thing it uses the
`Enlight_Controller_Dispatcher_ControllerPath_Api` event to register the API controller and for 
the other thing it uses the `Enlight_Controller_Front_StartDispatch` event to register our resource to Shopware.

### Components/Api/Resource/Producer.php
The resource gets called by our controller. Every controller action relies on one method of our resource. 
* indexAction -> getList() -> returns a list of producers
* getAction -> getOne() -> returns one producer identified by its id
* putAction -> update() -> updates one producer identified by its id
* postAction -> create() -> creates a new producer
* deleteAction -> delete() -> deletes a producer

We recommend using doctrine models in the resource, because it allows you to use the `fromArray()` method in the 
`create()` and `update()` method to write the data directly. `fromArray()` searches for the setter methods of the 
attributes and saves the values to the variables which saves you time and code.

```
<?php

namespace Shopware\Components\Api\Resource;

use Shopware\Components\Api\Exception as ApiException;
use Shopware\Models\Article\Supplier as SupplierModel;

/**
 * Class Producer
 *
 * @package Shopware\Components\Api\Resource
 */
class Producer extends Resource
{
    /**
     * @return \Shopware\Models\Article\SupplierRepository
     */
    public function getRepository()
    {
        return $this->getManager()->getRepository(SupplierModel::class);
    }

    /**
     * Create new Producer
     *
     * @param array $params
     * @return SupplierModel
     * @throws ApiException\ValidationException
     */
    public function create(array $params)
    {
        /** @var SupplierModel $producer */
        $producer = new SupplierModel();

        $producer->fromArray($params);

        $violations = $this->getManager()->validate($producer);

        /**
         * Handle Violation Errors
         */
        if ($violations->count() > 0) {
            throw new ApiException\ValidationException($violations);
        }

        $this->getManager()->persist($producer);
        $this->flush();

        return $producer;
    }

    /**
     * @param int $offset
     * @param int $limit
     * @param array $criteria
     * @param array $orderBy
     * @return array
     */
    public function getList($offset = 0, $limit = 25, array $criteria = [], array $orderBy = [])
    {
        $builder = $this->getRepository()->createQueryBuilder('supplier');

        $builder->addFilter($criteria)
            ->addOrderBy($orderBy)
            ->setFirstResult($offset)
            ->setMaxResults($limit);
        $query = $builder->getQuery();
        $query->setHydrationMode($this->resultMode);

        $paginator = $this->getManager()->createPaginator($query);

        //returns the total count of the query
        $totalResult = $paginator->count();

        //returns the producer data
        $producer = $paginator->getIterator()->getArrayCopy();

        return ['data' => $producer, 'total' => $totalResult];
    }

    /**
     * Delete Existing Producer
     *
     * @param $id
     * @return null|object
     * @throws ApiException\NotFoundException
     * @throws ApiException\ParameterMissingException
     */
    public function delete($id)
    {
        $this->checkPrivilege('delete');

        if (empty($id)) {
            throw new ApiException\ParameterMissingException();
        }

        $producer = $this->getRepository()->find($id);

        if (!$producer) {
            throw new ApiException\NotFoundException("Producer by id $id not found");
        }

        $this->getManager()->remove($producer);
        $this->flush();
    }

    /**
     * Get One Producer Information
     *
     * @param $id
     * @return mixed
     * @throws ApiException\NotFoundException
     * @throws ApiException\ParameterMissingException
     */
    public function getOne($id)
    {
        $this->checkPrivilege('read');

        if (empty($id)) {
            throw new ApiException\ParameterMissingException();
        }

        $builder = $this->getRepository()
            ->createQueryBuilder('Supplier')
            ->select('Supplier')
            ->where('Supplier.id = ?1')
            ->setParameter(1, $id);

        /** @var SupplierModel $producer */
        $producer = $builder->getQuery()->getOneOrNullResult($this->getResultMode());

        if (!$producer) {
            throw new ApiException\NotFoundException("Producer by id $id not found");
        }

        return $producer;
    }

    /**
     * @param $id
     * @param array $params
     * @return null|object
     * @throws ApiException\ValidationException
     * @throws ApiException\NotFoundException
     * @throws ApiException\ParameterMissingException
     */
    public function update($id, array $params)
    {
        $this->checkPrivilege('update');

        if (empty($id)) {
            throw new ApiException\ParameterMissingException();
        }

        /** @var $producer SupplierModel */
        $builder = $this->getRepository()
            ->createQueryBuilder('Supplier')
            ->select('Supplier')
            ->where('Supplier.id = ?1')
            ->setParameter(1, $id);

        /** @var SupplierModel $producer */
        $producer = $builder->getQuery()->getOneOrNullResult(self::HYDRATE_OBJECT);

        if (!$producer) {
            throw new ApiException\NotFoundException("Producer by id $id not found");
        }

        $producer->fromArray($params);

        $violations = $this->getManager()->validate($producer);
        if ($violations->count() > 0) {
            throw new ApiException\ValidationException($violations);
        }

        $this->flush();

        return $producer;
    }
}

```

### Controllers/Api/Producer
The controller handles all requests to our REST API endpoint. Authorisation and routing is managed by the Shopware REST API.

You should name your actions after this schema:

| Action name        | Request type   | parameter    |
|--------------------|----------------|--------------|
| indexAction        | get request    | no           |
| getAction          | get request    | `id`         |
| batchAction        | put request    | no           |
| putAction          | put request    | `id`         |
| postAction         | post request   | data fields  |
| batchDeleteAction  | delete request | no           |
| deleteAction       | delete request | `id`         |


```
<?php

/**
 * Class Shopware_Controllers_Api_Producer
 */
class Shopware_Controllers_Api_Producer extends Shopware_Controllers_Api_Rest
{
    /**
     * @var Shopware\Components\Api\Resource\Producer
     */
    protected $resource = null;

    public function init()
    {
        $this->resource = \Shopware\Components\Api\Manager::getResource('Producer');
    }

    /**
     * GET Request on /api/Producer
     */
    public function indexAction()
    {
        $limit = $this->Request()->getParam('limit', 1000);
        $offset = $this->Request()->getParam('start', 0);
        $sort = $this->Request()->getParam('sort', []);
        $filter = $this->Request()->getParam('filter', []);

        $result = $this->resource->getList($offset, $limit, $filter, $sort);

        $this->View()->assign(['success' => true, 'data' => $result]);
    }

    /**
     * Create new Producer
     *
     * POST /api/producer
     */
    public function postAction()
    {
        $producer = $this->resource->create($this->Request()->getPost());

        $location = $this->apiBaseUrl . 'producer/' . $producer->getId();

        $data = [
            'id' => $producer->getId(),
            'location' => $location,
        ];
        $this->View()->assign(['success' => true, 'data' => $data]);
        $this->Response()->setHeader('Location', $location);
    }

    /**
     * Get one Producer
     *
     * GET /api/producer/{id}
     */
    public function getAction()
    {
        $id = $this->Request()->getParam('id');
        /** @var \Shopware\Models\Article\Supplier $producer */
        $producer = $this->resource->getOne($id);

        $this->View()->assign(['success' => true, 'data' => $producer]);
    }

    /**
     * Update One Producer
     *
     * PUT /api/producer/{id}
     */
    public function putAction()
    {
        $producerId = $this->Request()->getParam('id');
        $params = $this->Request()->getPost();

        /** @var \Shopware\Models\Article\Supplier $producer */
        $producer = $this->resource->update($producerId, $params);

        $location = $this->apiBaseUrl . 'producer/' . $producerId;
        $data = [
            'id' => $producer->getId(),
            'location' => $location
        ];

        $this->View()->assign(['success' => true, 'data' => $data]);
    }

    /**
     * Delete One Producer
     *
     * DELETE /api/producer/{id}
     */
    public function deleteAction()
    {
        $producerId = $this->Request()->getParam('id');

        $this->resource->delete($producerId);

        $this->View()->assign(['success' => true]);
    }
}
```

## Test the API
This resource supports the following operations:

|  Access URL                 | GET                   | GET (List)            | PUT                    | PUT (Batch)         | POST                 | DELETE                | DELETE (Batch)      |
|-----------------------------|-----------------------|-----------------------|------------------------|---------------------|----------------------|-----------------------|---------------------|
| /api/producer               | ![Yes](../img/yes.png) | ![Yes](../img/yes.png) |  ![Yes](../img/yes.png) | ![No](../img/no.png) | ![Yes](../img/yes.png) | ![Yes](../img/yes.png) | ![No](../img/no.png) |

If you want to access this resource, simply query the following URL:
* http://my-shop-url/api/producer

The following examples assume you are using the provided
**[demo API client](/developers-guide/rest-api/#using-the-rest-api-in-your-own-a)**. One of its advantages is that, 
instead of providing query arguments directly in the URL, you can do so by means of method argument.
The client application will, internally, handle the full URL generation. You can also place variables using
this technique.

### GET
Getting one producer by its id.
```
$client->get('producer/1');
```

Result:
```
{
  "data": {
    "id": 1,
    "name": "shopware AG",
    "image": "",
    "link": "http://www.shopware.de",
    "description": "",
    "metaTitle": null,
    "metaDescription": "New description",
    "metaKeywords": null,
    "changed": "2016-10-21T13:53:42+0200"
  },
  "success": true
}
```

### GET(List)
Get a list of producers. With the optional `limit` parameter, it is possible to specify how many producer you want the 
API to return.
```
$client->get('producer?limit=3');
```

Result
```
{
  "data": [
    {
      "id": 1,
      "name": "shopware AG",
      "image": "",
      "link": "http://www.shopware.de",
      "description": "",
      "metaTitle": null,
      "metaDescription": "New description",
      "metaKeywords": null,
      "changed": "2016-10-21T13:53:42+0200"
    },
    {
      "id": 2,
      "name": "Feinbrennerei Sasse",
      "image": "media/image/sasse.png",
      "link": "http://www.sassekorn.de",
      "description": "",
      "metaTitle": null,
      "metaDescription": null,
      "metaKeywords": null,
      "changed": "2016-10-21T13:53:42+0200"
    },
    {
      "id": 3,
      "name": "Teapavilion",
      "image": "media/image/tea.png",
      "link": "http://www.teapavilion.com",
      "description": "",
      "metaTitle": null,
      "metaDescription": null,
      "metaKeywords": null,
      "changed": "2016-10-21T13:53:42+0200"
    }
  ],
  "total": 16,
  "success": true
}
```

### PUT
To update an producer it is always required to provide the id of the producer. In this example, we will update the 
`metaDescription` of the producer with id 1.
```
$client->put('producer/1', [
   'metaDescription' => 'New description'
]);
```

Result:
```
{
  "success": true,
  "data": {
    "id": 1,
    "location": "http://my-shop-url/api/producer/1"
  }
}
```

### POST
Create a new producer

```
$client->post('producer', [
    'name' => 'Swag Producer API test'
]);
```

Result:
```
{
  "success": true,
  "data": {
    "id": 19,
    "location": "http://my-shop-url/api/producer/19"
  }
}
```

### DELETE
Delete one producer identified by its id. In this case we delete the producer with id 1.

```
$client->delete('producer/1');
```

Result:
```
{
  "success": true
}
```

## Download plugin ##
The whole plugin can be downloaded <a href="{{ site.url }}/exampleplugins/SwagProducerApi.zip">here</a>.


