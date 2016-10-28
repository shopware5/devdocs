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
