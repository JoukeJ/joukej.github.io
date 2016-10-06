<?php namespace App\TTC\Chain\Response\EndOfChain;

use App\TTC\Chain\Response\EndOfChainResponse;
use Illuminate\View\View;

class RenderResponse extends EndOfChainResponse
{

    /**
     * @var View
     */
    protected $view;

    /**
     * RenderResponse constructor.
     * @param View $view
     */
    public function __construct(View $view)
    {
        $this->view = $view;
    }

    /**
     * @return View
     */
    public function getObject()
    {
        return $this->view;
    }


}
