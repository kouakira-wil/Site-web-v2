<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Team;

use App\Models\TeamMember;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Livewire\WithFileUploads;

#[Layout('layouts.admin')]
class TeamForm extends Component
{
    use WithFileUploads;

    /**
     * Social networks offered in the form, keyed by the stored attribute name.
     */
    private const NETWORKS = [
        'linkedin' => 'LinkedIn',
        'instagram' => 'Instagram',
        'dribbble' => 'Dribbble',
        'github' => 'GitHub',
    ];

    public ?TeamMember $member = null;

    public string $name = '';

    public string $role = '';

    public string $bio = '';

    public string $email = '';

    public bool $is_active = true;

    public int $position = 0;

    /** @var array<string, string> */
    public array $socials = [];

    public ?TemporaryUploadedFile $photo = null;

    public function mount(?TeamMember $member = null): void
    {
        $this->socials = array_fill_keys(array_keys(self::NETWORKS), '');

        if ($member?->exists !== true) {
            return;
        }

        $this->member = $member;
        $this->name = $member->name;
        $this->role = $member->role;
        $this->bio = (string) $member->bio;
        $this->email = (string) $member->email;
        $this->is_active = $member->is_active;
        $this->position = $member->position;
        $this->socials = array_merge($this->socials, array_map(
            static fn ($url): string => (string) $url,
            $member->socials ?? [],
        ));
    }

    /**
     * @return array<string, array<int, mixed>|string>
     */
    protected function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:120'],
            'role' => ['required', 'string', 'max:120'],
            'bio' => ['nullable', 'string', 'max:2000'],
            'email' => ['nullable', 'email', 'max:180'],
            'is_active' => ['boolean'],
            'position' => ['integer', 'min:0', 'max:9999'],
            'socials.*' => ['nullable', 'url', 'max:255'],
            'photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,avif', 'max:4096'],
        ];
    }

    /**
     * @return array<string, string>
     */
    protected function validationAttributes(): array
    {
        return [
            'name' => 'nom',
            'role' => 'fonction',
            'bio' => 'biographie',
            'email' => 'adresse e-mail',
            'photo' => 'photo',
        ];
    }

    public function save(): void
    {
        $data = $this->validate();

        $member = $this->member ?? new TeamMember();

        $member->fill([
            'name' => $data['name'],
            'role' => $data['role'],
            'bio' => $data['bio'],
            'email' => $data['email'],
            'is_active' => $data['is_active'],
            'position' => $data['position'],
            'socials' => array_filter($this->socials, static fn (string $url): bool => $url !== ''),
        ]);

        if ($this->photo !== null) {
            if (filled($member->photo)) {
                Storage::disk('public')->delete($member->photo);
            }

            $member->photo = $this->photo->store('equipe', 'public');
        }

        $member->save();

        $this->member = $member->refresh();
        $this->reset('photo');

        session()->flash('status', 'Membre enregistré.');

        $this->redirectRoute('admin.team.edit', $member, navigate: true);
    }

    public function removePhoto(): void
    {
        if ($this->member?->photo === null) {
            return;
        }

        Storage::disk('public')->delete($this->member->photo);
        $this->member->update(['photo' => null]);
        $this->member->refresh();
    }

    public function render(): View
    {
        return view('livewire.admin.team.form', [
            'networks' => self::NETWORKS,
            'pageTitle' => $this->member?->exists ? 'Modifier le membre' : 'Nouveau membre',
        ]);
    }
}
