<?php

namespace App\TTC\Jobs\Backend\Entity;

use App\Jobs\Job;
use App\TTC\Repositories\Backend\EntityContract;
use Illuminate\Contracts\Bus\SelfHandling;

class CreateEntityJob extends Job implements SelfHandling
{

    /**
     * @var EntityContract
     */
    private $entities;

    /**
     * @param EntityContract $entities
     */
    public function __construct(EntityContract $entities)
    {
        $this->entities = $entities;
    }

    /**
     * @return \App\TTC\Models\Survey\Entity
     */
    public function handle()
    {
        return $this->entities->create(\Input::except('afterId'), \Input::get('afterId'));
    }
}
