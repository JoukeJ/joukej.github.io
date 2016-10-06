<?php

namespace App\TTC\MatchMaker\MashConnector;


use App\TTC\MatchMaker\MashConnector\Events\MashEventInterface;
use GuzzleHttp\Client;

class ProfileConnector
{
    private $profile;

    /**
     * ProfileUpdater constructor.
     * @param $profile
     */
    public function __construct($profile)
    {
        $this->profile = $profile;
    }

    public function updateProfile()
    {
        $client = new Client();

        $request = $client->createRequest('POST', env('API_MASH_URL') . 'participants/'.$this->profile->mash_identifier);

        $request->setHeader('Authorization', 'Bearer '. env('API_MASH_KEY'));

        $response = $client->send($request, [
            'profile' => [
                'gender' => $this->profile->gender,
                'dob' => date('Y-m-d', strtotime($this->profile->birthday)),
                'location' => $this->profile->country->iso,
            ]
        ]);

        return $response->json();
    }

    public function sendEvent(MashEventInterface $mashEvent)
    {
        $client = new Client();

        $request = $client->createRequest('POST', env('API_MASH_URL') . 'participants/'.$this->profile->mash_identifier.'/events');

        $request->setHeader('Authorization', 'Bearer '. env('API_MASH_KEY'));

        $response = $client->send($request, [
            'pid' => 'TODO',
            'pname' => $mashEvent->getPName(),
            'event' => $mashEvent->getEvent()
        ]);

        return $response->json();
    }
}
