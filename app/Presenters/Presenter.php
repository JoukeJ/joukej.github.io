<?php namespace App\Presenters;

class Presenter
{
    private $_model;

    public function __construct($model)
    {
        $this->_model = $model;
    }

    public function __get($key)
    {
        return $this->_model->$key;
    }

    public function __call($name, $arguments)
    {
        call_user_func([$this->_model, $name], $arguments);
    }

    public function __isset($name)
    {
        return isset($this->_model->$name);
    }
}
