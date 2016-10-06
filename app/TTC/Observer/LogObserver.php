<?php
/**
 * Created by Luuk Holleman
 * Date: 17/06/15
 */

namespace App\TTC\Observer;

use App\TTC\Models\Survey\Log;

class LogObserver
{
    /**
     * @param \Eloquent $model
     */
    public function creating(\Eloquent $model)
    {
        $this->log($model, 'creating');
    }

    /**
     * @param \Eloquent $model
     */
    public function updating(\Eloquent $model)
    {
        $this->log($model, 'updating');
    }

    /**
     * @param \Eloquent $model
     */
    public function deleting(\Eloquent $model)
    {
        $this->log($model, 'deleting');
    }

    /**
     * @param $model
     * @param $action
     */
    private function log($model, $action)
    {
        $original = [];
        $updated = [];

        foreach (array_keys($model->getAttributes()) as $attribute) {
            $original[$attribute] = $model->getOriginal($attribute);

            if ($original[$attribute] != $model->getAttribute($attribute)) {
                $updated[$attribute] = $model->getAttribute($attribute);
            }
        }

        $user_id = null;

        if (\Auth::check()) {
            $user_id = \Auth::user()->id;
        }

        Log::create([
            'user_id' => $user_id,
            'model' => get_class($model),
            'original' => json_encode($original),
            'changed' => json_encode($updated),
            'action' => $action
        ]);
    }
}
