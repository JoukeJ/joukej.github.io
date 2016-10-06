<?php namespace App\Http\Controllers\Asimov;

use App\Http\Controllers\Controller;

class HomeController extends Controller
{

    public function index()
    {
        return \View::make('frontend.home.index');
    }

}
