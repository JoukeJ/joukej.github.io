<?php
/**
 * Created by Luuk Holleman
 * Date: 18/06/15
 */

namespace App\TTC\Jobs\Frontend\Profile;


use App\Jobs\Job;
use App\TTC\Repositories\Frontend\ProfileContract;
use Illuminate\Contracts\Bus\SelfHandling;

/**
 * Class UpdateProfileJob
 * @package App\TTC\Jobs\Frontend\Profile
 */
class UpdateProfileJob extends Job implements SelfHandling
{
    /**
     * @var ProfileContract
     */
    public $profiles;

    /**
     * @var int
     */
    public $profileId;

    /**
     * @var array
     */
    public $data;

    /**
     * @param $profileId
     * @param array $data
     * @param ProfileContract $profiles
     */
    public function __construct($profileId, array $data, ProfileContract $profiles)
    {
        $this->profileId = $profileId;
        $this->data = $data;
        $this->profiles = $profiles;
    }

    /**
     * @return \App\TTC\Models\Profile
     */
    public function handle()
    {
        return $this->profiles->update($this->profileId, $this->data);
    }
}
