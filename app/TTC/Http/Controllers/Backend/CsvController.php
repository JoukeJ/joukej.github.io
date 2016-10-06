<?php

namespace App\TTC\Http\Controllers\Backend;


use App\Http\Controllers\Controller;
use App\TTC\Jobs\Backend\Csv\ImportCsv;
use App\TTC\Models\Profile;
use Ddeboer\DataImport\Writer\CsvWriter;

class CsvController extends Controller
{
    public function index()
    {
        return \View::make('ttc.backend.csv.index');
    }

    public function import()
    {
//        try {
            $dir = sys_get_temp_dir();
            $file = uniqid();

            \Input::file('csv')->move($dir, $file);

            $imported = $this->dispatch(app(ImportCsv::class, [$dir . '/' . $file]));

            \Notification::success(sprintf("Imported %s profiles (%s were duplicates)", $imported['imported'], $imported['duplicates']));

            return \Redirect::back();
//        } catch (\Exception $e) {
//            \App::abort(500, $e->getMessage());
//        }
    }

    public function export()
    {
        $profiles = Profile::all();

        $writer = new CsvWriter(',');
        $writer->setStream(fopen('php://temp', 'w'));
        $writer->setCloseStreamOnFinish(false);

        $writer->writeItem(['Phone', 'Url', 'Name', 'Gender', 'Birthday', 'Country', 'City']);

        foreach ($profiles as $profile) {
            $writer->writeItem([
                $profile->phonenumber,
                sprintf(env('SHORT_URL'), $profile->identifier),
                $profile->name,
                $profile->gender,
                $profile->birthday,
                $profile->country->name,
                $profile->geo_city,
            ]);
        }

        $stream = $writer->getStream();
        rewind($stream);

        $callback = function () use ($stream) {
            echo stream_get_contents($stream);
        };

        return \Response::stream($callback, 200, [
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Content-type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename=all_profiles.csv',
            'Expires' => '0',
            'Pragma' => 'public',
        ]);
    }
}
