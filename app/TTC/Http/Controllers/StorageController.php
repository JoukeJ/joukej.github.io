<?php

namespace App\TTC\Http\Controllers;

use App\TTC\Models\Survey\Entity\Info\Image;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class StorageController extends Controller
{

    /**
     * @param $id
     * @return mixed
     */
    public function image($identifier)
    {
        $image = Image::where('identifier', '=', $identifier)->firstOrFail();

        $file = $this->getStorageDirectory() . $image->path;

        return response(file_get_contents($file))->header('Content-Type', mime_content_type($file));
    }

    /**
     * @return string
     */
    protected function getStorageDirectory()
    {
        return $dir = app_path() . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'storage';
    }

}
