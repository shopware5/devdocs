<?php declare(strict_types = 1);

namespace B2bAcl\Tests\Integration;

use B2bAcl\Offer\OfferRepository;
use B2bAcl\Offer\OfferSearchStruct;
use Shopware\B2BTest\Common\KernelTestCaseTrait;
use Shopware\B2BTest\Debtor\MockIdentity;

class OfferRepositoryTest extends \PHPUnit_Framework_TestCase
{
    use KernelTestCaseTrait;

    /**
     * @return OfferRepository
     */
    private function getRepository(): OfferRepository
    {
        return self::getKernel()->getContainer()->get('b2b_offer.repository');
    }

    public function test_repository_instance()
    {
        self::assertInstanceOf(OfferRepository::class, $this->getRepository());
    }

    public function test_repository()
    {
        $identity = new MockIdentity();
        $ownershipContext = $identity->getOwnershipContext();

        $ownershipContext->shopOwnerUserId = 250;
        $ownershipContext->identityId = 11;
        $ownershipContext->identityClassName = \Shopware\B2B\Contact\Framework\ContactIdentity::class;

        $searchStruct = new OfferSearchStruct();
        $result = $this->getRepository()->fetchList($ownershipContext, $searchStruct);

        self::assertCount(0, $result);

        $result = $this->getRepository()->fetchTotalCount($ownershipContext, $searchStruct);

        self::assertEquals(0, $result);
    }
}
