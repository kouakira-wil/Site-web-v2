<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Cycle;
use App\Models\Project;
use Illuminate\Contracts\View\View;

class ProjectController extends Controller
{
    public function index(): View
    {
        /*
         * Toutes les filières publiées : le tri par pôle se fait dans le
         * navigateur, sur des fiches déjà présentes. Filtrer ou paginer ici
         * priverait le bouton « Toutes » de ce qu'il doit révéler.
         */
        $projects = Project::query()
            ->published()
            ->with('category')
            ->ordered()
            ->get();

        return view('site.filieres.index', [
            'projects' => $projects,
            // Filières regroupées par cycle, pour le bloc « tracks ».
            'cycles' => Cycle::query()
                ->ordered()
                ->with(['projects' => fn ($query) => $query->published()->with('category')->ordered()])
                ->get(),
            'categories' => Category::query()->ofType(Category::TYPE_PROJECT)->ordered()->get(),
        ]);
    }

    public function show(Project $project): View
    {
        abort_unless($project->isPublished(), 404);

        $project->load(['category', 'images', 'cycles']);

        return view('site.filieres.show', [
            'project' => $project,
            // Nombre de filières publiées dans le même pôle, pour la bande de chiffres.
            'poleCount' => $project->category_id === null ? 0 : Project::query()
                ->published()
                ->where('category_id', $project->category_id)
                ->count(),
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
