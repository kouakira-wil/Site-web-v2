@php
    /*
     * Mention de copyright, datée à l'affichage.
     *
     * L'année était écrite en dur dans les deux pieds de page : elle devenait
     * fausse au premier janvier, sans que rien ne le signale.
     *
     * Elle se lit dans le fuseau de l'école, pas celui de l'application :
     * `config('app.timezone')` vaut UTC, et Brazzaville a une heure d'avance.
     * Le 1er janvier entre minuit et une heure, UTC afficherait encore
     * l'année écoulée.
     */
    $annee = now()->setTimezone(config('ecole.fuseau'))->year;
    $depuis = (int) config('ecole.annee_creation');

    // Une plage n'a de sens qu'à partir de la deuxième année.
    $mention = $depuis > 0 && $depuis < $annee
        ? $depuis.'–'.$annee
        : (string) $annee;
@endphp

<span {{ $attributes->merge(['class' => 'at-footer-copyright']) }}>
    {{ config('app.name') }} © <time datetime="{{ $annee }}">{{ $mention }}</time>
</span>
