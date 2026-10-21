<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Project;
use Illuminate\Contracts\View\View;

class HomeController extends Controller
{
    public function __invoke(): View
    {
        return view('site.home', [
            // Quatre : c'est ce que la rangée de l'accueil affiche de front.
            'recentPosts' => Post::query()
                ->published()
                ->with('category')
                ->latestFirst()
                ->take(4)
                ->get(),
            'projectCount' => Project::query()->published()->count(),
        ]);
    }
}
