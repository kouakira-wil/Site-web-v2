<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Pages;

use App\Models\PageHeader;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Livewire\WithFileUploads;

/**
 * Édition des en-têtes de pages.
 *
 * Les clés étant fixées par le code, il n'y a ni création ni suppression :
 * une page unique suffit, chaque en-tête y est un jeu de champs.
 */
#[Layout('layouts.admin')]
class PageHeaderManager extends Component
{
    use WithFileUploads;

    /** Dossier des visuels. Sert de garde-fou : aucune suppression au-dehors. */
    private const IMAGE_DIR = 'entetes';

    /**
     * Pictogrammes proposés, alignés sur resources/views/components/site/icon.blade.php.
     *
     * @var array<int, string>
     */
    public const ICONES = ['book', 'briefcase', 'users', 'globe', 'target', 'award', 'sparkle', 'compass'];

    /** @var array<int, array<string, mixed>> */
    public array $headers = [];

    /**
     * Nouveaux visuels, indexés sur la position de l'en-tête.
     *
     * @var array<int, TemporaryUploadedFile>
     */
    public array $images = [];

    public function mount(): void
    {
        $this->headers = PageHeader::query()
            ->orderBy('group')
            ->orderBy('position')
            ->orderBy('id')
            ->get()
            ->map(fn (PageHeader $header): array => [
                'id' => $header->id,
                'key' => $header->key,
                'group' => $header->group,
                'label' => (string) $header->label,
                'eyebrow' => (string) $header->eyebrow,
                'title' => (string) $header->title,
                'title_accent' => (string) $header->title_accent,
                'intro' => (string) $header->intro,
                'body' => (string) $header->body,
                'cta_label' => (string) $header->cta_label,
                'cta_url' => (string) $header->cta_url,
                // Une étiquette par ligne à la saisie.
                'tags' => implode(PHP_EOL, $header->tagList()),
                'icon' => (string) $header->icon,
                'image' => $header->image,
                'image_url' => $header->imageUrl(),
            ])
            ->all();
    }

    /**
     * @return array<string, array<int, string>>
     */
    protected function rules(): array
    {
        return [
            'headers' => ['array'],
            'headers.*.label' => ['required', 'string', 'max:60'],
            'headers.*.eyebrow' => ['nullable', 'string', 'max:120'],
            'headers.*.title' => ['required', 'string', 'max:200'],
            'headers.*.title_accent' => ['nullable', 'string', 'max:200'],
            'headers.*.intro' => ['nullable', 'string', 'max:600'],
            'headers.*.body' => ['nullable', 'string', 'max:6000'],
            'headers.*.cta_label' => ['nullable', 'string', 'max:80'],
            'headers.*.cta_url' => ['nullable', 'string', 'max:255'],
            'headers.*.tags' => ['nullable', 'string', 'max:400'],
            // Liste fermée : un nom libre ne rendrait aucun pictogramme.
            'headers.*.icon' => ['nullable', 'string', 'in:'.implode(',', self::ICONES)],
            'images' => ['array'],
            'images.*' => ['image', 'mimes:jpg,jpeg,png,webp,avif', 'max:4096'],
        ];
    }

    /**
     * @return array<string, string>
     */
    protected function validationAttributes(): array
    {
        return [
            'headers.*.label' => 'libellé',
            'headers.*.title' => 'titre',
            'headers.*.title_accent' => 'seconde ligne',
            'headers.*.eyebrow' => 'surtitre',
            'headers.*.intro' => 'accroche',
            'headers.*.body' => 'texte long',
            'headers.*.cta_label' => 'libellé du bouton',
            'headers.*.cta_url' => 'lien du bouton',
            'headers.*.tags' => 'étiquettes',
            'headers.*.icon' => 'pictogramme',
        ];
    }

    /**
     * Retire le visuel d'un en-tête, sans toucher au reste de la saisie.
     */
    public function removeImage(int $index): void
    {
        unset($this->images[$index]);

        $this->headers[$index]['image'] = null;
        $this->headers[$index]['image_url'] = null;
    }

    public function save(): void
    {
        $this->validate();

        foreach ($this->headers as $index => $ligne) {
            $header = PageHeader::query()->find($ligne['id']);

            if ($header === null) {
                continue;
            }

            $ancienVisuel = $header->image;

            // Le chemin transmis par le formulaire n'est jamais pris au mot :
            // il ne sert qu'à savoir si l'éditeur a retiré le visuel. Le chemin
            // conservé vient toujours de la base. Sans cela, un compte rédacteur
            // pourrait viser un fichier arbitraire du disque public et le faire
            // supprimer au tour suivant.
            $visuel = blank($ligne['image']) ? null : $ancienVisuel;

            if (($this->images[$index] ?? null) instanceof TemporaryUploadedFile) {
                $visuel = $this->images[$index]->store(self::IMAGE_DIR, 'public');
            }

            $header->fill([
                'label' => trim($ligne['label']),
                'eyebrow' => trim((string) $ligne['eyebrow']) ?: null,
                'title' => trim($ligne['title']),
                'title_accent' => trim((string) $ligne['title_accent']) ?: null,
                'intro' => trim((string) $ligne['intro']) ?: null,
                'body' => trim((string) $ligne['body']) ?: null,
                'cta_label' => trim((string) $ligne['cta_label']) ?: null,
                'cta_url' => trim((string) $ligne['cta_url']) ?: null,
                'tags' => collect(preg_split('/\R/', (string) $ligne['tags']))
                    ->map(fn (string $tag): string => trim($tag))
                    ->filter()
                    ->values()
                    ->all(),
                'icon' => trim((string) $ligne['icon']) ?: null,
                'image' => $visuel,
            ])->save();

            // Le fichier remplacé ou retiré n'est plus référencé : on le supprime,
            // mais jamais hors du dossier dédié.
            if (filled($ancienVisuel) && $ancienVisuel !== $visuel && str_starts_with($ancienVisuel, self::IMAGE_DIR.'/')) {
                Storage::disk('public')->delete($ancienVisuel);
            }
        }

        $this->reset('images');
        $this->mount();

        session()->flash('status', 'En-têtes enregistrés.');
    }

    public function render(): View
    {
        return view('livewire.admin.pages.page-header-manager', ['icones' => self::ICONES]);
    }
}
