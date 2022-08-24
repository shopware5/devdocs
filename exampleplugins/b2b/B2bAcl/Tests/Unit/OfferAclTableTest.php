<?php declare(strict_types=1);

namespace B2bAcl\Tests;

use B2bAcl\Offer\AclTableContactContextResolver;
use B2bAcl\Offer\OfferAclTable;

class OfferAclTableTest extends \PHPUnit_Framework_TestCase
{
    public function test_creation_of_acl_table()
    {
        $aclTable = new OfferAclTable();
        self::assertInstanceOf(OfferAclTable::class, $aclTable);

        $method = new \ReflectionMethod('\\B2bAcl\\Offer\\OfferAclTable', 'getContextResolvers');
        $method->setAccessible(true);
        $resolvers = $method->invoke(new OfferAclTable());

        self::assertInternalType('array', $resolvers);
        self::assertContainsOnlyInstancesOf(AclTableContactContextResolver::class, $resolvers);
    }
}
