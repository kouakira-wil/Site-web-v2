@php
    $s = \App\Models\PageHeader::forKey('home.marquee');
@endphp

<!-- at-sec8-area-start -->
                <div class="container-2200">
                    <div class="at-sec8-area pt-90 pb-90 bg-neutral-50 !rounded-[2rem] lg:!mx-4 !mx-2 mt-10">
                        <div class="container">
                            <div class="flex flex-wrap -mx-3 [&>*]:px-3">
                                <div class="w-full">
                                    <div class="!flex !flex-wrap lg:!justify-between !justify-around !items-center !gap-6">
                                        @foreach ($s?->tagPairs() ?? [] as [$valeur, $libelle])
                                            <div>
                                                {{-- `.h1` donne l'allure d'un titre sans en être un :
                                                     le seul <h1> de la page reste celui du bandeau. --}}
                                                <p class="h1 !mb-0"><span class="odometer !whitespace-nowrap" data-count="{{ $valeur }}"></span></p>
                                                <p>{{ $libelle }}</p>
                                            </div>
                                        @endforeach

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- at-sec8-area-end -->
