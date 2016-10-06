<?php

namespace App\TTC\Http\Controllers\Api\V1;

use App\Exceptions\GeneralException;
use App\Http\Controllers\Controller;
use App\TTC\Exceptions\Api\ValidationFailedException;
use App\TTC\Models\Profile;
use App\TTC\Repositories\Frontend\ApiRepository;

/**
 * Class ProfileController
 * @package App\TTC\Http\Controllers\Api\V1
 */
class ProfileController extends Controller
{
    /**
     * @var ApiRepository
     */
    private $apiRepository;

    /**
     * @param ApiRepository $apiRepository
     */
    public function __construct(ApiRepository $apiRepository)
    {
        $this->apiRepository = $apiRepository;
    }

    /**
     * @param $phone
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model
     */
    public function detail($phone)
    {
        try {
            $profile = $this->apiRepository->findByPhonenumber($phone);

            return array_merge(
                [
                    'identifier' => $profile->identifier,
                    'url' => $profile->getShortUrl()
                ],
                $profile->toArray(),
                ['status' => 'found']);
        } catch (\Exception $e) {
            return ['status' => 'not found'];
        }
    }

    /**
     * @return array
     * @throws GeneralException
     * @throws ValidationFailedException
     */
    public function createOrUpdate()
    {
        $created = false;

        $profile = $this->apiRepository->createOrUpdateProfile(\Input::all(), $created);

        return [
            'identifier' => $profile->identifier,
            'url' => $profile->getShortUrl(),
            'status' => ($created ? 'created' : 'updated')
        ];
    }
}
