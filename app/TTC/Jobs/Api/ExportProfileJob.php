<?php /* created by Rob van Bentem, 27/08/2015 */

namespace App\TTC\Jobs\Api;

use App\Jobs\Job;
use App\TTC\Models\Profile;
use GuzzleHttp\Client;
use Illuminate\Contracts\Bus\SelfHandling;

class ExportProfileJob extends Job implements SelfHandling
{
    /**
     * @var Profile
     */
    protected $profile;

    /**
     * ExportProfileJob constructor.
     * @param Profile $profile
     */
    public function __construct(Profile $profile)
    {
        $this->profile = $profile;
    }

    /**
     * @todo implement further
     */
    public function handle()
    {
        $data = $this->getExportArray();

        $client = new Client();
        $response = $client->post(env('API_MASH_URL'), [
            'headers' => ['Content-Type' => 'application/json'],
            'body' => $data,
        ]);

        return (int)$response->getStatusCode() === 200;
    }

    /**
     * @return array
     */
    protected function getExportArray()
    {
        return array_merge(
            [
                'identifier' => $this->profile->identifier,
                'url' => $this->profile->getShortUrl()
            ],
            $this->profile->toArray()
        );
    }


}
