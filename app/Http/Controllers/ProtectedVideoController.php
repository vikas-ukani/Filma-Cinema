<?php

namespace App\Http\Controllers;

use App\Movie;
use App\Season;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class ProtectedVideoController extends Controller
{
   
    public function video(Request $request)
    {

        $movies = Movie::where('is_protect', 1)->where('password', '!=', null)->get();
        $seasons = Season::where('is_protect', 1)->where('password', '!=', null)->get();
        $result = collect();

        foreach ($movies as $movie) {
            $result->push($movie);
        }
        foreach ($seasons as $season) {
            $result->push($season);
        }

        $currentPage = LengthAwarePaginator::resolveCurrentPage();

        $itemCollection = collect($result);

        // Define how many items we want to be visible in each page
        $perPage = 15;

        // Slice the collection to get the items to display in current page
        $currentPageItems = $itemCollection->slice(($currentPage * $perPage) - $perPage, $perPage)->all();

        // Create our paginator and pass it to the view
        $paginatedItems = new LengthAwarePaginator($currentPageItems, count($itemCollection), $perPage);

        // set url path for generted links
        $paginatedItems->setPath($request->url());

        //return view('protectedPassword',compact('result'));
        return view('protectedPassword', ['pusheditems' => $paginatedItems]);
    }

}
