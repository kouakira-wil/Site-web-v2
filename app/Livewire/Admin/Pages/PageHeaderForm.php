<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Pages;

use App\Models\PageHeader;
use App\Support\PageHeaderSchema;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Livewire\WithFileUploads;

/**
 * Édition d'un bloc de texte.
 *
 * La clé technique et la famille sont fixées par le code : le formulaire ne
 * modifie que le contenu, et il n'y a ni création ni suppression.
 */
#[Layout('layouts.admin')]
class PageHeaderForm extends Component
{
    use WithFileUploads;

    /** Dossier des visuels. Sert de garde-fou : aucune suppression au-dehors. */
    private const IMAGE_DIR = 'entetes';

    /**
     * Pictogrammes proposés, alignés sur resources/views/components/site/icon.blade.php.
     *
     * @var array<int, string>
     */
    public const ICONES = [
        'book', 'briefcase', 'users', 'globe', 'target', 'award', 'sparkle', 'compass',
        'user-check', 'document', 'send', 'check-circle', 'card', 'help',
    ];

    public PageHeader $header;

    public string $label = '';

    public string $eyebrow = '';

    public string $title = '';

    public string $title_accent = '';

    public string $title_variants = '';

    public string $intro = '';

    public string $body = '';

    public string $cta_label = '';

    public string $cta_url = '';

    public string $tags = '';

    public string $icon = '';

    public ?TemporaryUploadedFile $image = null;

    public ?TemporaryUploadedFile $image_secondary = null;

    /**
     * Retraits demandés, par nom de champ fichier.
     *
     * @var array<string, bool>
     */
    public array $retraits = ['image' => false, 'image_secondary' => false];

    public function mount(PageHeader $header): void
    {
        $this->header = $header;

        $this->label = (string) $header->label;
        $this->eyebrow = (string) $header->eyebrow;
        $this->title = (string) $header->title;
        $this->title_accent = (string) $header->title_accent;
        // Une entrée par ligne à la saisie.
        $this->title_variants = implode(PHP_EOL, $header->title_variants ?? []);
        $this->intro = (string) $header->intro;
        $this->body = (string) $header->body;
        $this->cta_label = (string) $header->cta_label;
        $this->cta_url = (string) $header->cta_url;
        $this->tags = implode(PHP_EOL, $header->tagList());
        $this->icon = (string) $header->icon;
    }

    /**
     * Règles de tous les champs possibles ; seules celles du schéma du bloc
     * sont retenues. Un champ absent du formulaire n'est ni validé ni écrit.
     *
     * @return array<string, array<int, string>>
     */
    private function reglesCompletes(): array
    {
        return [
            'label' => ['required', 'string', 'max:60'],
            'eyebrow' => ['nullable', 'string', 'max:120'],
            'title' => ['required', 'string', 'max:200'],
            'title_accent' => ['nullable', 'string', 'max:200'],
            'title_variants' => ['nullable', 'string', 'max:800'],
            'intro' => ['nullable', 'string', 'max:600'],
            'body' => ['nullable', 'string', 'max:6000'],
            'cta_label' => ['nullable', 'string', 'max:80'],
            'cta_url' => ['nullable', 'string', 'max:255'],
            'tags' => ['nullable', 'string', 'max:400'],
            // Liste fermée : un nom libre ne rendrait aucun pictogramme.
            'icon' => ['nullable', 'string', 'in:'.implode(',', self::ICONES)],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,avif', 'max:4096'],
            'image_secondary' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,avif', 'max:4096'],
        ];
    }

    /**
     * @return array<string, array<int, string>>
     */
    protected function rules(): array
    {
        return array_intersect_key(
            $this->reglesCompletes(),
            array_flip(PageHeaderSchema::fieldsFor($this->header)),
        );
    }

    /**
     * @return array<string, string>
     */
    protected function validationAttributes(): array
    {
        return [
            'label' => 'libellé',
            'eyebrow' => 'surtitre',
            'title' => 'titre',
            'title_accent' => 'seconde ligne',
            'title_variants' => 'variantes du titre',
            'intro' => 'accroche',
            'body' => 'texte long',
            'cta_label' => 'libellé du bouton',
            'cta_url' => 'lien du bouton',
            'tags' => 'étiquettes',
            'icon' => 'pictogramme',
            'image' => 'visuel',
            'image_secondary' => 'seconde photo',
        ];
    }

    /**
     * Retire le visuel d'un champ fichier donné.
     */
    public function removeImage(string $champ = 'image'): void
    {
        if (! array_key_exists($champ, $this->retraits)) {
            return;
        }

        $this->reset($champ);
        $this->retraits[$champ] = true;
    }

    /**
     * Découpe une saisie « une entrée par ligne » en tableau.
     *
     * @return array<int, string>
     */
    private function lignes(string $saisie): array
    {
        return collect(preg_split('/\R/', $saisie))
            ->map(fn (string $ligne): string => trim($ligne))
            ->filter()
            ->values()
            ->all();
    }

    public function save(): void
    {
        $data = $this->validate();
        $champs = PageHeaderSchema::fieldsFor($this->header);

        // Les champs fichier sont traités ensemble : chaque emplacement garde
        // son chemin d'origine, sauf retrait explicite ou nouveau téléversement.
        // Le chemin conservé vient toujours de la base — rien de ce que le
        // formulaire transmet ne peut désigner un fichier arbitraire du disque.
        $anciens = [];
        $nouveaux = [];

        foreach (array_keys($this->retraits) as $champFichier) {
            if (! in_array($champFichier, $champs, true)) {
                continue;
            }

            $anciens[$champFichier] = $this->header->{$champFichier};
            $nouveaux[$champFichier] = $this->retraits[$champFichier] ? null : $anciens[$champFichier];

            if ($this->{$champFichier} !== null) {
                $nouveaux[$champFichier] = $this->{$champFichier}->store(self::IMAGE_DIR, 'public');
            }
        }

        // Seuls les champs du schéma sont réécrits : un bloc qui n'expose pas
        // `body` ne doit pas voir son texte effacé au premier enregistrement.
        $valeurs = [
            'label' => fn (): string => trim((string) $data['label']),
            'eyebrow' => fn (): ?string => trim((string) $data['eyebrow']) ?: null,
            'title' => fn (): string => trim((string) $data['title']),
            'title_accent' => fn (): ?string => trim((string) $data['title_accent']) ?: null,
            'title_variants' => fn (): array => $this->lignes((string) $data['title_variants']),
            'intro' => fn (): ?string => trim((string) $data['intro']) ?: null,
            'body' => fn (): ?string => trim((string) $data['body']) ?: null,
            'cta_label' => fn (): ?string => trim((string) $data['cta_label']) ?: null,
            'cta_url' => fn (): ?string => trim((string) $data['cta_url']) ?: null,
            'tags' => fn (): array => $this->lignes((string) $data['tags']),
            'icon' => fn (): ?string => trim((string) $data['icon']) ?: null,
        ];

        $aEcrire = [];

        foreach ($champs as $champ) {
            if (isset($valeurs[$champ])) {
                $aEcrire[$champ] = $valeurs[$champ]();
            }
        }

        $aEcrire = array_merge($aEcrire, $nouveaux);

        $this->header->fill($aEcrire)->save();

        // Un fichier remplacé ou retiré n'est plus référencé : on le supprime,
        // mais jamais hors du dossier dédié.
        foreach ($anciens as $champFichier => $ancien) {
            if (filled($ancien) && $ancien !== $nouveaux[$champFichier] && str_starts_with($ancien, self::IMAGE_DIR.'/')) {
                Storage::disk('public')->delete($ancien);
            }
        }

        $this->reset('image', 'image_secondary');
        $this->retraits = ['image' => false, 'image_secondary' => false];
        $this->header->refresh();

        session()->flash('status', 'Bloc enregistré.');
    }

    public function render(): View
    {
        return view('livewire.admin.pages.form', [
            'champs' => PageHeaderSchema::for($this->header),
            'icones' => self::ICONES,
            'famille' => PageHeaderIndex::FAMILLES[$this->header->group] ?? $this->header->group,
        ]);
    }
}
