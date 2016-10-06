<?php /* created by Rob van Bentem, 27/07/2015 */

namespace Test\TTC\Api;

use App\Models\Api\Token;
use App\Models\Api\Token\Ip;
use App\TTC\Models\Country;
use App\TTC\Models\Language;
use App\TTC\Models\Profile;
use App\TTC\Repositories\Frontend\ApiRepository;
use Test\TTC\BaseTTCTest;

// declared abstract ~RvB !!!!!
abstract class ApiTest extends BaseTTCTest
{
    /**
     * @var ApiRepository
     */
    private $apiRepository;

    public function setUp()
    {
        parent::setUp();

        $token = Token::create([
            'customer' => 'test',
            'token' => '123',
        ]);

        $ip = Ip::create([
            'api_token_id' => $token->id,
            'ip' => '127.0.0.1'
        ]);

        $lang = Language::create([
            'name' => 'Testlang',
            'iso' => 'testiso'
        ]);

        $country = Country::create([
            'iso' => 'nl'
        ]);

        $this->apiRepository = new ApiRepository();
    }

    public function testInvalidAuth()
    {
        $this->post('/api/v1/profile?token=124')
            ->seeJson(['status' => 'error']);
    }

    public function testCreateProfileWithValidAuth()
    {
        $profile = factory(Profile::class)->make([
            'language' => 'testiso',
            'geo_country' => 'nl',
        ]);

        $this->post('/api/v1/profile?token=123', $profile->getAttributes())
            ->seeJson(['status' => 'created']);
    }

    public function testUpdateProfileWithValidAuth()
    {
        $data = factory(Profile::class)->make([
            'language' => 'testiso',
            'geo_country' => 'nl',
        ])->getAttributes();

        $profile = $this->apiRepository->createOrUpdateProfile($data, $created);


        $this->post('/api/v1/profile?token=123', $data)
            ->seeJson(['status' => 'updated'])
            ->seeJson(['identifier' => $profile->identifier]);
    }

    public function testGetProfileWithValidAuth()
    {
        $profile = factory(Profile::class)->create([
            'language_id' => Language::first()->id,
            'geo_country_id' => Country::first()->id
        ]);

        $phonenumber = (string)$profile->phonenumber;

        $this->get('/api/v1/profile/' . $phonenumber . '?token=123')
            ->seeJson(['phonenumber' => $phonenumber]);
    }
}
