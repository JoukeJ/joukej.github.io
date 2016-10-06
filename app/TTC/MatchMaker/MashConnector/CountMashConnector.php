<?php

namespace App\TTC\MatchMaker\MashConnector;


use App\TTC\Models\Survey;
use GuzzleHttp\Client;
use Illuminate\Support\Collection;

/**
 * Class ProfileFetch
 * @package App\TTC\MatchMaker
 */
class CountMashConnector extends MashConnectorAbstract
{
    /**
     * ProfileFetch constructor.
     * @param Survey $survey
     */
    public function __construct(Survey $survey)
    {
        parent::__construct($survey);
    }

    /**
     * @return string
     */
    public function get()
    {
        $client = new Client();

        $request = $client->createRequest('GET', env('API_MASH_URL') . $this->urlBuilder->count());

        $request->setHeader('Authorization', 'Bearer '. env('API_MASH_KEY'));

        $response = $client->send($request);

        return $response->json();
    }
}
