<?php
/**
 * Created by Luuk Holleman
 * Date: 24/06/15
 */

namespace App\TTC\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\TTC\Models\Country;

class CountryController extends Controller
{
    public function detail($id)
    {
        return Country::findOrFail($id);
    }
}
