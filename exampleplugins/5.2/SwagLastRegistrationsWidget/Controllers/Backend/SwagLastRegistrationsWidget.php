<?php

class Shopware_Controllers_Backend_SwagLastRegistrationsWidget extends Shopware_Controllers_Backend_ExtJs
{
    /**
     * Return the last registered users with an offset if it is defined
     */
    public function listAction()
    {
        $start = (int) $this->Request()->getParam('start');
        $limit = (int) $this->Request()->getParam('limit');
        $queryBuilder = Shopware()->Container()->get('dbal_connection')->createQueryBuilder();

        $queryBuilder->select([
            'user.id',
            'CONCAT(billing.firstname, \' \', billing.lastname) AS customer',
            'user.customergroup',
            'user.firstlogin as date',
            '(SELECT COUNT(*) FROM s_user) AS total'
        ])
            ->from('s_user', 'user')
            ->innerJoin('user', 's_user_billingaddress', 'billing', 'user.id = billing.userID')
            ->orderBy('date', 'DESC');

        if (!empty($start)) {
            $queryBuilder->setFirstResult($start);
        }
        if (!empty($limit)) {
            $queryBuilder->setMaxResults($limit);
        }

        $data = $queryBuilder->execute()->fetchAll();

        $this->View()->assign([
            'success' => true,
            'data'    => $data,
            'total'   => $data['total']
        ]);
    }
}
