<?php

namespace App\TTC\Models\Profile;


use App\TTC\Models\Profile;
use App\TTC\Models\Survey;

class Event extends \Eloquent
{
    protected $table = 'profile_events';

    public $timestamps = false;

    public function profile()
    {
        return $this->belongsTo(Profile::class);
    }

    public function survey()
    {
        return $this->belongsTo(Survey::class);
    }
}
