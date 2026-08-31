<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Testimonial extends Model
{
    use HasFactory;

    protected $fillable = [
        'author',
        'quote',
        'program',
        'graduation_year',
        'location',
        'photo',
        'is_active',
        'position',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'position' => 'integer',
            'graduation_year' => 'integer',
        ];
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('position')->orderByDesc('graduation_year');
    }

    public function photoUrl(): ?string
    {
        return $this->photo ? Storage::url($this->photo) : null;
    }

    /**
     * Signature affichée en gras sous la citation.
     *
     * Le nom reste facultatif : sans lui, on signe par la promotion, ce que
     * font les témoignages déjà en ligne.
     */
    public function signature(): string
    {
        if (filled($this->author)) {
            return $this->author;
        }

        return filled($this->graduation_year)
            ? 'Diplômé(e), promotion '.$this->graduation_year
            : 'Diplômé(e) de l’ESCAT';
    }

    /**
     * Citation encadrée de guillemets français.
     *
     * Les guillemets sont posés au rendu et non à la saisie : un rédacteur
     * qui les tape lui-même n'en obtient pas deux paires.
     */
    public function formattedQuote(): string
    {
        $texte = trim((string) $this->quote);
        $texte = trim($texte, '«» '.chr(9).chr(10).chr(13));

        return '« '.$texte.' »';
    }

    /**
     * Initiale de repli quand aucune photo n'a été téléversée.
     */
    public function initial(): string
    {
        $source = filled($this->author) ? $this->author : ($this->program ?: 'E');

        return mb_strtoupper(mb_substr(trim($source), 0, 1));
    }
}
