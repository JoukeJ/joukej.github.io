<?php

namespace App\TTC\Jobs\Backend\Entity;

use App\Jobs\Job;
use App\TTC\Models\Survey\Entity;
use App\TTC\Repositories\Backend\EntityContract;
use Illuminate\Contracts\Bus\SelfHandling;

class UpdateEntityJob extends Job implements SelfHandling
{
    /**
     * @var EntityContract
     */
    private $entities;

    /**
     * UpdateEntityJob constructor.
     * @param EntityContract $entities
     */
    public function __construct(EntityContract $entities)
    {
        $this->entities = $entities;
    }

    /**
     * Execute the job.
     *
     * @return Entity
     */
    public function handle()
    {
        return $this->entities->update(\Route::current()->getParameter('entities'), \Input::all());
    }
}
