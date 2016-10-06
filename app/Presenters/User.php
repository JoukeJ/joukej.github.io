<?php namespace App\Presenters;

use App\Presenters\Interfaces\SearchablePresenterInterface;

class User extends \App\Presenters\Presenter implements SearchablePresenterInterface
{

    public function getSearchClassName()
    {
        return trans('model/user.plural');
    }

    public function getSearchTitle()
    {
        return $this->fullname();
    }

    public function getSearchBody()
    {
        return $this->email;
    }

    public function getSearchUrl()
    {
        return \URL::route('management.users.edit', [$this->id]);
    }

    public function getSearchIcon()
    {
        return 'md-keyboard-alt';
    }

    public function fullname()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function getSearchColor()
    {
        return '#FF9800';
    }
}
