<?php namespace Test\TTC\Frontend\Profile;

use App\Exceptions\GeneralException;
use App\TTC\Models\Language;
use App\TTC\Models\Profile;
use App\TTC\Repositories\Frontend\ProfileContract;
use App\TTC\Repositories\Frontend\ProfileRepository;
use Test\TTC\BaseTTCTest;

class RepositoryTest extends BaseTTCTest
{

    /**
     * @var ProfileRepository
     */
    private $frontendProfileRepository;

    public function setUp()
    {
        parent::setUp();

        $this->frontendProfileRepository = \App::make(ProfileContract::class);
    }

    public function testFindByIdentifierOrThrowException()
    {
        $this->createLanguage();
        $profile = factory(Profile::class)->create([
            'language_id' => $this->createLanguage()->id,
            'geo_country_id' => $this->createCountry()->id
        ]);

        $foundProfile = $this->frontendProfileRepository->findByIdentifierOrThrowException($profile->identifier);

        $this->assertEquals($profile->id, $foundProfile->id);
    }

    public function testFindNonExistingProfileByIdentifier()
    {
        $this->setExpectedException(GeneralException::class);

        $this->frontendProfileRepository->findByIdentifierOrThrowException("HOWDY! I do not exist!");
    }

    public function testFindById()
    {
        $profile = $this->createProfile();

        $this->frontendProfileRepository->findOrThrowException($profile->id);
    }

    public function testFindByIdThrowsException()
    {
        $this->setExpectedException(GeneralException::class);

        $this->frontendProfileRepository->findOrThrowException(0);
    }
}
