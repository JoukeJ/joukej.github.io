<?php namespace Test\TTC\Backend\Entity;

use App\TTC\Factories\EntityFactory;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Test\TTC\BaseTTCTest;

class ModelTest extends BaseTTCTest
{
    use WithoutMiddleware;

    public function setUp()
    {
        parent::setUp();

        $this->beSuperuser();
    }

    public function testGetShortType()
    {
        $types = \Config::get('ttc.entity.types');

        foreach ($types as $name => $type) {
            $entity = EntityFactory::make($name);

            $this->assertEquals($name, $entity->getShortType());
        }
    }
}
