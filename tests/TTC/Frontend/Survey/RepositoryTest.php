<?php namespace Test\TTC\Frontend\Survey;

use Test\TTC\BaseTTCTest;

class RepositoryTest extends BaseTTCTest
{

    public function setUp()
    {
        parent::setUp();

        $this->beSuperuser();
    }

    public function testFindSurveyByIdentifiers()
    {
        $survey = $this->getSurvey();
        $foundSurvey = $this->frontendSurveyRepository->findByIdentifierOrThrowException($survey->identifier);

        $this->assertEquals($survey->id, $foundSurvey->id);
    }

    public function testThrowExceptionOnWrongSurveyIdentifier()
    {
        $this->setExpectedException('\App\Exceptions\GeneralException');
        $this->frontendSurveyRepository->findByIdentifierOrThrowException('THIS IDENTIFIER DOES NOT EXIST (i hope?)');
    }
}
