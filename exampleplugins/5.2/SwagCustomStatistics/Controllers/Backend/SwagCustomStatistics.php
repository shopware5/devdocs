<?php

class Shopware_Controllers_Backend_SwagCustomStatistics extends Shopware_Controllers_Backend_ExtJs
{
    public function getVoucherStatisticsAction()
    {
        $connection = $this->container->get('dbal_connection');
        $query = $connection->createQueryBuilder();
        $query->select(['COUNT(codes.cashed) as amount', 'vouchers.description as name'])
            ->from('s_emarketing_voucher_codes', 'codes')
            ->innerJoin('codes', 's_emarketing_vouchers', 'vouchers', 'vouchers.id = codes.voucherID')
            ->where('codes.cashed = 1')
            ->groupBy('vouchers.id');

        $idList = (string) $this->Request()->getParam('selectedShops');
        if (!empty($idList)) {
            $selectedShopIds = explode(',', $idList);

            foreach ($selectedShopIds as $shopId) {
                $query->addSelect('SUM(IF(vouchers.subshopID = ' . $connection->quote($shopId) . ', codes.cashed, 0)) as amount' . $shopId);
            }
        }

        $data = $query->execute()->fetchAll();

        $this->View()->assign([
            'success' => true,
            'data' => $data,
            'count' => count($data)
        ]);
    }
}
