<?php declare(strict_types=1);

namespace B2bAcl\Tests\Unit;

use B2bAcl\Offer\AclConfig;

class AclConfigTest extends \PHPUnit_Framework_TestCase
{
    public function test_config_returns_array()
    {
        $aclConfig = AclConfig::getAclConfigArray();
        self::assertInternalType('array', $aclConfig);
    }
}
