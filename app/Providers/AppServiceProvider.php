<?php

namespace App\Providers;

use App\Models\Cycle;
use App\Models\Project;
use App\View\Components\Site\FilieresMenu;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->forgetMenuCacheOnContentChange();
    }

    /**
     * Keeps the header mega-menu in step with the content.
     *
     * The menu is rendered on every page, so its query is cached indefinitely;
     * without this the back office could publish a filière and never see it
     * appear in the navigation.
     */
    private function forgetMenuCacheOnContentChange(): void
    {
        $forget = static fn (Model $model) => Cache::forget(FilieresMenu::CACHE_KEY);

        foreach ([Project::class, Cycle::class] as $class) {
            $class::saved($forget);
            $class::deleted($forget);
        }
    }
}
