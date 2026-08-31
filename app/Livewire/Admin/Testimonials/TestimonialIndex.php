<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Testimonials;

use App\Models\Testimonial;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.admin')]
#[Title('Témoignages')]
class TestimonialIndex extends Component
{
    public function toggleActive(int $testimonialId): void
    {
        $testimonial = Testimonial::query()->findOrFail($testimonialId);
        $testimonial->update(['is_active' => ! $testimonial->is_active]);
    }

    public function delete(int $testimonialId): void
    {
        $testimonial = Testimonial::query()->findOrFail($testimonialId);

        // La photo n'a plus de porteur : on la retire du disque avec la ligne.
        if (filled($testimonial->photo)) {
            Storage::disk('public')->delete($testimonial->photo);
        }

        $testimonial->delete();

        $this->dispatch('notify', message: 'Témoignage supprimé.');
    }

    public function render(): View
    {
        return view('livewire.admin.testimonials.index', [
            'testimonials' => Testimonial::query()->ordered()->get(),
        ]);
    }
}
