<?php

declare(strict_types=1);

namespace App\View\Components\Site;

use App\Models\Cycle;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\Component;

/**
 * Mega-menu listing the published filières, grouped by study cycle.
 *
 * Rendered in the site header on every page, so the query is cached; the cache
 * is cleared whenever a filière or a cycle changes (see AppServiceProvider).
 */
class FilieresMenu extends Component
{
    public const CACHE_KEY = 'site.menu.filieres';

    public function render(): View
    {
        return view('components.site.filieres-menu', [
            'cycles' => $this->cycles(),
        ]);
    }

    /**
     * Cycles that actually have at least one published filière.
     *
     * @return Collection<int, Cycle>
     */
    private function cycles(): Collection
    {
        return Cache::rememberForever(self::CACHE_KEY, function (): Collection {
            return Cycle::query()
                ->ordered()
                ->with([
                    'projects' => fn ($query) => $query->published()->ordered(),
                ])
                ->get()
                ->filter(fn (Cycle $cycle): bool => $cycle->projects->isNotEmpty())
                ->values();
        });
    }
}
