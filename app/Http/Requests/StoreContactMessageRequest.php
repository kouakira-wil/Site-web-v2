<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;

class StoreContactMessageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email:rfc', 'max:180'],
            'phone' => ['nullable', 'string', 'max:40'],
            'subject' => ['nullable', 'string', 'max:180'],
            'message' => ['required', 'string', 'min:20', 'max:5000'],
            /*
             * Piège à robots. Le champ s'appelait « website » : les extensions
             * de remplissage automatique le reconnaissaient et le remplissaient,
             * ce qui faisait rejeter des demandes légitimes. Un nom sans
             * signification ne leur dit plus rien.
             */
            'reference_interne' => ['prohibited'],
        ];
    }

    /**
     * Journalise le déclenchement du piège à robots.
     *
     * Un message rejeté ici n'est pas enregistré : sans trace, une demande
     * légitime bloquée par un remplissage automatique disparaîtrait sans que
     * personne ne puisse le savoir.
     */
    protected function failedValidation(Validator $validator): void
    {
        if ($validator->errors()->has('reference_interne')) {
            Log::warning('Demande de contact bloquée par le piège à robots', [
                'nom' => $this->input('name'),
                'email' => $this->input('email'),
                'ip' => $this->ip(),
            ]);
        }

        parent::failedValidation($validator);
    }

    /**
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'name' => 'nom',
            'email' => 'adresse e-mail',
            'phone' => 'téléphone',
            'subject' => 'sujet',
            'message' => 'message',
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'reference_interne.prohibited' => 'Votre message n’a pas pu être envoyé.',
            'message.min' => 'Merci de détailler votre demande en 20 caractères minimum.',
        ];
    }
}
