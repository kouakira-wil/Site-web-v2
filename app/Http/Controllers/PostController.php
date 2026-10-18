<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Post;
use Illuminate\Contracts\View\View;

class PostController extends Controller
{
    public function index(): View
    {
        return view('site.posts.index', [
            /*
             * Toutes les actualités publiées, sans filtre ni pagination : le tri
             * par rubrique se fait dans le navigateur, sur des cartes déjà
             * présentes dans la page. Filtrer aussi côté serveur laisserait le
             * bouton « Toutes » sans rien à révéler.
             */
            'posts' => Post::query()
                ->published()
                ->with('category')
                ->latestFirst()
                ->get(),
            'categories' => Category::query()->ofType(Category::TYPE_POST)->ordered()->get(),
        ]);
    }

    public function show(Post $post): View
    {
        abort_unless($post->isPublished(), 404);

        $post->load(['category', 'tags', 'images']);

        return view('site.posts.show', [
            'post' => $post,
            'categories' => Category::query()->ofType(Category::TYPE_POST)->ordered()->get(),
            'previous' => Post::query()->published()
                ->where('published_at', '<', $post->published_at)
                ->latestFirst()->first(),
            'next' => Post::query()->published()
                ->where('published_at', '>', $post->published_at)
                ->orderBy('published_at')->first(),
            'related' => Post::query()
                ->published()
                ->where('category_id', $post->category_id)
                ->whereKeyNot($post->getKey())
                ->latestFirst()
                ->take(2)
                ->get(),
        ]);
    }
}
