{{--
    Description de la filière, saisie au back-office.

    Section à part, sur toute la largeur du conteneur : elle ne partage plus
    la rangée des chiffres. Markdown rendu avec le HTML brut retiré — un
    compte rédacteur ne peut pas injecter de balises.
--}}
@if (filled($project->description))
    <section class="filiere-presentation" aria-labelledby="titre-presentation">
        <div class="container">
            <p class="sd3-label filiere-presentation__eyebrow">La filière</p>
            <h2 id="titre-presentation" class="filiere-presentation__title">{{ $project->title }}</h2>

            <div class="filiere-presentation__body">
                {!! $project->descriptionHtml() !!}
            </div>
        </div>
    </section>
@endif
