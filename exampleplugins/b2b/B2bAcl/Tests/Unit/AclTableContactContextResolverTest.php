<?php declare(strict_types=1);

namespace B2bAcl\Tests;

use B2bAcl\Offer\AclTableContactContextResolver;
use Shopware\B2B\Acl\Framework\AclContextResolverMain;
use Shopware\B2B\Acl\Framework\AclUnsupportedContextException;
use Shopware\B2B\Contact\Framework\ContactEntity;
use Shopware\B2B\Contact\Framework\ContactIdentity;
use Shopware\B2B\StoreFrontAuthentication\Framework\OwnershipContext;

class AclTableContactContextResolverTest extends \PHPUnit_Framework_TestCase
{
    public function test_creation_of_resolver()
    {
        $resolver = new AclTableContactContextResolver();
        self::assertInstanceOf(AclContextResolverMain::class, $resolver);

        $this->expectException(AclUnsupportedContextException::class);
        $resolver->extractId('');
    }

    public function test_extract_id_for_contact_entity()
    {
        $context = new ContactEntity();
        $context->id = 42;
        $resolver = new AclTableContactContextResolver();
        self::assertEquals(42, $resolver->extractId($context));
    }

    public function test_extract_id_based_on_ownership_context()
    {
        $context = new OwnershipContext(1, 1, 'foo', 1, 1, ContactIdentity::class);
        $resolver = new AclTableContactContextResolver();
        self::assertEquals(1, $resolver->extractId($context));
    }
}
