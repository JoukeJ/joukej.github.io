<?php namespace App\TTC\Listeners\Backend;

use App\Events\Asimov\Ui\MainMenuItemsEvent;
use Illuminate\Support\Facades\URL;

class MainMenuItemsListener
{

    /**
     * @param MainMenuItemsEvent $event
     */
    public function handle(MainMenuItemsEvent $event)
    {
        /**
         * Get current URI in parts
         */
        $currentUri = \Route::current()->uri();
        $currentUriParts = explode("/", $currentUri);

        $items = [];

        /**
         * Survey Menu
         */
        $survey_items = [];

        if (\Auth::user()->may('management.survey.seeall')) {
            $survey_items[URL::route('surveys.index')] = trans('survey/menu.index');
        }

        if (\Auth::user()->may('management.survey.seeown')) {
            $survey_items[URL::route('surveys.my_index')] = trans('survey/menu.my_index');
        }

        if (\Auth::user()->may('management.survey.create')) {
            $survey_items[URL::route('surveys.create')] = trans('survey/menu.create');
        }

        $active = false;
        foreach ($survey_items as $url => $text) {
            if (\Request::url() === $url) {
                $active = true;
            }
        }
        if ($active === false && $currentUriParts[0] === 'surveys') {
            $active = true;
        }
        if(sizeof($survey_items) > 0) {
            $items[trans('Surveys')] = [
                'active' => $active,
                'class' => 'md md-folder',
                'children' => $survey_items
            ];
        }

        /**
         * Import export menu
         */
        $csv_items = [];

        if(\Auth::user()->may('management.csv')){
            $csv_items[\URL::route('csv.index')] = trans('csv.importexport');
        }
        $active = false;
        foreach ($csv_items as $url => $text) {
            if (\Request::url() === $url) {
                $active = true;
            }
        }
        if(sizeof($csv_items) > 0) {
            $items[trans('csv.csv')] = [
                'active' => $active,
                'class' => 'md md-work',
                'children' => $csv_items
            ];
        }



        /**
         * Management Menu
         */
        $management_items = [];
        $management_items[URL::route('management.users.index')] = trans('model/user.plural');
        $management_items[URL::route('management.roles.index')] = trans('model/role.plural');
        $management_items[URL::route('management.permissions.index')] = trans('model/permission.plural');

        $active = false;
        foreach ($management_items as $url => $text) {
            if (true === $url) {
                $active = true;
            }
        }
        if ($active === false && $currentUriParts[0] === 'management') {
            $active = true;
        }
        if (\Auth::user()->may('management.users')) {
            $items[trans('Management')] = [
                'active' => $active,
                'class' => 'md md-people',
                'children' => $management_items
            ];
        }

        $event->items->push(view('ttc.backend.menu.item', array('items' => $items)));
    }

}
