<?php namespace App\TTC\Chain\Response;

use App\TTC\Chain\ChainResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

abstract class EndOfChainResponse extends ChainResponse
{

    /**
     * @return View|RedirectResponse
     */
    abstract public function getObject();

}
