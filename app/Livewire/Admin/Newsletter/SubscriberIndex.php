<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Newsletter;

use App\Models\NewsletterSubscriber;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.admin')]
#[Title('Lettre d’information')]
class SubscriberIndex extends Component
{
    use WithPagination;

    public function delete(int $subscriberId): void
    {
        NewsletterSubscriber::query()->findOrFail($subscriberId)->delete();

        $this->dispatch('notify', message: 'Adresse supprimée.');
    }

    public function render(): View
    {
        return view('livewire.admin.newsletter.index', [
            'subscribers' => NewsletterSubscriber::query()->latest()->paginate(25),
            'total' => NewsletterSubscriber::query()->subscribed()->count(),
        ]);
    }
}
