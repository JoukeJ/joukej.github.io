<?php namespace App\TTC\Chain\Response\EndOfChain;

use App\TTC\Chain\Response\EndOfChainResponse;
use App\TTC\Events\Frontend\Entity\EntityRedirectedEvent;

class RedirectResponse extends EndOfChainResponse
{
    /**
     * @var \Illuminate\Http\RedirectResponse
     */
    protected $redirect;

    /**
     * @var int
     */
    protected $toEntityId;

    /**
     * @return int
     */
    public function getToEntityId()
    {
        return $this->toEntityId;
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function getRedirect()
    {
        return $this->redirect;
    }

    /**
     * RedirectResponse constructor.
     * @param \Illuminate\Http\RedirectResponse $redirect
     */
    public function __construct(\Illuminate\Http\RedirectResponse $redirect, $toEntityId = null)
    {
        $this->redirect = $redirect;
        $this->toEntityId = $toEntityId;
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function getObject()
    {
        return $this->redirect;
    }

    /**
     * Fire the redirected event
     */
    public function fireEvents()
    {
        parent::fireEvents();

        \Event::fire(new EntityRedirectedEvent($this));
    }


}
