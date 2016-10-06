<?php

namespace App\TTC\Events\Backend\Profile;


use App\TTC\Models\Profile;

/**
 * Class UpdatedProfile
 * @package App\TTC\Events\Backend\Profile
 */
class UpdatedProfile
{
    /**
     * @var Profile
     */
    public $profile;

    /**
     * @var string
     */
    public $name = 'update-profile';

    /**
     * UpdatedProfile constructor.
     * @param $profile
     */
    public function __construct(Profile $profile)
    {
        $this->profile = $profile;
    }
}
