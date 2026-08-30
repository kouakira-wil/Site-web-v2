<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Cycle;
use App\Models\Project;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function index(Request $request): View
    {
        $activeSlug = $request->string('categorie')->toString();

        $projects = Project::query()
            ->published()
            ->with('category')
            ->when($activeSlug !== '', fn (Builder $query) => $query->whereHas(
                'category',
                fn (Builder $c) => $c->where('slug', $activeSlug)
            ))
            ->ordered()
            ->paginate(9)
            ->withQueryString();

        return view('site.projects.index', [
            'projects' => $projects,
            // Filières regroupées par cycle, pour le bloc « tracks ».
            'cycles' => Cycle::query()
                ->ordered()
                ->with(['projects' => fn ($query) => $query->published()->with('category')->ordered()])
                ->get(),
            'categories' => Category::query()->ofType(Category::TYPE_PROJECT)->ordered()->get(),
            'activeSlug' => $activeSlug,
        ]);
    }

    public function show(Project $project): View
    {
        abort_unless($project->isPublished(), 404);

        $project->load(['category', 'images']);

        return view('site.projects.show', [
            'project' => $project,
            'related' => Project::query()
                ->published()
                ->where('category_id', $project->category_id)
                ->whereKeyNot($project->getKey())
                ->ordered()
                ->take(2)
                ->get(),
        ]);
    }
}
