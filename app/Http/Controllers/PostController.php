<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Post;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index(Request $request): View
    {
        $activeSlug = $request->string('categorie')->toString();

        $posts = Post::query()
            ->published()
            ->with('category')
            ->when($activeSlug !== '', fn (Builder $query) => $query->whereHas(
                'category',
                fn (Builder $c) => $c->where('slug', $activeSlug)
            ))
            ->latestFirst()
            ->paginate(8)
            ->withQueryString();

        return view('site.posts.index', [
            'posts' => $posts,
            'categories' => Category::query()->ofType(Category::TYPE_POST)->ordered()->get(),
            'activeSlug' => $activeSlug,
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
