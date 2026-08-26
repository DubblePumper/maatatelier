<x-layouts.app title="Offerte voor maatwerk aanvragen | MAATATELIER" description="Vraag vrijblijvend een offerte aan voor een maatkast, keuken of interieur. Teken je kast, geef globale maten door en upload maximaal 5 bestanden van 15 MB.">
    <section class="border-b border-taupe/40 bg-sand text-anthracite">
        <div class="mx-auto max-w-7xl px-5 py-16 sm:px-8 lg:px-10 lg:py-20">
            <p class="section-label">Ontwerp & offerte</p>
            <h1 class="mt-6 max-w-5xl font-brand text-5xl font-semibold leading-[1.02] tracking-[-0.055em] sm:text-7xl">Teken de basis. Wij maken het precies.</h1>
            <p class="mt-7 max-w-2xl border-l-2 border-olive pl-6 text-lg leading-8 text-anthracite/70">Stel je kast eenvoudig samen, upload foto's van je ruimte en ontvang daarna een persoonlijke offerte.</p>
        </div>
    </section>

    <section class="mx-auto max-w-[94rem] px-5 py-16 sm:px-8 lg:px-10 lg:py-24">
        @if ($errors->any())
            <div class="mb-8 rounded-2xl border-2 border-oak bg-ivory p-6 text-anthracite" role="alert" tabindex="-1" data-form-errors>
                <h2 class="font-brand font-semibold">We kunnen je aanvraag nog niet versturen.</h2>
                <p class="mt-2 text-sm">Controleer de gemarkeerde velden. Je ingevulde gegevens zijn bewaard.</p>
            </div>
        @endif

        <form action="{{ route('quote_requests.store') }}" method="POST" enctype="multipart/form-data" class="grid gap-8 lg:grid-cols-[1fr_20rem]" data-quote-wizard>
            @csrf

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

                <div class="grid gap-6">
                    <fieldset class="wizard-panel" data-wizard-step="1">
                        <legend class="wizard-legend">Wat wil je laten maken?</legend>
                        <p class="wizard-help">Kies wat het dichtst in de buurt komt. Je kunt later extra uitleg geven.</p>
                        <div class="mt-7 grid gap-3 sm:grid-cols-2">
                            @foreach ([
                                'maatkast' => 'Maatkast',
                                'dressing' => 'Dressing',
                                'keuken' => 'Keuken',
                                'tv-meubel' => 'TV-meubel',
                                'bureau' => 'Bureau',
                                'wandmeubel' => 'Wandmeubel',
                                'bijkeuken' => 'Bijkeuken',
                                'ander-maatwerk' => 'Ander maatwerk',
                            ] as $value => $label)
                                <label class="choice-card">
                                    <input class="peer sr-only" type="radio" name="project_type" value="{{ $value }}" @checked(old('project_type') === $value) @if ($errors->has('project_type')) aria-invalid="true" aria-describedby="project-type-error" @endif required>
                                    <span class="choice-card-content">{{ $label }}</span>
                                </label>
                            @endforeach
                        </div>
                        @error('project_type')<p class="form-error" id="project-type-error">{{ $message }}</p>@enderror
                    </fieldset>

                    <fieldset class="wizard-panel" data-wizard-step="2" id="kast-ontwerper">
                        <legend class="wizard-legend">Welke afmetingen en functies ken je al?</legend>
                        <p class="wizard-help">Globale maten in millimeter zijn voldoende. Laat een veld leeg als je het nog niet weet.</p>
                        <div class="mt-7 grid gap-5 sm:grid-cols-3">
                            @foreach ([['width_mm', 'Breedte', '2400'], ['height_mm', 'Hoogte', '2500'], ['depth_mm', 'Diepte', '600']] as [$name, $label, $placeholder])
                                <div>
                                    <label class="form-label" for="{{ $name }}">{{ $label }} <span class="font-normal text-anthracite/70">(mm)</span></label>
                                    <div class="relative">
                                        <input class="form-input pr-14" id="{{ $name }}" name="{{ $name }}" type="number" min="100" inputmode="numeric" value="{{ old($name) }}" placeholder="{{ $placeholder }}" @error($name) aria-invalid="true" aria-describedby="{{ $name }}-error" @enderror>
                                        <span class="pointer-events-none absolute right-4 top-1/2 translate-y-[-0.2rem] text-xs font-semibold text-anthracite/70" aria-hidden="true">mm</span>
                                    </div>
                                    @error($name)<p class="form-error" id="{{ $name }}-error">{{ $message }}</p>@enderror
                                </div>
                            @endforeach
                        </div>
                        <input type="hidden" name="dimensions_are_approximate" value="0">
                        <label class="check-row mt-5 flex min-h-12 cursor-pointer items-center gap-3 rounded-xl border border-transparent px-3 text-sm transition-colors hover:bg-sand/45">
                            <input class="form-checkbox" type="checkbox" name="dimensions_are_approximate" value="1" @checked(old('dimensions_are_approximate', '1') === '1')>
                            Deze afmetingen zijn ongeveer
                        </label>
                        <div class="mt-9 grid gap-7 rounded-[1.5rem] bg-sand p-5 sm:p-7 lg:grid-cols-[0.8fr_1.2fr]" data-closet-designer>
                            <div>
                                <p class="section-label">Jouw kastbeeld</p>
                                <div class="mt-5">
                                    <label class="form-label" for="layout_columns">Aantal kastmodules</label>
                                    <select class="form-input" id="layout_columns" name="layout_columns" @error('layout_columns') aria-invalid="true" aria-describedby="layout-columns-error" @enderror>
                                        @foreach ([1, 2, 3, 4, 5, 6] as $columns)
                                            <option value="{{ $columns }}" @selected((int) old('layout_columns', 3) === $columns)>{{ $columns }} {{ $columns === 1 ? 'module' : 'modules' }}</option>
                                        @endforeach
                                    </select>
                                    @error('layout_columns')<p class="form-error" id="layout-columns-error">{{ $message }}</p>@enderror
                                </div>
                                <div class="mt-5">
                                    <label class="form-label" for="finish">Afwerking</label>
                                    <select class="form-input" id="finish" name="finish" @error('finish') aria-invalid="true" aria-describedby="finish-error" @enderror>
                                        @foreach (['licht-eiken' => 'Licht eiken', 'naturel-eiken' => 'Naturel eiken', 'olijfbrons' => 'Olijfbrons', 'ivoor' => 'Ivoor'] as $value => $label)
                                            <option value="{{ $value }}" @selected(old('finish', 'licht-eiken') === $value)>{{ $label }}</option>
                                        @endforeach
                                    </select>
                                    @error('finish')<p class="form-error" id="finish-error">{{ $message }}</p>@enderror
                                </div>
                            </div>
                            <div>
                                <div class="cabinet-preview flex h-72 gap-1.5 rounded-xl border-8 border-taupe p-2" data-cabinet-preview data-finish="{{ old('finish', 'licht-eiken') }}" aria-hidden="true">
                                    @foreach ([1, 2, 3, 4, 5, 6] as $module)
                                        <div class="cabinet-module relative min-w-0 flex-1 border border-anthracite/25 p-1.5" data-cabinet-module>
                                            <span class="mt-8 block h-px bg-anthracite/25"></span>
                                            <span class="mt-16 block h-px bg-anthracite/25"></span>
                                            <span class="absolute right-2 top-1/2 size-1.5 rounded-full bg-anthracite/55"></span>
                                        </div>
                                    @endforeach
                                </div>
                                <p class="mt-3 text-center text-xs text-anthracite/65" data-cabinet-description aria-live="polite">Kast met 3 modules in licht eiken.</p>
                            </div>
                        </div>
                        <div class="mt-9">
                            <span class="form-label">Welke functies zijn belangrijk?</span>
                            <div class="mt-3 grid gap-3 sm:grid-cols-2">
                                @foreach (['legplanken' => 'Legplanken', 'laden' => 'Laden', 'kledingroede' => 'Kledingroede', 'open-vakken' => 'Open vakken', 'deuren' => 'Deuren', 'verlichting' => 'Verlichting', 'schoenen' => 'Schoenenopberging', 'werkblad' => 'Werkblad', 'toestellen' => 'Inbouwtoestellen', 'kabelbeheer' => 'Kabelbeheer'] as $value => $label)
                                    <label class="check-row flex min-h-12 cursor-pointer items-center gap-3 rounded-xl border border-taupe/50 bg-ivory px-4 py-3 text-sm transition-colors hover:border-olive hover:bg-sand/45">
                                        <input class="form-checkbox" type="checkbox" name="features[]" value="{{ $value }}" @checked(in_array($value, old('features', []), true))>
                                        {{ $label }}
                                    </label>
                                @endforeach
                            </div>
                            @error('features')<p class="form-error">{{ $message }}</p>@enderror
                            @error('features.*')<p class="form-error">{{ $message }}</p>@enderror
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

            <aside class="h-fit rounded-[2rem] bg-sand p-6 lg:sticky lg:top-6" aria-label="Samenvatting">
                <p class="section-label">Jouw aanvraag</p>
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
                        <dt class="text-anthracite/70">Kastbeeld</dt>
                        <dd class="mt-1 font-medium" data-summary="finish">Licht eiken</dd>
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
                <p class="mt-6 rounded-2xl bg-ivory/60 p-4 text-xs leading-5 text-anthracite/70">Je aanvraag is vrijblijvend. Een definitieve prijs volgt pas na persoonlijke beoordeling.</p>
            </aside>
        </form>
    </section>
</x-layouts.app>
