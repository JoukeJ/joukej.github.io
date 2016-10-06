<?php


namespace App\TTC\Chain;


interface HandlesChain
{
    /**
     * @param ChainPayload $payload
     * @return ChainResponse
     */
    public function handleChain(ChainPayload $payload);
}
