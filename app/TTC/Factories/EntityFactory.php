<?php namespace App\TTC\Factories;

use App\Exceptions\GeneralException;
use App\TTC\Models\Survey\Entity\BaseEntity;

class EntityFactory
{
    /**
     * @param $type
     * @return BaseEntity
     * @throws GeneralException
     */
    public static function make($type)
    {
        $class = \Config::get('ttc.entity.types.' . $type);

        if ($class === null) {
            throw new GeneralException("Unknown type '{$type}'.");
        }

        return new $class;
    }
}
