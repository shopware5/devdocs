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
