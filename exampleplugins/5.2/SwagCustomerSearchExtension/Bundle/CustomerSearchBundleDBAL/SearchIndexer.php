<?php

namespace SwagCustomerSearchExtension\Bundle\CustomerSearchBundleDBAL;

use Doctrine\DBAL\Connection;
use Shopware\Bundle\CustomerSearchBundleDBAL\Indexing\SearchIndexerInterface;

class SearchIndexer implements SearchIndexerInterface
{
    /**
     * @var SearchIndexerInterface
     */
    private $coreIndexer;

    /**
     * @var Connection
     */
    private $connection;

    /**
     * @param SearchIndexerInterface $coreIndexer
     * @param Connection $connection
     */
    public function __construct(SearchIndexerInterface $coreIndexer, Connection $connection)
    {
        $this->coreIndexer = $coreIndexer;
        $this->connection = $connection;
    }

    public function populate(array $ids)
    {
        $this->coreIndexer->populate($ids);

//        //fetch data
//        $rows = $this->connection->createQueryBuilder()->execute()->fetchAll();
//
//        //create prepared statement for fast inserts
//        $statement = $this->connection->prepare("INSERT INTO test-table");
//
//        //iterate rows and insert data
//        foreach ($rows as $row) {
//            $statement->execute($row);
//        }
    }

    public function clearIndex()
    {
        $this->coreIndexer->clearIndex();
//        $this->connection->executeUpdate("DELETE FROM test-table");
    }

    public function cleanupIndex()
    {
//        $this->coreIndexer->cleanupIndex();
    }
}