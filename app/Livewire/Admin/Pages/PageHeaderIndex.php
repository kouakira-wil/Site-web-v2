<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Pages;

use App\Models\PageHeader;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

/**
 * Sommaire des textes du site.
 *
 * Les blocs sont trop nombreux pour tenir dans un seul formulaire : on les
 * liste par famille, et chacun s'édite sur sa propre page.
 */
#[Layout('layouts.admin')]
#[Title('Textes du site')]
class PageHeaderIndex extends Component
{
    /**
     * Intitulés des familles. Une clé inconnue retombe sur son propre nom,
     * pour qu'un futur groupe apparaisse au lieu d'être avalé.
     *
     * @var array<string, string>
     */
    public const FAMILLES = [
        'pages' => 'Bandeaux des pages',
        'accueil' => 'Sections de la page d’accueil',
        'ecole' => 'Page L’école',
        'admission' => 'Page Admission',
    ];

    public function render(): View
    {
        return view('livewire.admin.pages.index', [
            'familles' => PageHeader::query()
                ->orderBy('group')
                ->orderBy('position')
                ->orderBy('id')
                ->get()
                ->groupBy('group'),
            'intitules' => self::FAMILLES,
        ]);
    }
}
