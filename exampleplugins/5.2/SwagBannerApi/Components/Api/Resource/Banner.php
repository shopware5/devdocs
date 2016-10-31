<?php

namespace Shopware\Components\Api\Resource;

use Shopware\Components\Api\Exception as ApiException;
use Shopware\Models\Banner\Banner as BannerModel;

/**
 * Class Banner
 *
 * @package Shopware\Components\Api\Resource
 */
class Banner extends Resource
{
    /**
     * @return \Shopware\Models\Article\SupplierRepository
     */
    public function getRepository()
    {
        return $this->getManager()->getRepository(BannerModel::class);
    }

    /**
     * Create new Banner
     *
     * @param array $params
     * @return BannerModel
     * @throws ApiException\ValidationException
     */
    public function create(array $params)
    {
        /** @var BannerModel $Banner */
        $Banner = new BannerModel();

        $Banner->fromArray($params);

        $violations = $this->getManager()->validate($Banner);

        /**
         * Handle Violation Errors
         */
        if ($violations->count() > 0) {
            throw new ApiException\ValidationException($violations);
        }

        $this->getManager()->persist($Banner);
        $this->flush();

        return $Banner;
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
        $builder = $this->getRepository()->createQueryBuilder('banner');

        $builder->addFilter($criteria)
            ->addOrderBy($orderBy)
            ->setFirstResult($offset)
            ->setMaxResults($limit);
        $query = $builder->getQuery();
        $query->setHydrationMode($this->resultMode);

        $paginator = $this->getManager()->createPaginator($query);

        //returns the total count of the query
        $totalResult = $paginator->count();

        //returns the Banner data
        $Banner = $paginator->getIterator()->getArrayCopy();

        return ['data' => $Banner, 'total' => $totalResult];
    }

    /**
     * Delete Existing Banner
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

        $Banner = $this->getRepository()->find($id);

        if (!$Banner) {
            throw new ApiException\NotFoundException("Banner by id $id not found");
        }

        $this->getManager()->remove($Banner);
        $this->flush();
    }

    /**
     * Get One Banner Information
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
            ->createQueryBuilder('Banner')
            ->select('Banner')
            ->where('Banner.id = ?1')
            ->setParameter(1, $id);

        /** @var BannerModel $Banner */
        $Banner = $builder->getQuery()->getOneOrNullResult($this->getResultMode());

        if (!$Banner) {
            throw new ApiException\NotFoundException("Banner by id $id not found");
        }

        return $Banner;
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

        /** @var $Banner BannerModel */
        $builder = $this->getRepository()
            ->createQueryBuilder('Banner')
            ->select('Banner')
            ->where('Banner.id = ?1')
            ->setParameter(1, $id);

        /** @var BannerModel $Banner */
        $Banner = $builder->getQuery()->getOneOrNullResult(self::HYDRATE_OBJECT);

        if (!$Banner) {
            throw new ApiException\NotFoundException("Banner by id $id not found");
        }

        $Banner->fromArray($params);

        $violations = $this->getManager()->validate($Banner);
        if ($violations->count() > 0) {
            throw new ApiException\ValidationException($violations);
        }

        $this->flush();

        return $Banner;
    }
}
