<?php

namespace App\Http\Controllers;

use App\Models\PageRank;
use Illuminate\Http\Request;

class PageRankController extends Controller
{
    public function index()
    {
        return view('index');
    }

    public function getList(Request $request)
    {
        $pageRankQuery = PageRank::query()->orderBy('root_domain', 'asc');

        if ($request->search_text && strlen($request->search_text) > 2) {
            $pageRankQuery->where('root_domain', 'like', '%' . $request->search_text . '%');
        }

        return $pageRankQuery->paginate(100);
    }
}
