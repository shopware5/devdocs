<?php

/**
 * Class Shopware_Controllers_Api_Banner
 */
class Shopware_Controllers_Api_Banner extends Shopware_Controllers_Api_Rest
{
    /**
     * @var Shopware\Components\Api\Resource\Banner
     */
    protected $resource = null;

    public function init()
    {
        $this->resource = \Shopware\Components\Api\Manager::getResource('Banner');
    }

    /**
     * GET Request on /api/Banner
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
     * Create new Banner
     *
     * POST /api/Banner
     */
    public function postAction()
    {
        $Banner = $this->resource->create($this->Request()->getPost());

        $location = $this->apiBaseUrl . 'Banner/' . $Banner->getId();

        $data = [
            'id' => $Banner->getId(),
            'location' => $location,
        ];
        $this->View()->assign(['success' => true, 'data' => $data]);
        $this->Response()->setHeader('Location', $location);
    }

    /**
     * Get one Banner
     *
     * GET /api/Banner/{id}
     */
    public function getAction()
    {
        $id = $this->Request()->getParam('id');
        /** @var \Shopware\Models\Banner\Banner $Banner */
        $Banner = $this->resource->getOne($id);

        $this->View()->assign(['success' => true, 'data' => $Banner]);
    }

    /**
     * Update One Banner
     *
     * PUT /api/Banner/{id}
     */
    public function putAction()
    {
        $BannerId = $this->Request()->getParam('id');
        $params = $this->Request()->getPost();

        /** @var \Shopware\Models\Banner\Banner $Banner */
        $Banner = $this->resource->update($BannerId, $params);

        $location = $this->apiBaseUrl . 'Banner/' . $BannerId;
        $data = [
            'id' => $Banner->getId(),
            'location' => $location
        ];

        $this->View()->assign(['success' => true, 'data' => $data]);
    }

    /**
     * Delete One Banner
     *
     * DELETE /api/Banner/{id}
     */
    public function deleteAction()
    {
        $BannerId = $this->Request()->getParam('id');

        $this->resource->delete($BannerId);

        $this->View()->assign(['success' => true]);
    }
}
