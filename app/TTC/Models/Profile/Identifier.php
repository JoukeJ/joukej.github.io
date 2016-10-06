<?php

namespace App\TTC\Models\Profile;

use App\TTC\Models\Profile;

class Identifier extends \Eloquent
{
    protected $table = 'profile_identifiers';

    protected $fillable = [
        'profile_id',
        'survey_id',
        'identifier'
    ];

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
