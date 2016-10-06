<?php

namespace App\TTC\Jobs\Backend\Entity;

use App\Jobs\Job;
use App\TTC\Repositories\Backend\EntityContract;
use Illuminate\Contracts\Bus\SelfHandling;

class DeleteEntityJob extends Job implements SelfHandling
{

    /**
     * @var EntityContract
     */
    private $entities;

    /**
     * DeleteEntityJob constructor.
     * @param EntityContract $entities
     */
    public function __construct(EntityContract $entities)
    {
        $this->entities = $entities;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->entities->delete(\Route::current()->getParameter('entities'));
    }
}
