<?php

namespace App\Console\Commands;

use App\TTC\Jobs\Api\ExportProfileJob;
use App\TTC\Models\Profile;
use App\TTC\Repositories\Frontend\ApiRepository;
use App\TTC\Repositories\Frontend\ProfileContract;
use Illuminate\Console\Command;
use Illuminate\Foundation\Bus\DispatchesJobs;

class ExportProfiles extends Command
{
    use DispatchesJobs;

    /**
     * @var ApiRepository
     */
    protected $api;

    /**
     * @var ProfileContract
     */
    protected $profiles;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'profile:export';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Export updated/new profiles to mash.';

    /**
     * @param ApiRepository $api
     * @param ProfileContract $profiles
     */
    public function __construct(ApiRepository $api, ProfileContract $profiles)
    {
        parent::__construct();

        $this->api = $api;
        $this->profiles = $profiles;
    }

    /**
     * Execute the console command.
     * @return mixed
     */
    public function handle()
    {
        $profiles = $this->api->getProfilesFlaggedForExport();

        foreach ($profiles as $profile) {

            $result = $this->dispatch(app(ExportProfileJob::class, [$profile]));

            if ($result === true) {
                $this->profiles->update($profile->identifier, ['update_flag' => 0]);
            }

            try {

            } catch (\Exception $e) {
                // log to mail/hipchat
            }
        }
    }
}
