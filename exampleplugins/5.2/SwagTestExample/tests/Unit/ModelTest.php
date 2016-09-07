<?php

use Shopware\Components\Test\Plugin\TestCase;
use SwagTestExample\Models\TestModel;

class ModelTest extends TestCase
{
    public function testModelCreation()
    {
        $model = new TestModel();

        $data = [
            'name' => 'foo',
            'description' => 'bar'
        ];

        $model->fromArray($data);

        $this->assertInstanceOf(TestModel::class, $model);
        $this->assertEquals('foo', $model->getName());
        $this->assertEquals('bar', $model->getDescription());
    }
}
