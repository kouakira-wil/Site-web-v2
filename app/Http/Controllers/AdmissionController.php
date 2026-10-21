<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Cycle;
use App\Models\Project;
use Illuminate\Contracts\View\View;

class AdmissionController extends Controller
{
    public function __invoke(): View
    {
        /*
         * Le tableau des programmes se trie dans le navigateur, par cycle puis
         * par pole : toutes les filieres publiees partent donc dans la page,
         * chacune etiquetee de ses cycles et de son pole.
         */
        $filieres = Project::query()
            ->published()
            ->with(['category', 'cycles'])
            ->ordered()
            ->get();

        return view('site.admission', [
            'filieres' => $filieres,
            // Seuls les cycles qui portent au moins une filiere publiee : un
            // onglet vide n'a rien a reveler.
            'cycles' => Cycle::query()
                ->ordered()
                ->get()
                ->filter(fn (Cycle $cycle): bool => $filieres->contains(
                    fn (Project $filiere): bool => $filiere->cycles->contains($cycle)
                ))
                ->values(),
            'poles' => Category::query()
                ->ofType(Category::TYPE_PROJECT)
                ->ordered()
                ->get()
                ->filter(fn (Category $pole): bool => $filieres->contains('category_id', $pole->id))
                ->values(),
        ]);
    }
}
