<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Testimonials;

use App\Models\Testimonial;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Livewire\WithFileUploads;

#[Layout('layouts.admin')]
class TestimonialForm extends Component
{
    use WithFileUploads;

    public ?Testimonial $testimonial = null;

    public string $author = '';

    public string $quote = '';

    public string $program = '';

    public ?string $graduation_year = null;

    public string $location = '';

    public bool $is_active = true;

    public int $position = 0;

    public ?TemporaryUploadedFile $photo = null;

    public function mount(?Testimonial $testimonial = null): void
    {
        if ($testimonial?->exists !== true) {
            return;
        }

        $this->testimonial = $testimonial;
        $this->author = (string) $testimonial->author;
        $this->quote = (string) $testimonial->quote;
        $this->program = (string) $testimonial->program;
        $this->graduation_year = $testimonial->graduation_year ? (string) $testimonial->graduation_year : null;
        $this->location = (string) $testimonial->location;
        $this->is_active = $testimonial->is_active;
        $this->position = $testimonial->position;
    }

    /**
     * @return array<string, array<int, mixed>>
     */
    protected function rules(): array
    {
        return [
            'author' => ['nullable', 'string', 'max:120'],
            'quote' => ['required', 'string', 'max:600'],
            'program' => ['nullable', 'string', 'max:150'],
            // Borne haute volontairement large : une promotion peut être
            // annoncée avant la fin de l'année en cours.
            'graduation_year' => ['nullable', 'integer', 'min:1990', 'max:'.(now()->year + 1)],
            'location' => ['nullable', 'string', 'max:150'],
            'is_active' => ['boolean'],
            'position' => ['integer', 'min:0', 'max:9999'],
            'photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,avif', 'max:4096'],
        ];
    }

    /**
     * @return array<string, string>
     */
    protected function validationAttributes(): array
    {
        return [
            'author' => 'nom',
            'quote' => 'témoignage',
            'program' => 'formation suivie',
            'graduation_year' => 'année d’obtention',
            'location' => 'ville',
            'photo' => 'photo',
        ];
    }

    public function save(): void
    {
        $data = $this->validate();

        $testimonial = $this->testimonial ?? new Testimonial();

        $testimonial->fill([
            'author' => filled($data['author']) ? trim($data['author']) : null,
            'quote' => trim($data['quote']),
            'program' => filled($data['program']) ? trim($data['program']) : null,
            'graduation_year' => $data['graduation_year'],
            'location' => filled($data['location']) ? trim($data['location']) : null,
            'is_active' => $data['is_active'],
            'position' => $data['position'],
        ]);

        if ($this->photo !== null) {
            if (filled($testimonial->photo)) {
                Storage::disk('public')->delete($testimonial->photo);
            }

            $testimonial->photo = $this->photo->store('temoignages', 'public');
        }

        $testimonial->save();

        $this->testimonial = $testimonial->refresh();
        $this->reset('photo');

        session()->flash('status', 'Témoignage enregistré.');

        $this->redirectRoute('admin.testimonials.edit', $testimonial, navigate: true);
    }

    public function removePhoto(): void
    {
        if ($this->testimonial?->photo === null) {
            return;
        }

        Storage::disk('public')->delete($this->testimonial->photo);
        $this->testimonial->update(['photo' => null]);
        $this->testimonial->refresh();
    }

    public function render(): View
    {
        return view('livewire.admin.testimonials.form', [
            'pageTitle' => $this->testimonial?->exists ? 'Modifier le témoignage' : 'Nouveau témoignage',
        ]);
    }
}
