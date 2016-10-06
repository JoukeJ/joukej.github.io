<?php

namespace App\Events\Asimov\Ui;

use App\Events\Event;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;

class MainMenuItemsEvent extends Event
{
    use SerializesModels;

    /**
     * @var Collection
     */
    public $items;

    /**
     * @param Collection $items
     */
    public function __construct(Collection $items)
    {
        $this->items = $items;
    }

    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function broadcastOn()
    {
        return [];
    }
}
