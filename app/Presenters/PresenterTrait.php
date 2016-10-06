<?php namespace App\Presenters;

trait PresenterTrait
{

    public function getPresenter()
    {
        $model = get_class($this);

        $presenter = str_replace('Models', 'Presenters', $model);

        if (class_exists($presenter)) {
            return new $presenter($this);
        }

        return new Presenter($this);
    }

    public function getPresenterAttribute()
    {
        return $this->getPresenter();
    }
}
