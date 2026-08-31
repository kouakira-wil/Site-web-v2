<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\StoreNewsletterSubscriptionRequest;
use App\Models\NewsletterSubscriber;
use Illuminate\Http\RedirectResponse;

class NewsletterController extends Controller
{
    public function store(StoreNewsletterSubscriptionRequest $request): RedirectResponse
    {
        $email = mb_strtolower(trim($request->validated('email')));

        /*
         * updateOrCreate plutôt que create : une adresse déjà inscrite ne
         * déclenche pas d'erreur de contrainte, et une personne s'étant
         * désinscrite est réactivée. On ne révèle pas non plus, par un message
         * différent, si l'adresse était déjà connue.
         */
        NewsletterSubscriber::query()->updateOrCreate(
            ['email' => $email],
            [
                'ip_address' => $request->ip(),
                'unsubscribed_at' => null,
            ],
        );

        return back()
            ->with('newsletter_status', 'Merci, votre adresse est bien enregistrée.')
            ->withFragment('newsletter');
    }
}
