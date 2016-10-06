<?php namespace App\Presenters\Interfaces;

interface SearchablePresenterInterface
{
    public function getSearchTitle();

    public function getSearchBody();

    public function getSearchUrl();

    public function getSearchIcon();

    public function getSearchColor();
}
