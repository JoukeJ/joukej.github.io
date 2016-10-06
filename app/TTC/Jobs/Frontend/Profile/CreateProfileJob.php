<?php
/**
 * Created by Luuk Holleman
 * Date: 18/06/15
 */

namespace App\TTC\Jobs\Frontend\Profile;


use App\Jobs\Job;
use App\TTC\Repositories\Frontend\ProfileContract;

/**
 * Class CreateProfileJob
 * @package App\TTC\Jobs\Frontend\Profile
 */
class CreateProfileJob extends Job
{
    /**
     * @var
     */
    public $profiles;

    /**
     * @var array
     */
    public $data;

    /**
     * @param array $data
     * @param ProfileContract $profiles
     */
    public function __construct(array $data, ProfileContract $profiles)
    {
        $this->data = $data;
        $this->profiles = $profiles;
    }

    /**
     * @return \App\TTC\Models\Profile
     */
    public function handle()
    {
        return $this->profiles->create($this->data);
    }
}
