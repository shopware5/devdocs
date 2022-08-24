<?php declare(strict_types=1);

namespace B2bLogin\Contact;

use Doctrine\DBAL\Connection;
use Shopware\B2B\Common\Repository\DbalHelper;
use Shopware\B2B\Contact\Framework\ContactEntity;
use Shopware\B2B\Contact\Framework\ContactRepository;
use Shopware\B2B\Debtor\Framework\DebtorRepository;
use Shopware\B2B\StoreFrontAuthentication\Framework\StoreFrontAuthenticationRepository;

class B2bContactRepository extends ContactRepository
{
    const TABLE_NAME = 'b2b_debtor_contact';
    const TABLE_ALIAS = 'contact';

    /**
     * @var Connection
     */
    private $connection;

    /**
     * @var DebtorRepository
     */
    private $debtorRepository;

    /**
     * @var DbalHelper
     */
    private $dbalHelper;

    /**
     * @var StoreFrontAuthenticationRepository
     */
    private $authenticationRepository;

    /**
     * @param Connection $connection
     * @param DbalHelper $dbalHelper
     * @param DebtorRepository $debtorRepository
     * @param StoreFrontAuthenticationRepository $authenticationRepository
     */
    public function __construct(
        Connection $connection,
        DbalHelper $dbalHelper,
        DebtorRepository $debtorRepository,
        StoreFrontAuthenticationRepository $authenticationRepository
    ) {
        $this->connection = $connection;
        $this->debtorRepository = $debtorRepository;
        $this->dbalHelper = $dbalHelper;
        $this->authenticationRepository = $authenticationRepository;
        parent::__construct($connection, $dbalHelper, $debtorRepository, $authenticationRepository);
    }

    /**
     * @param string $staffId
     * @return ContactEntity
     */
    public function fetchOneByStaffId(string $staffId): ContactEntity
    {
        $statement = $this->connection->createQueryBuilder()
            ->select('*')
            ->from(self::TABLE_NAME, self::TABLE_ALIAS)
            ->where(self::TABLE_ALIAS . '.staff_id = :staffId')
            ->setParameter('staffId', $staffId)
            ->execute();

        $contactData = $statement->fetch(\PDO::FETCH_ASSOC);

        return parent::createContactByContactData($contactData, $staffId);
    }
}
