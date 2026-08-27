<x-layouts.app title="Maatkast configurator met live prijs | MAATATELIER" description="Stel je maatkast, dressing of meubel visueel samen en zie meteen een berekende richtprijs inclusief btw, levering en plaatsing. Upload daarna maximaal 5 bestanden van 15 MB.">
    <section class="border-b border-taupe/40 bg-sand text-anthracite">
        <div class="mx-auto max-w-7xl px-5 py-16 sm:px-8 lg:px-10 lg:py-20">
            <p class="section-label">Live configurator & offerte</p>
            <h1 class="mt-6 max-w-5xl font-brand text-5xl font-semibold leading-[1.02] tracking-[-0.055em] sm:text-7xl">Jouw meubel. Meteen zichtbaar én berekend.</h1>
            <p class="mt-7 max-w-2xl border-l-2 border-olive pl-6 text-lg leading-8 text-anthracite/70">Kies, schuif en verfijn. Het meubelbeeld en de richtprijs veranderen live mee. Daarna kun je foto's toevoegen en het ontwerp technisch laten controleren.</p>
        </div>
    </section>

    <section class="mx-auto max-w-[94rem] px-5 py-16 sm:px-8 lg:px-10 lg:py-24">
        @if ($errors->any())
            <div class="mb-8 rounded-2xl border-2 border-oak bg-ivory p-6 text-anthracite" role="alert" tabindex="-1" data-form-errors>
                <h2 class="font-brand font-semibold">We kunnen je aanvraag nog niet versturen.</h2>
                <p class="mt-2 text-sm">Controleer de gemarkeerde velden. Je ingevulde gegevens zijn bewaard.</p>
            </div>
        @endif

        <form action="{{ route('quote_requests.store') }}" method="POST" enctype="multipart/form-data" class="grid gap-8 lg:grid-cols-[minmax(0,1fr)_22rem]" data-quote-wizard data-furniture-configurator data-configurator-rules="{{ json_encode($configuratorRules, JSON_THROW_ON_ERROR) }}" data-has-errors="{{ $errors->any() ? 'true' : 'false' }}">
            @csrf
            <input type="hidden" name="configured" value="1">

            <div class="min-w-0">
                <div class="mb-8" aria-label="Voortgang aanvraag">
                    <div class="mb-3 flex items-center justify-between gap-4 text-xs font-medium text-anthracite/70">
                        <span data-progress-label>Stap 1 van 5</span>
                        <span>Ongeveer 5 minuten</span>
                    </div>
                    <div class="h-1.5 overflow-hidden rounded-full bg-sand" aria-hidden="true">
                        <div class="h-full w-1/5 rounded-full bg-olive transition-[width] duration-300 motion-reduce:transition-none" data-progress-bar></div>
                    </div>
                </div>

                <div class="mb-6 flex items-center justify-between gap-5 rounded-2xl bg-anthracite px-5 py-4 text-ivory lg:hidden" data-mobile-price-card>
                    <div>
                        <p class="text-[0.65rem] font-semibold uppercase tracking-[0.16em] text-oak">Live richtprijs</p>
                        <p class="mt-1 text-xs leading-5 text-ivory/70" data-price-status-mobile>Inclusief btw, levering en plaatsing.</p>
                    </div>
                    <p class="shrink-0 font-brand text-2xl font-semibold tracking-[-0.035em]" data-configurator-price-mobile>€ {{ number_format($initialConfiguredPrice['estimated_price_cents'] / 100, 0, ',', '.') }}</p>
                </div>

                <div class="grid gap-6">
                    <fieldset class="wizard-panel" data-wizard-step="1">
                        <legend class="wizard-legend">Kies je meubel</legend>
                        <p class="wizard-help">Start met het model dat het dichtst bij je idee ligt. Voor maatkasten en meubels krijg je meteen een live richtprijs.</p>
                        <div class="mt-7 grid gap-3 sm:grid-cols-2">
                            @foreach ([
                                ['maatkast', 'Maatkast', 'Opberging van wand tot wand', true],
                                ['dressing', 'Dressing', 'Open of rustig gesloten', true],
                                ['tv-meubel', 'TV-meubel', 'Techniek slim weggewerkt', true],
                                ['wandmeubel', 'Wandmeubel', 'Open en gesloten in balans', true],
                                ['bureau', 'Bureau', 'Een rustige werkplek op maat', true],
                                ['bijkeuken', 'Bijkeuken', 'Elke centimeter praktisch', true],
                                ['keuken', 'Keuken', 'Prijs na persoonlijk ontwerp', false],
                                ['ander-maatwerk', 'Ander maatwerk', 'Vertel ons wat je nodig hebt', false],
                            ] as [$value, $label, $description, $hasLivePrice])
                                <label class="choice-card">
                                    <input class="peer sr-only" type="radio" name="project_type" value="{{ $value }}" @checked(old('project_type', 'maatkast') === $value) @if ($errors->has('project_type')) aria-invalid="true" aria-describedby="project-type-error" @endif required>
                                    <span class="choice-card-content min-h-24 items-start">
                                        <span class="grid gap-1">
                                            <span data-choice-label>{{ $label }}</span>
                                            <span class="text-xs font-normal leading-5 text-anthracite/65">{{ $description }}</span>
                                            <span class="mt-1 text-[0.65rem] font-semibold uppercase tracking-[0.14em] text-olive">{{ $hasLivePrice ? 'Live prijs' : 'Persoonlijke prijs' }}</span>
                                        </span>
                                    </span>
                                </label>
                            @endforeach
                        </div>
                        @error('project_type')<p class="form-error" id="project-type-error">{{ $message }}</p>@enderror
                    </fieldset>

                    <fieldset class="wizard-panel" data-wizard-step="2" id="kast-ontwerper">
                        <legend class="wizard-legend">Maak het van jou</legend>
                        <div class="mt-7 rounded-[1.75rem] border border-taupe/50 bg-sand p-6 sm:p-8" data-personal-project-panel hidden>
                            <span class="grid size-12 place-items-center rounded-full bg-olive font-brand text-lg font-semibold text-ivory" aria-hidden="true">MA</span>
                            <h2 class="mt-5 font-brand text-2xl font-semibold tracking-[-0.03em]">Dit ontwerp maken we persoonlijk.</h2>
                            <p class="mt-3 max-w-2xl text-sm leading-6 text-anthracite/70">Een keuken of volledig vrij maatwerk past niet eerlijk in een standaard meubelmodel. Ga verder met je stijl, foto’s en wensen. We bekijken je ruimte en bezorgen daarna een duidelijke persoonlijke prijs.</p>
                            <p class="mt-5 text-xs font-semibold uppercase tracking-[0.14em] text-olive">Geen generieke kastprijs · wel technisch advies op maat</p>
                        </div>

                        <div data-live-configurator-panel>
                            <p class="wizard-help">Begin met globale maten. Elke keuze verandert meteen je meubelbeeld en berekende richtprijs.</p>

                            <div class="mt-8 grid gap-8 xl:grid-cols-[minmax(20rem,1.05fr)_minmax(19rem,0.95fr)] xl:items-start">
                            <div class="configurator-stage">
                                <div class="flex flex-wrap items-start justify-between gap-4">
                                    <div>
                                        <p class="section-label">Live meubelbeeld</p>
                                        <p class="mt-2 text-sm text-anthracite/65">Vooraanzicht op verhouding</p>
                                    </div>
                                    <span class="rounded-full border border-olive/40 bg-ivory px-3 py-2 text-[0.65rem] font-semibold uppercase tracking-[0.14em] text-olive">Live</span>
                                </div>

                                <div class="mt-5 grid min-h-80 place-items-center overflow-hidden rounded-[1.5rem] border border-taupe/50 bg-ivory p-4 sm:min-h-[28rem] sm:p-7">
                                    <svg class="h-auto max-h-[26rem] w-full" viewBox="0 0 800 600" aria-hidden="true" focusable="false" data-configurator-preview>
                                        <defs>
                                            <filter id="cabinet-shadow" x="-20%" y="-20%" width="140%" height="160%">
                                                <feDropShadow dx="0" dy="14" stdDeviation="12" flood-color="#222222" flood-opacity="0.14" />
                                            </filter>
                                            <pattern id="oak-grain" width="26" height="80" patternUnits="userSpaceOnUse">
                                                <rect width="26" height="80" fill="#d8b58a" />
                                                <path d="M4 0 C12 20 0 36 9 56 S14 72 10 80 M20 0 C12 18 27 34 18 54 S17 70 22 80" fill="none" stroke="#b8aa98" stroke-width="1.2" opacity="0.55" />
                                            </pattern>
                                        </defs>
                                        <path d="M90 525 H710" stroke="#b8aa98" stroke-width="2" stroke-linecap="round" opacity="0.6" />
                                        <g data-configurator-drawing filter="url(#cabinet-shadow)">
                                            <rect x="170" y="120" width="460" height="390" rx="4" fill="url(#oak-grain)" stroke="#6f6a4d" stroke-width="4" />
                                            <path d="M285 122 V508 M400 122 V508 M515 122 V508" stroke="#222222" stroke-opacity="0.28" stroke-width="2" />
                                        </g>
                                    </svg>
                                </div>
                                <p class="mt-4 min-h-12 text-sm leading-6 text-anthracite/70" data-configurator-description>Gesloten maatkast van 2400 × 2500 × 600 mm, vier modules, licht eiken en comfort binnenwerk.</p>
                            </div>

                            <div class="grid gap-5">
                                <fieldset class="configurator-group">
                                    <legend class="configurator-group-title"><span>01</span> Afmetingen</legend>
                                    <div class="mt-5 grid gap-5">
                                        @foreach ([
                                            ['width_mm', 'Breedte', 600, 5000, 50, 2400],
                                            ['height_mm', 'Hoogte', 500, 3000, 50, 2500],
                                            ['depth_mm', 'Diepte', 250, 800, 10, 600],
                                        ] as [$name, $label, $min, $max, $step, $default])
                                            <div data-measurement-control>
                                                <div class="flex flex-col items-start gap-3 sm:flex-row sm:items-center sm:justify-between sm:gap-4">
                                                    <label class="form-label" for="{{ $name }}">{{ $label }}</label>
                                                    <div class="measurement-stepper">
                                                        <button type="button" data-step-down aria-label="{{ $label }} met {{ $step }} millimeter verminderen">−</button>
                                                        <div class="relative">
                                                            <input class="measurement-number" id="{{ $name }}" name="{{ $name }}" type="number" min="{{ $min }}" max="{{ $max }}" step="{{ $step }}" inputmode="numeric" value="{{ old($name, $default) }}" @error($name) aria-invalid="true" aria-describedby="{{ $name }}-error" @enderror required>
                                                            <span aria-hidden="true">mm</span>
                                                        </div>
                                                        <button type="button" data-step-up aria-label="{{ $label }} met {{ $step }} millimeter verhogen">+</button>
                                                    </div>
                                                </div>
                                                <label class="sr-only" for="{{ $name }}-range">{{ $label }} instellen, in millimeter</label>
                                                <input class="configurator-range mt-4" id="{{ $name }}-range" type="range" min="{{ $min }}" max="{{ $max }}" step="{{ $step }}" value="{{ old($name, $default) }}" data-range-for="{{ $name }}">
                                                @error($name)<p class="form-error" id="{{ $name }}-error">{{ $message }}</p>@enderror
                                            </div>
                                        @endforeach
                                    </div>
                                    <details class="mt-5 rounded-xl border border-taupe/50 bg-ivory px-4 py-3 text-sm">
                                        <summary class="min-h-11 cursor-pointer py-2 font-semibold text-olive">Hulp bij het meten</summary>
                                        <p class="pb-2 leading-6 text-anthracite/70">Meet op drie plaatsen en gebruik voorlopig de kleinste maat. Een afwijking is geen probleem: voor productie meten we alles technisch na.</p>
                                    </details>
                                </fieldset>

                                <fieldset class="configurator-group">
                                    <legend class="configurator-group-title"><span>02</span> Indeling</legend>
                                    <div class="mt-5 grid gap-5">
                                        <div>
                                            <label class="form-label" for="layout_columns">Aantal modules</label>
                                            <div class="measurement-stepper mt-2 w-fit">
                                                <button type="button" data-counter-down="layout_columns" aria-label="Eén module minder">−</button>
                                                <input class="counter-number" id="layout_columns" name="layout_columns" type="number" min="{{ $configuratorRules['modules']['min'] }}" max="{{ $configuratorRules['modules']['max'] }}" step="1" inputmode="numeric" value="{{ old('layout_columns', $configuratorRules['modules']['default']) }}" @error('layout_columns') aria-invalid="true" aria-describedby="layout-columns-error" @enderror required>
                                                <button type="button" data-counter-up="layout_columns" aria-label="Eén module meer">+</button>
                                            </div>
                                            @error('layout_columns')<p class="form-error" id="layout-columns-error">{{ $message }}</p>@enderror
                                        </div>

                                        <fieldset>
                                            <legend class="form-label">Voorkant</legend>
                                            <div class="mt-3 grid grid-cols-3 gap-2">
                                                @foreach (['open' => 'Open', 'draaideuren' => 'Draai', 'schuifdeuren' => 'Schuif'] as $value => $label)
                                                    <label class="mini-choice">
                                                        <input class="peer sr-only" type="radio" name="front_style" value="{{ $value }}" @checked(old('front_style', 'draaideuren') === $value) @if ($errors->has('front_style')) aria-invalid="true" aria-describedby="front-style-error" @endif required>
                                                        <span>{{ $label }}</span>
                                                    </label>
                                                @endforeach
                                            </div>
                                            @error('front_style')<p class="form-error" id="front-style-error">{{ $message }}</p>@enderror
                                        </fieldset>

                                        <fieldset>
                                            <legend class="form-label">Binnenwerk</legend>
                                            <div class="mt-3 grid gap-2">
                                                @foreach ([
                                                    'basis' => ['Basis', 'Slimme legplanken'],
                                                    'comfort' => ['Comfort', 'Meer verdeling en zacht beslag'],
                                                    'premium' => ['Premium', 'Maximale afwerking en detail'],
                                                ] as $value => [$label, $description])
                                                    <label class="choice-card">
                                                        <input class="peer sr-only" type="radio" name="interior_level" value="{{ $value }}" @checked(old('interior_level', 'comfort') === $value) @if ($errors->has('interior_level')) aria-invalid="true" aria-describedby="interior-level-error" @endif required>
                                                        <span class="choice-card-content justify-between">
                                                            <span><strong class="block">{{ $label }}</strong><small class="mt-1 block font-normal text-anthracite/65">{{ $description }}</small></span>
                                                            <span class="text-olive" aria-hidden="true">✓</span>
                                                        </span>
                                                    </label>
                                                @endforeach
                                            </div>
                                            @error('interior_level')<p class="form-error" id="interior-level-error">{{ $message }}</p>@enderror
                                        </fieldset>

                                        <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-1 2xl:grid-cols-2">
                                            @foreach ([
                                                ['drawer_count', 'Laden', $configuratorRules['extras']['laden']['min'], $configuratorRules['extras']['laden']['max']],
                                                ['rail_count', 'Kledingroedes', $configuratorRules['extras']['roedes']['min'], $configuratorRules['extras']['roedes']['max']],
                                            ] as [$name, $label, $min, $max])
                                                <div>
                                                    <label class="form-label" for="{{ $name }}">{{ $label }}</label>
                                                    <div class="measurement-stepper mt-2 w-fit">
                                                        <button type="button" data-counter-down="{{ $name }}" aria-label="Eén {{ strtolower($label) }} minder">−</button>
                                                        <input class="counter-number" id="{{ $name }}" name="{{ $name }}" type="number" min="{{ $min }}" max="{{ $max }}" step="1" inputmode="numeric" value="{{ old($name, $name === 'drawer_count' ? 2 : 1) }}" @error($name) aria-invalid="true" aria-describedby="{{ str($name)->replace('_', '-') }}-error" @enderror required>
                                                        <button type="button" data-counter-up="{{ $name }}" aria-label="Eén {{ strtolower($label) }} meer">+</button>
                                                    </div>
                                                    @error($name)<p class="form-error" id="{{ str($name)->replace('_', '-') }}-error">{{ $message }}</p>@enderror
                                                </div>
                                            @endforeach
                                        </div>

                                        <input type="hidden" name="led_lighting" value="0">
                                        <label class="check-row flex min-h-14 cursor-pointer items-center justify-between gap-3 rounded-xl border border-taupe/50 bg-ivory px-4 py-3 text-sm transition-colors hover:border-olive hover:bg-sand/45">
                                            <span class="flex items-center gap-3"><input class="form-checkbox" type="checkbox" name="led_lighting" value="1" @checked(old('led_lighting') === '1') @error('led_lighting') aria-invalid="true" aria-describedby="led-lighting-error" @enderror> Geïntegreerde ledverlichting</span>
                                            <span class="text-xs font-semibold text-olive" data-option-price="led_lighting"></span>
                                        </label>
                                        @error('led_lighting')<p class="form-error" id="led-lighting-error">{{ $message }}</p>@enderror
                                    </div>
                                </fieldset>
                            </div>
                        </div>

                        <fieldset class="configurator-group mt-8">
                            <legend class="configurator-group-title"><span>03</span> Materiaal & kleur</legend>
                            <div class="mt-5 grid gap-3 sm:grid-cols-2 lg:grid-cols-5">
                                @foreach ([
                                    'ivoor' => ['Ivoor', '#F7F5F2'],
                                    'zand' => ['Zand', '#E7DED1'],
                                    'olijfbrons' => ['Olijfbrons', '#6F6A4D'],
                                    'licht-eiken' => ['Licht eiken', '#D8B58A'],
                                    'naturel-eiken' => ['Naturel eiken', '#B8AA98'],
                                ] as $value => [$label, $colour])
                                    <label class="material-choice">
                                        <input class="peer sr-only" type="radio" name="finish" value="{{ $value }}" @checked(old('finish', 'licht-eiken') === $value) @if ($errors->has('finish')) aria-invalid="true" aria-describedby="finish-error" @endif required>
                                        <span class="material-choice-content">
                                            <span class="material-swatch" style="--swatch: {{ $colour }}" aria-hidden="true"></span>
                                            <span class="font-semibold">{{ $label }}</span>
                                            <span class="text-xs text-anthracite/65" data-material-price="{{ $value }}"></span>
                                        </span>
                                    </label>
                                @endforeach
                            </div>
                            @error('finish')<p class="form-error" id="finish-error">{{ $message }}</p>@enderror
                        </fieldset>

                        <input type="hidden" name="installation" value="1">
                        <input type="hidden" name="dimensions_are_approximate" value="0">
                        <label class="check-row mt-6 flex min-h-12 cursor-pointer items-center gap-3 rounded-xl border border-transparent px-3 text-sm transition-colors hover:bg-sand/45">
                            <input class="form-checkbox" type="checkbox" name="dimensions_are_approximate" value="1" @checked(old('dimensions_are_approximate', '1') === '1')>
                            Mijn afmetingen zijn voorlopig. MAATATELIER meet technisch na voor productie.
                            </label>
                        </div>
                    </fieldset>

                    <fieldset class="wizard-panel" data-wizard-step="3">
                        <legend class="wizard-legend">Welke sfeer en richting passen bij je?</legend>
                        <p class="wizard-help">Deze keuzes zijn een vertrekpunt, geen definitieve materiaalbeslissing.</p>
                        <div class="mt-7">
                            <span class="form-label">Stijl</span>
                            <div class="mt-3 grid gap-3 sm:grid-cols-2">
                                @foreach (['licht-hout' => 'Licht hout', 'donker-hout' => 'Donker hout', 'wit-minimalistisch' => 'Wit & minimalistisch', 'zwart-architecturaal' => 'Zwart & architecturaal', 'warm-neutraal' => 'Warm & neutraal', 'nog-te-bepalen' => 'Nog te bepalen'] as $value => $label)
                                    <label class="choice-card">
                                        <input class="peer sr-only" type="radio" name="style" value="{{ $value }}" @checked(old('style') === $value) @if ($errors->has('style')) aria-invalid="true" aria-describedby="style-error" @endif required>
                                        <span class="choice-card-content">{{ $label }}</span>
                                    </label>
                                @endforeach
                            </div>
                            @error('style')<p class="form-error" id="style-error">{{ $message }}</p>@enderror
                        </div>
                        <div class="mt-9 grid gap-6 sm:grid-cols-2">
                            <div>
                                <label class="form-label" for="budget">Budgetrichting</label>
                                <select class="form-input" id="budget" name="budget" @error('budget') aria-invalid="true" aria-describedby="budget-error" @enderror required>
                                    <option value="">Kies een richting</option>
                                    @foreach (['functioneel' => 'Functioneel & prijsbewust', 'gebalanceerd' => 'Balans in materiaal en budget', 'hoogwaardig' => 'Hoogwaardige materialen en afwerking', 'nog-te-bepalen' => 'Nog te bepalen'] as $value => $label)
                                        <option value="{{ $value }}" @selected(old('budget') === $value)>{{ $label }}</option>
                                    @endforeach
                                </select>
                                @error('budget')<p class="form-error" id="budget-error">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="form-label" for="timing">Gewenste timing</label>
                                <select class="form-input" id="timing" name="timing" @error('timing') aria-invalid="true" aria-describedby="timing-error" @enderror required>
                                    <option value="">Kies een timing</option>
                                    @foreach (['zo-snel-mogelijk' => 'Zo snel mogelijk', 'binnen-3-maanden' => 'Binnen 3 maanden', 'binnen-6-maanden' => 'Binnen 6 maanden', 'later' => 'Later', 'nog-te-bepalen' => 'Nog te bepalen'] as $value => $label)
                                        <option value="{{ $value }}" @selected(old('timing') === $value)>{{ $label }}</option>
                                    @endforeach
                                </select>
                                @error('timing')<p class="form-error" id="timing-error">{{ $message }}</p>@enderror
                            </div>
                        </div>
                    </fieldset>

                    <fieldset class="wizard-panel" data-wizard-step="4">
                        <legend class="wizard-legend">Laat je ruimte zien.</legend>
                        <p class="wizard-help">Upload maximaal 5 foto's, een schets of inspiratiebeeld. JPG, PNG, WebP of PDF, maximaal 15 MB per bestand.</p>
                        <div class="mt-7">
                            <span class="form-label">Foto’s en schetsen <span class="font-normal text-anthracite/70">(optioneel)</span></span>
                            <label class="upload-zone" for="attachments" data-upload-zone>
                                <input class="sr-only" id="attachments" name="attachments[]" type="file" accept=".jpg,.jpeg,.png,.webp,.pdf" multiple aria-describedby="attachments-help{{ $errors->has('attachments') || $errors->has('attachments.*') ? ' attachments-error' : '' }}" @if ($errors->has('attachments') || $errors->has('attachments.*')) aria-invalid="true" @endif>
                                <span class="grid size-14 place-items-center rounded-full bg-sand font-brand text-2xl font-semibold text-olive" aria-hidden="true">+</span>
                                <span class="mt-5 font-brand text-lg font-semibold">Sleep je foto's hierheen</span>
                                <span class="mt-2 text-sm leading-6 text-anthracite/70">of klik om foto’s, een schets of PDF te kiezen</span>
                                <span class="mt-4 rounded-full border border-olive px-4 py-2 font-brand text-xs font-semibold text-olive">Kies bestanden</span>
                            </label>
                            <div class="mt-4 hidden grid-cols-2 gap-3 sm:grid-cols-3" data-file-previews aria-live="polite"></div>
                            <p class="mt-3 text-xs text-anthracite/70" id="attachments-help" data-file-summary data-empty-text="Nog geen bestanden gekozen.">Nog geen bestanden gekozen.</p>
                            @if ($errors->has('attachments') || $errors->has('attachments.*'))
                                <p class="form-error" id="attachments-error">{{ $errors->first('attachments') ?: $errors->first('attachments.*') }}</p>
                            @endif
                        </div>
                        <div class="mt-7">
                            <label class="form-label" for="notes">Wat moeten we zeker weten? <span class="font-normal text-anthracite/70">(optioneel)</span></label>
                            <textarea class="form-input min-h-36" id="notes" name="notes" maxlength="2000" placeholder="Vertel iets over de ruimte, wat nu niet werkt of wat je zeker wilt behouden." @error('notes') aria-invalid="true" aria-describedby="notes-error" @enderror>{{ old('notes') }}</textarea>
                            @error('notes')<p class="form-error" id="notes-error">{{ $message }}</p>@enderror
                        </div>
                    </fieldset>

                    <fieldset class="wizard-panel" data-wizard-step="5">
                        <legend class="wizard-legend">Hoe kunnen we je bereiken?</legend>
                        <p class="wizard-help">We gebruiken je gegevens alleen om deze aanvraag te beoordelen en te beantwoorden.</p>
                        <div class="mt-7 grid gap-5 sm:grid-cols-2">
                            <div class="sm:col-span-2">
                                <label class="form-label" for="name">Naam</label>
                                <input class="form-input" id="name" name="name" type="text" autocomplete="name" value="{{ old('name') }}" maxlength="100" @error('name') aria-invalid="true" aria-describedby="name-error" @enderror required>
                                @error('name')<p class="form-error" id="name-error">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="form-label" for="email">E-mailadres</label>
                                <input class="form-input" id="email" name="email" type="email" autocomplete="email" inputmode="email" value="{{ old('email') }}" maxlength="254" @error('email') aria-invalid="true" aria-describedby="email-error" @enderror required>
                                @error('email')<p class="form-error" id="email-error">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="form-label" for="phone">Telefoonnummer</label>
                                <input class="form-input" id="phone" name="phone" type="tel" autocomplete="tel" inputmode="tel" value="{{ old('phone') }}" maxlength="30" @error('phone') aria-invalid="true" aria-describedby="phone-error" @enderror required>
                                @error('phone')<p class="form-error" id="phone-error">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="form-label" for="postal_code">Postcode</label>
                                <input class="form-input" id="postal_code" name="postal_code" type="text" autocomplete="postal-code" inputmode="numeric" pattern="[0-9]{4}" value="{{ old('postal_code') }}" maxlength="4" @error('postal_code') aria-invalid="true" aria-describedby="postal-code-error" @enderror required>
                                @error('postal_code')<p class="form-error" id="postal-code-error">{{ $message }}</p>@enderror
                            </div>
                        </div>
                        <div class="hidden" aria-hidden="true">
                            <label for="website">Website</label>
                            <input id="website" name="website" type="text" tabindex="-1" autocomplete="off">
                        </div>
                        <label class="check-row mt-7 flex cursor-pointer items-start gap-3 rounded-2xl border border-transparent bg-sand/45 p-5 text-sm leading-6 transition-colors hover:border-taupe">
                            <input class="form-checkbox mt-0.5" type="checkbox" name="consent" value="1" @checked(old('consent')) @error('consent') aria-invalid="true" aria-describedby="consent-error" @enderror required>
                            <span>Ik geef toestemming om mijn gegevens en bestanden te gebruiken voor het beantwoorden van deze aanvraag. Lees meer in het <a class="underline decoration-olive underline-offset-4 hover:text-olive" href="{{ route('privacy') }}">privacybeleid</a>.</span>
                        </label>
                        @error('consent')<p class="form-error" id="consent-error">{{ $message }}</p>@enderror
                    </fieldset>
                </div>

                <div class="mt-6 flex flex-col-reverse gap-3 sm:flex-row sm:justify-between" data-wizard-actions>
                    <button class="secondary-button hidden" type="button" data-wizard-back>Vorige stap</button>
                    <button class="primary-button hidden sm:ml-auto" type="button" data-wizard-next>Volgende stap</button>
                    <button class="primary-button sm:ml-auto" type="submit" data-wizard-submit>Verstuur je aanvraag</button>
                </div>
            </div>

            <aside class="h-fit rounded-[2rem] bg-sand p-6 lg:sticky lg:top-6" aria-label="Samenvatting en richtprijs">
                <p class="section-label">Jouw configuratie</p>
                <div class="mt-5 rounded-[1.5rem] bg-anthracite p-5 text-ivory" data-price-card>
                    <p class="text-xs font-semibold uppercase tracking-[0.16em] text-oak">Berekende richtprijs</p>
                    <p class="mt-3 font-brand text-4xl font-semibold tracking-[-0.045em]" data-configurator-price>€ {{ number_format($initialConfiguredPrice['estimated_price_cents'] / 100, 0, ',', '.') }}</p>
                    <p class="mt-1 text-xs text-ivory/65">incl. 21% btw, levering en plaatsing</p>
                    <p class="mt-4 border-t border-ivory/20 pt-4 text-xs leading-5 text-ivory/70" data-price-status>Direct aangepast aan je keuzes.</p>
                </div>
                <p class="sr-only" role="status" aria-live="polite" aria-atomic="true" data-configurator-announcement></p>
                <details class="mt-4 rounded-xl border border-taupe/60 bg-ivory px-4 py-3 text-sm" data-price-details>
                    <summary class="min-h-11 cursor-pointer py-2 font-semibold text-olive">Bekijk prijsopbouw</summary>
                    <dl class="grid gap-3 border-t border-taupe/40 py-4 text-xs">
                        <div class="flex justify-between gap-4"><dt>Vergelijkbare full-service marktprijs</dt><dd data-benchmark-price>€ {{ number_format($initialConfiguredPrice['benchmark_price_cents'] / 100, 0, ',', '.') }}</dd></div>
                        <div class="flex justify-between gap-4 text-olive"><dt>MAATATELIER voordeel (minstens 5%)</dt><dd data-price-discount>− € {{ number_format($initialConfiguredPrice['savings_cents'] / 100, 0, ',', '.') }}</dd></div>
                        <div class="flex justify-between gap-4 border-t border-taupe/40 pt-3 font-semibold"><dt>Jouw richtprijs</dt><dd data-price-total>€ {{ number_format($initialConfiguredPrice['estimated_price_cents'] / 100, 0, ',', '.') }}</dd></div>
                    </dl>
                    <p class="pb-2 text-xs leading-5 text-anthracite/65">Prijsboek {{ $initialConfiguredPrice['pricing_version'] }} · benchmark gecontroleerd op {{ $configuratorRules['benchmark_checked_at'] }}. We vergelijken alleen geplaatst maatwerk met een gelijkwaardige service.</p>
                </details>
                <dl class="mt-5 grid gap-4 text-sm">
                    <div class="border-b border-taupe/20 pb-4">
                        <dt class="text-anthracite/70">Project</dt>
                        <dd class="mt-1 font-medium" data-summary="project_type">Nog niet gekozen</dd>
                    </div>
                    <div class="border-b border-taupe/20 pb-4">
                        <dt class="text-anthracite/70">Stijl</dt>
                        <dd class="mt-1 font-medium" data-summary="style">Nog niet gekozen</dd>
                    </div>
                    <div class="border-b border-taupe/20 pb-4">
                        <dt class="text-anthracite/70">Uitvoering</dt>
                        <dd class="mt-1 font-medium" data-summary="configuration">2400 × 2500 × 600 mm · licht eiken</dd>
                    </div>
                    <div class="border-b border-taupe/20 pb-4">
                        <dt class="text-anthracite/70">Budget</dt>
                        <dd class="mt-1 font-medium" data-summary="budget">Nog niet gekozen</dd>
                    </div>
                    <div>
                        <dt class="text-anthracite/70">Bestanden</dt>
                        <dd class="mt-1 font-medium" data-summary="attachments">Geen bestanden</dd>
                    </div>
                </dl>
                <p class="mt-6 rounded-2xl bg-ivory/60 p-4 text-xs leading-5 text-anthracite/70">Dit is een direct berekende richtprijs. Na technische opmeting bevestigen we materiaal, uitvoerbaarheid en de definitieve prijs vóór je beslist.</p>
            </aside>
        </form>
    </section>
</x-layouts.app>
