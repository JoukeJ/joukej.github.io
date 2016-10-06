<?php namespace App\TTC\Events\Frontend\Entity;

use App\Events\Event;
use App\TTC\Chain\Response\EndOfChain\RedirectResponse;

class EntityRedirectedEvent extends Event
{
    /**
     * @var RedirectResponse
     */
    public $redirectResponse;

    /**
     * EntityRedirectedEvent constructor.
     * @param RedirectResponse $redirectResponse
     */
    public function __construct(RedirectResponse $redirectResponse)
    {
        $this->redirectResponse = $redirectResponse;
    }


}
