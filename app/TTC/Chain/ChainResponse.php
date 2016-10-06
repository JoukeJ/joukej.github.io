<?php namespace App\TTC\Chain;

abstract class ChainResponse
{
    /**
     * @var ChainItem|null
     */
    protected $chainItem;

    /**
     * @return ChainItem|null
     */
    public function getChainItem()
    {
        return $this->chainItem;
    }

    /**
     * @param ChainItem|null $chainItem
     */
    public function setChainItem($chainItem)
    {
        $this->chainItem = $chainItem;
    }

    public function fireEvents()
    {
        // no events to be fired here
    }

}
