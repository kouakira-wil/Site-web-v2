<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Mail\ContactMessageReceived;
use App\Models\ContactMessage;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

/**
 * Vérifie la chaîne d'envoi du formulaire de contact.
 *
 * En production, une panne SMTP est volontairement avalée : le message du
 * visiteur est déjà en base, et son envoi ne doit pas échouer parce que le
 * serveur de courrier répond mal. L'effet de bord est qu'on ne sait pas si
 * l'envoi fonctionne. Cette commande le dit, en laissant l'erreur remonter.
 */
class TesterEnvoiContact extends Command
{
    protected $signature = 'contact:tester
                            {--destinataire= : Adresse à prévenir, au lieu de celle configurée}';

    protected $description = 'Envoie un message de contact fictif pour vérifier la configuration SMTP';

    public function handle(): int
    {
        $destinataire = $this->option('destinataire') ?: config('mail.contact_recipient');

        if (blank($destinataire)) {
            $this->components->error('Aucun destinataire : renseignez MAIL_CONTACT_RECIPIENT.');

            return self::FAILURE;
        }

        $this->components->info('Transport : '.config('mail.default').' · '.config('mail.mailers.smtp.host').':'.config('mail.mailers.smtp.port'));
        $this->components->info('Expéditeur : '.config('mail.from.address'));
        $this->components->info('Destinataire : '.$destinataire);

        // Un message non enregistré : la commande vérifie l'envoi, pas la base.
        $message = new ContactMessage([
            'name' => 'Test de configuration',
            'email' => config('mail.from.address'),
            'subject' => 'Vérification de l’envoi',
            'message' => 'Message envoyé par la commande contact:tester. Si vous le lisez, la chaîne d’envoi fonctionne.',
        ]);
        $message->created_at = now();

        try {
            Mail::to($destinataire)->send(new ContactMessageReceived($message));
        } catch (\Throwable $exception) {
            $this->components->error('Envoi impossible : '.$exception->getMessage());

            return self::FAILURE;
        }

        $this->components->success('Message remis au transport. Vérifiez la boîte de '.$destinataire.'.');

        return self::SUCCESS;
    }
}
