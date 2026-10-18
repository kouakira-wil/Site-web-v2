{{--
    Préchargeur : dix volets sombres qui se rétractent, le logo au centre.
    Les classes `page-loader` et `bar` viennent du gabarit Orisa, qui portait
    déjà leurs styles sans jamais poser le marquage.

    Le voile est écrit dans la page, et non injecté par le script : injecté, il
    n'apparaîtrait qu'après le premier rendu, c'est-à-dire trop tard pour
    masquer quoi que ce soit.

    Un voile en `position: fixed` et `z-index: 99999` rend le site inutilisable
    s'il ne part pas. Trois filets, du plus souhaitable au plus sûr :
      1. `initPrechargeur()` l'efface à la fin du chargement, en fondu ;
      2. le script en ligne ci-dessous l'efface de toute façon, sans dépendre
         du bundle Vite — s'il ne se charge pas, le site reste consultable ;
      3. sans JavaScript du tout, le `noscript` ne l'affiche jamais.

    `aria-hidden` le tient hors de l'arbre d'accessibilité : il n'annonce rien
    qu'un lecteur d'écran doive entendre, et le contenu est déjà derrière lui.
--}}
<noscript>
    <style>.page-loader { display: none !important; }</style>
</noscript>

<div class="page-loader" data-prechargeur aria-hidden="true">
    @for ($volet = 0; $volet < 10; $volet++)
        <div class="bar"></div>
    @endfor

    <div class="page-loader-logo">
        <img src="{{ asset('assets/imgs/template/logo/favicon.png') }}" alt=""
             width="200" height="200" decoding="async">
    </div>
</div>

<script>
    /*
     * Filet de sécurité, volontairement en ligne : il s'exécute même si le
     * bundle échoue, et le délai couvre une connexion lente sans jamais
     * laisser le voile en place indéfiniment.
     */
    window.setTimeout(function () {
        var voile = document.querySelector('[data-prechargeur]');

        if (voile) {
            voile.remove();
        }
    }, 5000);
</script>
