<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Http\Requests\StoreContactMessageRequest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Vérification du formulaire de contact avant l'envoi.
 *
 * Le serveur reste seul juge : le script peut ne pas tourner, et un envoi
 * forgé ne passe pas par lui. Ces tests gardent les deux côtés alignés — une
 * contrainte annoncée dans la page mais absente du serveur laisserait passer,
 * et l'inverse ferait rejeter une saisie que la page déclarait bonne.
 */
class ContactValidationTest extends TestCase
{
    use RefreshDatabase;

    private function page(): string
    {
        return $this->get('/contact')->assertOk()->getContent();
    }

    /**
     * Les bornes chiffrées des règles du serveur, par champ.
     *
     * @return array<string, array<string, int>>
     */
    private function bornesDuServeur(): array
    {
        $bornes = [];

        foreach ((new StoreContactMessageRequest())->rules() as $champ => $regles) {
            foreach ($regles as $regle) {
                if (preg_match('/^(max|min):(\d+)$/', (string) $regle, $trouve) === 1) {
                    $bornes[$champ][$trouve[1]] = (int) $trouve[2];
                }
            }
        }

        return $bornes;
    }

    public function test_the_browser_bubbles_are_replaced_by_our_own_messages(): void
    {
        // Sans `novalidate`, le navigateur affiche ses bulles, en anglais et
        // hors de la charte, avant que le script ait la main.
        $this->assertStringContainsString('data-contact novalidate', $this->page());
    }

    public function test_each_field_carries_the_same_bounds_as_the_server(): void
    {
        $contenu = $this->page();
        $bornes = $this->bornesDuServeur();

        $attributs = [
            'name' => 'contact-nom',
            'email' => 'contact-email',
            'phone' => 'contact-telephone',
            'subject' => 'contact-sujet',
            'message' => 'contact-message',
        ];

        foreach ($attributs as $champ => $id) {
            preg_match('#<(?:input|textarea)[^>]*id="'.$id.'"[^>]*>#', $contenu, $balise);

            $this->assertNotEmpty($balise, "Le champ {$champ} est introuvable.");

            foreach ($bornes[$champ] ?? [] as $sorte => $valeur) {
                $this->assertStringContainsString(
                    $sorte.'length="'.$valeur.'"',
                    $balise[0],
                    "Le champ {$champ} n’annonce pas la borne {$sorte}:{$valeur} du serveur.",
                );
            }
        }
    }

    public function test_the_required_fields_are_marked_as_such(): void
    {
        $contenu = $this->page();

        foreach (['contact-nom', 'contact-email', 'contact-message'] as $id) {
            preg_match('#<(?:input|textarea)[^>]*id="'.$id.'"[^>]*>#', $contenu, $balise);

            $this->assertStringContainsString('required', $balise[0], "{$id} n’est pas marqué obligatoire.");
        }

        // Téléphone et sujet sont facultatifs côté serveur : les exiger dans
        // la page bloquerait un envoi que le serveur accepte.
        foreach (['contact-telephone', 'contact-sujet'] as $id) {
            preg_match('#<(?:input|textarea)[^>]*id="'.$id.'"[^>]*>#', $contenu, $balise);

            $this->assertStringNotContainsString('required', $balise[0], "{$id} est exigé à tort.");
        }
    }

    public function test_each_field_has_a_place_for_its_message(): void
    {
        /*
         * Les avis existent dès le chargement, masqués : le script n'a qu'à
         * les remplir. Les créer à la volée les laisserait hors de
         * `aria-describedby`, donc muets pour un lecteur d'écran.
         */
        $contenu = $this->page();

        $this->assertSame(5, substr_count($contenu, 'data-erreur='));

        foreach (['nom', 'email', 'telephone', 'sujet', 'message'] as $champ) {
            $this->assertMatchesRegularExpression(
                '#aria-describedby="[^"]*erreur-'.$champ.'[^"]*"#',
                $contenu,
                "Le champ {$champ} ne pointe pas vers son avis.",
            );
        }
    }

    public function test_the_hardest_rule_is_announced_before_typing(): void
    {
        // Le minimum de 20 caractères est la règle qui bloque le plus souvent :
        // elle est dite sous le champ, pas découverte au refus.
        $this->assertStringContainsString('20 caractères minimum', $this->page());
    }

    public function test_the_server_still_refuses_what_the_page_would_have_caught(): void
    {
        /*
         * La vérification de la page est un confort, pas une barrière : un
         * envoi qui la contourne doit rencontrer les mêmes règles.
         */
        $this->from('/contact')->post('/contact', [
            'name' => '',
            'email' => 'pas-une-adresse',
            'message' => 'Trop court.',
            'website' => '',
        ])->assertRedirect('/contact')->assertSessionHasErrors(['name', 'email', 'message']);

        $this->assertDatabaseCount('contact_messages', 0);
    }
}
