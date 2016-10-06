<?php

namespace app\TTC\Events\Backend\Profile;


use App\TTC\Models\Profile\Identifier;

/**
 * Class SMSResponded
 * @package app\TTC\Events\Backend\Profile
 */
class SMSResponded
{
    /**
     * @var Identifier
     */
    public $profileIdentifier;

    /**
     * SMSResponded constructor.
     * @param $profileIdentifier
     */
    public function __construct(Identifier $profileIdentifier)
    {
        $this->profileIdentifier = $profileIdentifier;
    }
}
