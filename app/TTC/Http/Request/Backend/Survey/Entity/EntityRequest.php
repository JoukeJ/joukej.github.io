<?php
/**
 * Created by PhpStorm.
 * User: Rurik Wind
 * Date: 18-6-2015
 * Time: 14:38
 */

namespace App\TTC\Http\Request\Backend\Survey\Entity;


use App\Http\Requests\Request;
use Illuminate\Contracts\Auth\Guard;

class EntityRequest extends Request
{

    function __construct(Guard $guard)
    {
        parent::__construct($guard);

        \Input::merge(['type_options' => $this->getOptionsFromInput()]);

        \Input::merge(['skip' => $this->getSkipOptionsFromInput()]);
    }

    public function getOptionsFromInput()
    {
        // get new options
        $new_options = \Input::get('type_options_new', []);

        // merge new options with existing ones and give prefix
        $options = array_combine(
            array_map(function ($k) {
                return 'new_' . $k;
            }, array_keys($new_options))
            , $new_options
        );

        $options = \Input::get('type_options', []) + $options;

        return $options;
    }

    public function getSkipOptionsFromInput()
    {
        $new_skip = \Input::get('skip_new', []);

        $skips = array_combine(
            array_map(function ($k) {
                return 'new_' . $k;
            }, array_keys($new_skip))
            , $new_skip
        );

        $skips = \Input::get('skip', []) + $skips;

        return $skips;
    }
}