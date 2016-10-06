<?php

namespace App\TTC\Connector\Handler;


use App\TTC\Connector\Stage\SendEventStage;
use Unifact\Connector\Handler\Handlers\JobHandler;

class RegisterProfileEvent extends JobHandler
{
    public static function make() {
        return app(self::class, ['register-profile-event', [
                app(SendEventStage::class)
        ]]);
    }
}
