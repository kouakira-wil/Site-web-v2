<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Project;
use App\Models\TeamMember;
use Illuminate\Contracts\View\View;

class HomeController extends Controller
{
    public function __invoke(): View
    {
        return view('site.home', [
            'featured' => Project::query()
                ->published()
                ->featured()
                ->with('category')
                ->ordered()
                ->take(4)
                ->get(),
            'recentPosts' => Post::query()
                ->published()
                ->with('category')
                ->latestFirst()
                ->take(3)
                ->get(),
            'team' => TeamMember::query()->active()->ordered()->take(4)->get(),
            'projectCount' => Project::query()->published()->count(),
        ]);
    }
}
