<?php

namespace App\Http\Controllers;

use App\Models\Award;
use App\Models\Category;
use App\Models\LandingPageSection;
use App\Models\Winner;

class HomeController extends Controller
{
    public function index()
    {
        $sections = LandingPageSection::where('is_active', true)
            ->orderBy('order')
            ->get()
            ->keyBy('key');
        
        $winners = Winner::active()
            ->byYear(2024)
            ->ordered()
            ->get();
        
        $categories = Category::open()->with('companies')->limit(6)->get();
        
        $awards = Award::where('is_active', true)->get();
        
        $votingEndDate = Category::where('is_active', true)
            ->whereNotNull('voting_ends_at')
            ->orderBy('voting_ends_at', 'desc')
            ->value('voting_ends_at');
        
        return view('welcome', compact('sections', 'winners', 'votingEndDate', 'categories', 'awards'));
    }
}
