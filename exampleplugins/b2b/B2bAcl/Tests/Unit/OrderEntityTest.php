<?php declare(strict_types=1);

namespace B2bAcl\Tests;

use B2bAcl\Offer\OfferEntity;

class OrderEntityTest extends \PHPUnit_Framework_TestCase
{
    public function test_model_creation_from_database()
    {
        /** @var OfferEntity $entity */
        $entity = new OfferEntity();

        $data = [
            'id' => '123',
            's_user_id' => '250',
            'name' => 'foo',
            'description' => 'bar',
        ];

        $entity->fromDatabaseArray($data);

        $this->assertInstanceOf(OfferEntity::class, $entity);
        $this->assertEquals('123', $entity->id);
        $this->assertEquals('foo', $entity->name);
        $this->assertEquals('bar', $entity->description);
        $this->assertEquals('250', $entity->sUserId);

        $toDatabaseArray = $entity->toDatabaseArray();

        self::assertEquals($data, $toDatabaseArray);

        self::assertFalse($entity->isNew());
    }

    public function test_set_data()
    {
        /** @var OfferEntity $entity */
        $entity = new OfferEntity();

        $data = [
            's_user_id' => '250',
            'name' => 'foo',
            'description' => 'bar',
            'skippingParameter' => true,
        ];

        $entity->setData($data);

        self::assertNotEquals($data, $entity->toDatabaseArray());
        self::assertJson(json_encode($entity));
    }
}
