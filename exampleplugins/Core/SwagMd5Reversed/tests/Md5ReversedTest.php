<?php


/**
 * @backupGlobals disabled
 * @backupStaticAttributes disabled
 */
class Md5ReversedTest extends Shopware\Components\Test\Plugin\TestCase
{
    public function testHashEncoder()
    {
        $encoder = new \Shopware\SwagMd5Reversed\Md5ReversedEncoder();
        $hash = $encoder->encodePassword('secret');

        $this->assertEquals($hash, 'a7e86e2302d08ea6d3ff635f856468f4');
    }

    public function testIsValid()
    {
        $encoder = new \Shopware\SwagMd5Reversed\Md5ReversedEncoder();
        $isValid = $encoder->isPasswordValid('secret', 'a7e86e2302d08ea6d3ff635f856468f4');

        $this->assertTrue($isValid);
    }

    public function testInvalidPassword()
    {
        $encoder = new \Shopware\SwagMd5Reversed\Md5ReversedEncoder();
        $isValid = $encoder->isPasswordValid('secret', 'some random hash');

        $this->assertFalse($isValid);
    }


}