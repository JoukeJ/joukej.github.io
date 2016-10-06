<?php

use App\TTC\Models\Survey\Entity\Info\Image;
use App\TTC\Models\Survey\Entity\Info\Video;
use App\TTC\Models\Survey\Entity\Logic\Skip;
use App\TTC\Models\Survey\Entity\Question\Checkbox;
use App\TTC\Models\Survey\Entity\Question\Open;
use App\TTC\Models\Survey\Entity\Question\Radio;
use App\TTC\Models\Survey\Entity\Question\Text;

return [
    'types' => [
        // Question types
        'q_text' => Text::class,
        'q_open' => Open::class,
        'q_checkbox' => Checkbox::class,
        'q_radio' => Radio::class,
        'q_image' => \App\TTC\Models\Survey\Entity\Question\Image::class,
        // Info types
        'i_image' => Image::class,
        'i_video' => Video::class,
        // Logic types
        'l_skip' => Skip::class
    ]
];
