<?php

namespace App\Http\Controllers;

use App\Models\Club;
use Illuminate\View\View;

class ClubController extends Controller
{
    public function index(): View
    {
        $clubs = Club::query()
            ->with('category')
            ->orderBy('name')
            ->get();

        return view('clubs.index', compact('clubs'));
    }
}
