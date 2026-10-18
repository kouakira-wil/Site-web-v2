<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Testimonial;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Le ruban des témoignages ne boucle que s'il porte assez de vignettes.
 * Swiper exige « slidesPerView + fiches tampons » fiches, soit cinq avec les
 * trois colonnes du bureau ; en dessous il désactive la boucle sans rien dire
 * et le défilement s'arrête sur la dernière carte.
 */
class HomeTestimonialCarouselTest extends TestCase
{
    use RefreshDatabase;

    private function temoignages(int $nombre, bool $actif = true): void
    {
        foreach (range(1, $nombre) as $i) {
            Testimonial::query()->create([
                'author' => "Étudiant {$i}",
                'quote' => "Témoignage numéro {$i}.",
                'is_active' => $actif,
                'position' => $i,
            ]);
        }
    }

    private function vignettes(): int
    {
        $contenu = $this->get(route('home'))->assertOk()->getContent();

        $ruban = substr($contenu, (int) strpos($contenu, 'slider-testimonial'));

        return substr_count($ruban, 'swiper-slide">');
    }

    /**
     * @return array<string, array{int}>
     */
    public static function nombresDeTémoignages(): array
    {
        return ['un' => [1], 'deux' => [2], 'trois' => [3], 'quatre' => [4], 'cinq' => [5]];
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('nombresDeTémoignages')]
    public function test_the_ribbon_always_carries_enough_cards_to_loop(int $nombre): void
    {
        $this->temoignages($nombre);

        $this->assertGreaterThanOrEqual(
            6,
            $this->vignettes(),
            "Avec {$nombre} témoignage(s), le ruban n'a pas de quoi boucler.",
        );
    }

    public function test_the_list_is_not_padded_once_it_is_long_enough(): void
    {
        $this->temoignages(7);

        // Sept fiches suffisent : les répéter ferait revenir les mêmes visages.
        $this->assertSame(7, $this->vignettes());
    }

    public function test_an_empty_list_leaves_the_ribbon_empty(): void
    {
        // Sans témoignage actif, la boucle de remplissage ne doit pas tourner
        // dans le vide.
        $this->assertSame(0, $this->vignettes());
    }

    public function test_an_inactive_testimonial_stays_out(): void
    {
        $this->temoignages(6);
        Testimonial::query()->create(['author' => 'Ancien', 'quote' => 'Témoignage retiré', 'is_active' => false]);

        $contenu = $this->get(route('home'))->assertOk()->getContent();

        $this->assertStringNotContainsString('Témoignage retiré', $contenu);
        $this->assertSame(6, $this->vignettes());
    }
}
