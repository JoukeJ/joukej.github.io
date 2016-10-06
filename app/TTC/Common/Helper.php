<?php
namespace App\TTC\Common;


class Helper
{
    /**
     * @return mixed
     */
    public static function getLanguages()
    {
        return config('ttc.survey.languages');
    }

    /**
     * @return mixed
     */
    public static function getStatusses()
    {
        return config('ttc.survey.statusses');
    }

    /**
     * @return string
     */
    public static function getDeviceType()
    {
        $device = "feature";

        $detect = new \Mobile_Detect();
        if (!$detect->isGenericPhone()) {
            $device = "smart";
        }

        return $device;
    }

    /**
     * @param $view
     * @param array $args
     * @return \Illuminate\View\View
     */
    public static function getFrontendDeviceView($view, $args = [])
    {
        $args['device'] = self::getDeviceType();

        return view('ttc.frontend.feature.' . $view, $args);
    }

    public static function parseYoutubeUrl($url)
    {
        if (preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $url, $match)) {
            return $match[1];
        }

        return null;
    }
}
