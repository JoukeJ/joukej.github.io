<?php namespace App\Http\Requests;

use Illuminate\Contracts\Auth\Guard;
use Illuminate\Foundation\Http\FormRequest;

abstract class Request extends FormRequest
{

    /**
     * @var Guard
     */
    protected $guard;

    function __construct(Guard $guard)
    {
        $this->guard = $guard;
    }
}
