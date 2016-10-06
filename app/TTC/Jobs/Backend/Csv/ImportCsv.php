<?php

namespace App\TTC\Jobs\Backend\Csv;


use App\Exceptions\GeneralException;
use App\Jobs\Job;
use App\TTC\Models\Country;
use App\TTC\Repositories\Frontend\ProfileContract;
use Ddeboer\DataImport\Reader\CsvReader;
use Illuminate\Contracts\Bus\SelfHandling;

class ImportCsv extends Job implements SelfHandling
{
    private $filePath;

    private $profileRepository;

    /**
     * ImportCsv constructor.
     * @param $filePath
     * @param ProfileContract $profileRepository
     */
    public function __construct($filePath, ProfileContract $profileRepository)
    {
        $this->filePath = $filePath;

        $this->profileRepository = $profileRepository;
    }

    /**
     *
     */
    public function handle()
    {
        $file = new \SplFileObject($this->filePath);

        $reader = new CsvReader($file, ';');

        // skip first row
        $reader->setHeaderRowNumber(0);

        $imported = 0;
        $duplicates = 0;

        try {
            foreach ($reader as $n => $row) {
                try {
                    $keys = array_keys($row);
                    foreach ($keys as $key) {
                        $row[strtolower($key)] = $row[$key];
                    }

                    $this->profileRepository->findByPhonenumber($row['phone']);
                    $duplicates++;
                    continue;
                } catch (GeneralException $e) {
                    $countryName = array_get($row, 'country');
                    $country = Country::where('name', $countryName)->orWhere('iso', $countryName)->first();

                    if ($country === null) {
                        throw new \Exception(sprintf("Unknown country '%s'", $countryName));
                    }

                    $data = [
                        'identifier' => str_random(8),
                        'phonenumber' => array_get($row, 'phone'),
                        'name' => array_get($row, 'name'),
                        'geo_country_id' => $country->id,
                    ];

                    $validator = $this->validate($data);
                    if ($validator->passes()) {
                        if ($this->profileRepository->create($data)) {
                            $imported++;
                        }
                    } else {
                        $msg = $validator->errors()->first();
                        throw new \Exception(sprintf("Could not validate csv data on line number %s (%s)", $n, $msg));
                    }
                }
            }
        } catch (\Exception $e) {
            throw new \Exception(sprintf("Something went wrong during import, please check the file format and try again (Error: %s)",
                $e->getMessage(), 0, $e));
        }

        return ['imported' => $imported, 'duplicates' => $duplicates];
    }

    /**
     * @param $data
     * @return \Illuminate\Validation\Validator
     */
    private function validate($data)
    {
        return \Validator::make($data, [
            'phonenumber' => 'required',
            'identifier' => 'required',
            'geo_country_id' => 'required|exists:countries,id',
        ]);
    }
}
