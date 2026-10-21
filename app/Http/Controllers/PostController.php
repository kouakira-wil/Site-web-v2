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
            /*
             * Toutes les autres actualités, de la plus récente à la plus
             * ancienne. La sélection se limitait à deux articles de la même
             * rubrique : un article seul dans la sienne ne proposait rien.
             */
            'related' => Post::query()
                ->published()
                ->with('category')
                ->whereKeyNot($post->getKey())
                ->latestFirst()
                ->get(),
        ]);
    }
}
