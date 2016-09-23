<?php

class Shopware_Controllers_Backend_SwagLastRegistrationsWidget extends Shopware_Controllers_Backend_ExtJs
{
    /**
     * Return the last registered users with an offset if it is defined
     */
    public function getLastRegistrationsAction()
    {
        $start = (int) $this->Request()->getParam('start');
        $limit = (int) $this->Request()->getParam('limit');

        $select = "
            SELECT
                user.id,
                CONCAT(billing.firstname, ' ', billing.lastname) AS customer,
                user.customergroup,
                user.firstlogin as date,
                (SELECT COUNT(*) FROM s_user) AS total
            FROM s_user AS user
            INNER JOIN s_user_billingaddress billing
                ON user.id = billing.userID
            ORDER BY date DESC
            LIMIT $start, $limit
        ";

        $data = Shopware()->Db()->fetchAll($select);

        $this->View()->assign([
            'success' => true,
            'data'    => $data,
            'total'   => $data['total']
        ]);
    }
}