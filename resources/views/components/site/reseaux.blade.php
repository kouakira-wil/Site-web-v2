@props(['style' => 'liste'])

{{--
    Les réseaux de l'école, en un seul endroit.

    La liste était recopiée dans cinq gabarits, avec des ensembles différents à
    chaque fois : certains affichaient Twitter, LinkedIn ou YouTube, pour
    lesquels l'école n'a pas de compte — ces liens retombaient alors sur
    `/contact`, c'est-à-dire ailleurs que là où leur libellé promettait.

    Un réseau sans adresse configurée n'est pas rendu du tout.

    `style` choisit l'habillage du gabarit :
      - `liste`     : pied de page, nom suivi d'une flèche ;
      - `grille`    : menu latéral, pictogramme et nom ;
      - `detaille`  : page contact, pictogramme, nom et flèche à droite.
--}}

@php
    $reseaux = collect([
        ['cle' => 'facebook', 'nom' => 'Facebook'],
        ['cle' => 'instagram', 'nom' => 'Instagram'],
        ['cle' => 'tiktok', 'nom' => 'TikTok'],
    ])->map(fn (array $reseau): array => $reseau + ['url' => (string) config('social.'.$reseau['cle'])])
      ->filter(fn (array $reseau): bool => filled($reseau['url']))
      ->values();

    // WhatsApp ne vient pas de `social` : c'est le numéro de l'école.
    if (filled(config('ecole.whatsapp'))) {
        $reseaux->push([
            'cle' => 'whatsapp',
            'nom' => 'WhatsApp',
            'url' => 'https://wa.me/'.config('ecole.whatsapp'),
            'aide' => config('ecole.telephone'),
        ]);
    }
@endphp

@if ($reseaux->isNotEmpty())
    @if ($style === 'grille')
        @foreach ($reseaux as $reseau)
            <li>
                <a href="{{ $reseau['url'] }}" target="_blank" rel="noopener noreferrer"
                   class="at-offcanvas-social__link"
                   aria-label="{{ $reseau['nom'] }}{{ isset($reseau['aide']) ? ' : '.$reseau['aide'] : '' }}">
                    <x-site.reseau-icone :nom="$reseau['cle']" class="w-[18px] h-[18px]" />
                    <span>{{ $reseau['nom'] }}</span>
                </a>
            </li>
        @endforeach
    @elseif ($style === 'detaille')
        @foreach ($reseaux as $reseau)
            <li>
                <a href="{{ $reseau['url'] }}" target="_blank" rel="noopener noreferrer"
                   aria-label="{{ $reseau['nom'] }}{{ isset($reseau['aide']) ? ' : '.$reseau['aide'] : '' }}">
                    <div class="!flex !items-center !gap-2">
                        <div class="icon-social">
                            <x-site.reseau-icone :nom="$reseau['cle']" />
                        </div>
                        {{ $reseau['nom'] }}
                    </div>
                    <div class="icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="10" viewBox="0 0 12 10" fill="none" aria-hidden="true">
                            <path d="M7.60201e-05 6.26559L0 2.03894e-05L1.49997 -4.58971e-07L1.50004 4.87322L9.12873 4.87329L6.16652 2.12355L7.22714 1.139L12 5.56948L7.22713 10L6.16652 9.01545L9.12876 6.26566L7.60201e-05 6.26559Z" fill="currentColor" />
                        </svg>
                    </div>
                </a>
            </li>
        @endforeach
    @else
        @foreach ($reseaux as $reseau)
            <a href="{{ $reseau['url'] }}" target="_blank" rel="noopener noreferrer"
               aria-label="{{ $reseau['nom'] }}{{ isset($reseau['aide']) ? ' : '.$reseau['aide'] : '' }}">
                {{ $reseau['nom'] }}
                <svg xmlns="http://www.w3.org/2000/svg" width="9" height="10" viewBox="0 0 9 10" fill="none" aria-hidden="true">
                    <path d="M5.62494 9.99994L0.562517 10L0.5625 8.75003L4.49994 8.74996L4.5 2.39273L2.27828 4.86124L1.48278 3.97739L5.0625 0L8.64225 3.97739L7.84676 4.86124L5.625 2.3927L5.62494 9.99994Z" fill="currentColor" />
                </svg>
            </a>
        @endforeach
    @endif
@endif
