<?php namespace Test\TTC\Frontend\Entity;

use Test\TTC\BaseTTCTest;

class RepositoryTest extends BaseTTCTest
{

    public function setUp()
    {
        parent::setUp();

        $this->beSuperuser();
    }

    public function testFindEntityByIdentifiers()
    {
        $entity = $this->createEntityRadioQuestion();
        $foundEntity = $this->frontendEntityRepository->findByIdentifierOrThrowException($entity->identifier);

        $this->assertEquals($entity->id, $foundEntity->id);
    }

    public function testThrowExceptionOnWrongEntityIdentifier()
    {
        $this->setExpectedException('\App\Exceptions\GeneralException');
        $this->frontendEntityRepository->findByIdentifierOrThrowException('THIS IDENTIFIER DOES NOT EXIST (i hope?)');
    }

}
