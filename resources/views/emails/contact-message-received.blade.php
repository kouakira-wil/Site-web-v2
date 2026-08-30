@component('mail::message')
# Nouveau message depuis le site

**De :** {{ $contactMessage->name }} &lt;{{ $contactMessage->email }}&gt;
@if ($contactMessage->phone)
**Téléphone :** {{ $contactMessage->phone }}
@endif
**Sujet :** {{ $contactMessage->subject ?: 'Non précisé' }}
**Reçu le :** {{ $contactMessage->created_at->translatedFormat('d F Y à H:i') }}

---

{{ $contactMessage->message }}

@component('mail::button', ['url' => route('admin.messages.index')])
Ouvrir dans le back-office
@endcomponent

@endcomponent
