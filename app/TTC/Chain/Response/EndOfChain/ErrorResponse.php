<?php namespace App\TTC\Chain\Response\EndOfChain;

use App\TTC\Chain\Response\EndOfChainResponse;

class ErrorResponse extends EndOfChainResponse
{
    /**
     * @var \Illuminate\Http\RedirectResponse
     */
    protected $redirect;


    /**
     * @param array|string $error
     */
    public function __construct($error)
    {
        if (!is_array($error)) {
            $error = [
                'error' => $error
            ];
        }

        $this->redirect = \Redirect::back()->withErrors($error);
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function getObject()
    {
        return $this->redirect;
    }

}
