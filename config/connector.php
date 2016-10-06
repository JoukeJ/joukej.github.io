<?php /* created by Rob van Bentem, 13/10/2015 */

use Monolog\Logger;

return [
    'logging' => [
        'context' => 'connector',
        'logviewer' => [
            'min_level' => Logger::DEBUG
        ],
        'handlers' => [
            'file' => [
                'enabled' => true,
                'level' => Logger::DEBUG,
            ],
            'db' => [
                'enabled' => true,
                'level' => Logger::INFO,
            ],
            'email' => [
                'enabled' => true,
                'level' => Logger::ERROR,
                'from' => null, // defaults to error@HOSTNAME
                'subject' => 'Error in %s application',
                'to' => [],
            ],
            'hipchat' => [
                'enabled' => false,
                'level' => Logger::ERROR,
                'token' => '',
                'room' => '',
                'name' => '',
                'notify' => true // blink to draw attention to the user
            ],
        ]
    ],
    'icons' => [
        'job' => 'glyphicon glyphicon-record',
        'stage' => 'glyphicon glyphicon-unchecked',
        'log' => 'glyphicon glyphicon-console'
    ]
];
