<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\TeamMember;
use Illuminate\Contracts\View\View;

class AboutController extends Controller
{
    public function __invoke(): View
    {
        return view('site.about', [
            'members' => TeamMember::query()->active()->ordered()->take(4)->get(),
            'recentPosts' => Post::query()->published()->with('category')->latestFirst()->take(6)->get(),
        ]);
    }
}
