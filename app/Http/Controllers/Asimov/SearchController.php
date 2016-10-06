<?php namespace App\Http\Controllers\Asimov;

use App\Http\Controllers\Asimov;
use App\Http\Requests\Asimov\SearchRequest;
use Illuminate\Support\Collection;

class SearchController extends AsimovController
{

    public function search(SearchRequest $request)
    {
        $results = \Search::query($request->get('q'))->get() + \Search::query($request->get('q'))->get();

        $results = Collection::make($results);

        $colors = new Collection();

        // collect all colors
        $results->map(function ($result) use ($colors) {
            if (!$colors->has($result->presenter->getSearchColor())) {
                $colors->put($result->presenter->getSearchColor(), $result->presenter->getSearchClassName());
            }
        });

        return \View::make('asimov.search.search', [
            'results' => $results,
            'colors' => $colors
        ]);
    }
}
