<?php

namespace App\TTC\Jobs\Backend\Entity;

use App\Jobs\Job;
use App\TTC\Repositories\Backend\EntityContract;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Support\Arr;

class SyncSkipLogicJob extends Job implements SelfHandling
{

    /**
     * @var string[]
     */
    public $input;

    /**
     * @var EntityContract
     */
    public $entities;

    /**
     * @param $input
     * @param EntityContract $entities
     */
    public function __construct($input, EntityContract $entities)
    {
        $this->input = $input;
        $this->entities = $entities;
    }

    /**
     * @return \App\TTC\Models\Survey\Entity\Logic\Skip[]|\Illuminate\Database\Eloquent\Collection
     */
    public function handle()
    {
        return $this->entities->syncSkiplogic(Arr::get($this->input, 'entity_id'), Arr::get($this->input, 'skip'));
    }
}
