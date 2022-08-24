<?php declare(strict_types = 1);

namespace B2bAcl\Tests\Integration;

use B2bAcl\Offer\OfferCrudService;
use B2bAcl\Offer\OfferEntity;
use Shopware\B2B\Common\Service\CrudServiceRequest;
use Shopware\B2BTest\Common\KernelTestCaseTrait;
use Shopware\B2BTest\Debtor\DebtorFactoryTrait;

class ContingentGroupServiceTest extends \PHPUnit_Framework_TestCase
{
    use KernelTestCaseTrait;
    use DebtorFactoryTrait;

    /**
     * @return OfferCrudService
     */
    private function getCrudService(): OfferCrudService
    {
        return self::getKernel()->getContainer()->get('b2b_offer.crud_service');
    }

    public function test_crud_service_instance()
    {
        self::assertInstanceOf(OfferCrudService::class, $this->getCrudService());
    }

    public function test_crud_service()
    {
        $this->importFixtures($this->loadCommonFixtureSql());

        $data = ['name' => 'name', 'description' => 'description'];
        $createRequest = $this->getCrudService()->createNewRecordRequest($data);

        self::assertInstanceOf(CrudServiceRequest::class, $createRequest);

        $ownershipContext = self::createContactIdentity()
            ->getOwnershipContext();

        $offerEntity = $this->getCrudService()
            ->create($createRequest, $ownershipContext);

        self::assertInstanceOf(OfferEntity::class, $offerEntity);

        $offerEntity->description = 'updated Description';

        $existingRecordRequest = $this->getCrudService()->createExistingRecordRequest($offerEntity->toArray());

        $offerEntity = $this->getCrudService()->update($existingRecordRequest, $ownershipContext);

        self::assertInstanceOf(OfferEntity::class, $offerEntity);
        self::assertEquals('updated Description', $offerEntity->description);

        $existingRecordRequest = $this->getCrudService()->createExistingRecordRequest($offerEntity->toArray());

        $offerEntity = $this->getCrudService()->remove($existingRecordRequest);

        self::assertInstanceOf(OfferEntity::class, $offerEntity);
        self::assertNull($offerEntity->id);
    }
}
