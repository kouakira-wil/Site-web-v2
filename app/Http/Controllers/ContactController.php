<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\StoreContactMessageRequest;
use App\Mail\ContactMessageReceived;
use App\Models\ContactMessage;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function show(): View
    {
        return view('site.contact');
    }

    public function store(StoreContactMessageRequest $request): RedirectResponse
    {
        $message = ContactMessage::query()->create([
            ...$request->safe()->except('website'),
            'ip_address' => $request->ip(),
        ]);

        $this->notifyTeam($message);

        return redirect()
            ->route('contact')
            ->with('status', 'Merci, votre message est bien arrivé. Nous revenons vers vous sous 48 heures.');
    }

    /**
     * The message is already persisted, so a mail failure must not break the
     * visitor's submission — it is logged for the team instead.
     */
    private function notifyTeam(ContactMessage $message): void
    {
        $recipient = config('mail.contact_recipient');

        if (blank($recipient)) {
            return;
        }

        try {
            Mail::to($recipient)->send(new ContactMessageReceived($message));
        } catch (\Throwable $exception) {
            Log::error('Notification de contact non envoyée', [
                'contact_message_id' => $message->id,
                'exception' => $exception->getMessage(),
            ]);
        }
    }
}
