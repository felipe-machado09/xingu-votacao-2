<?php

namespace App\Http\Controllers;

use App\Models\Award;
use App\Models\Category;
use App\Models\LandingPageSection;
use App\Models\Sponsor;

class HomeController extends Controller
{
    public function index()
    {
        $sections = LandingPageSection::where('is_active', true)
            ->orderBy('order')
            ->get()
            ->keyBy('key');
        $year = (string) LandingPageSection::campaignYear();
        foreach ($sections as $section) {
            if ($section->title !== null) {
                $section->title = str_replace(['2025', '{ano}'], $year, $section->title);
            }
            if ($section->content !== null) {
                $section->content = str_replace(['2025', '{ano}'], $year, $section->content);
            }
        }

        $categories = Category::open()->with('companies')->limit(6)->get();

        $awards = Award::currentYear()->where('is_active', true)->get();
        $awards = $awards->filter(fn (Award $award) => $award->hasRemainingQuantity() && $award->requiredVotes() > 0);
        $minimumPrizeVotes = $awards->min(fn (Award $award) => $award->requiredVotes());

        $sponsors = Sponsor::where('is_active', true)
            ->orderBy('order')
            ->get();

        $votingEndDate = LandingPageSection::votingEndsAt() ?? Category::where('is_active', true)
            ->whereNotNull('voting_ends_at')
            ->orderBy('voting_ends_at', 'desc')
            ->value('voting_ends_at');
        $votingEndDate = $votingEndDate ? \Illuminate\Support\Carbon::parse($votingEndDate)->toIso8601String() : null;

        return view('welcome', compact('sections', 'votingEndDate', 'categories', 'awards', 'sponsors', 'minimumPrizeVotes'));
    }
}
