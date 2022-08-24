<?php declare(strict_types = 1);

namespace B2bPrice\Price;

use Shopware\B2B\Common\CrudEntity;
use Shopware\B2B\Price\Framework\PriceEntity;
use Shopware\B2B\Price\Framework\PriceSearchStruct;
use Symfony\Component\Intl\Exception\NotImplementedException;

class PriceRepository extends \Shopware\B2B\Price\Framework\PriceRepository
{
    public function __construct()
    {
    }

    /**
     * {@inheritdoc}
     */
    public function fetchPriceByDebtorIdAndOrderNumberAndQuantity(
        int $debtorId,
        string $orderNumber,
        int $quantity
    ): CrudEntity {
        $price = new PriceEntity();
        $price->price = random_int(1, 1337);

        return $price;
    }

    /**
     * {@inheritdoc}
     */
    public function fetchPricesByDebtorIdAndOrderNumber(int $debtorId, array $orderNumbers): array
    {
        $prices = [];
        foreach ($orderNumbers as $orderNumber) {
            $priceEntity = new PriceEntity();
            $priceEntity->price = random_int(1, 1337);
            $priceEntity->orderNumber = $orderNumber;
            $prices[] = $priceEntity;
        }

        return $prices;
    }

    /**
     * {@inheritdoc}
     */
    public function addPrice(PriceEntity $priceEntity): PriceEntity
    {
        throw new NotImplementedException();
    }

    /**
     * {@inheritdoc}
     */
    public function removePrice(PriceEntity $priceEntity): PriceEntity
    {
        throw new NotImplementedException();
    }

    /**
     * {@inheritdoc}
     */
    public function updatePrice(PriceEntity $priceEntity): PriceEntity
    {
        throw new NotImplementedException();
    }

    /**
     * {@inheritdoc}
     */
    public function fetchPricesByDebtorId(int $id, PriceSearchStruct $searchStruct): array
    {
        throw new NotImplementedException();
    }

    /**
     * @param int $id
     * @return PriceEntity
     */
    public function fetchOneById(int $id): PriceEntity
    {
        throw new NotImplementedException();
    }

    /**
     * @param int $id
     * @param PriceSearchStruct $searchStruct
     * @return int
     */
    public function fetchTotalCount(int $id, PriceSearchStruct $searchStruct): int
    {
        throw new NotImplementedException();
    }

    /**
     * @param PriceEntity $priceEntity
     * @return bool
     */
    public function checkForUniquePriceToRange(PriceEntity $priceEntity): bool
    {
        throw new NotImplementedException();
    }

    /**
     * @param PriceEntity $priceEntity
     * @return bool
     */
    public function checkForUniquePriceFromRange(PriceEntity $priceEntity): bool
    {
        throw new NotImplementedException();
    }
}
