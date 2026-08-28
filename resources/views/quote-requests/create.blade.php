@php($copy = trans('configurator'))
<x-layouts.app :title="$copy['meta_title']" :description="$copy['meta_description']">
    <section class="border-b border-taupe/40 bg-sand text-anthracite">
        <div class="mx-auto max-w-7xl px-5 py-16 sm:px-8 lg:px-10 lg:py-20">
            <p class="section-label">{{ $copy['hero']['eyebrow'] }}</p>
            <h1 class="mt-6 max-w-5xl font-brand text-5xl font-semibold leading-[1.02] tracking-[-0.055em] sm:text-7xl">{{ $copy['hero']['title'] }}</h1>
            <p class="mt-7 max-w-2xl border-l-2 border-olive pl-6 text-lg leading-8 text-anthracite/70">{{ $copy['hero']['intro'] }}</p>
        </div>
    </section>

    <section class="mx-auto max-w-[94rem] px-5 py-16 sm:px-8 lg:px-10 lg:py-24">
        @if ($errors->any())
            <div class="mb-8 rounded-2xl border-2 border-oak bg-ivory p-6 text-anthracite" role="alert" tabindex="-1" data-form-errors>
                <h2 class="font-brand font-semibold">{{ $copy['errors']['title'] }}</h2>
                <p class="mt-2 text-sm">{{ $copy['errors']['intro'] }}</p>
            </div>
        @endif

        <form action="{{ \App\Support\LocalizedRoute::url('quote_requests.store') }}" method="POST" enctype="multipart/form-data" class="grid gap-8 lg:grid-cols-[minmax(0,1fr)_22rem]" data-quote-wizard data-furniture-configurator data-configurator-rules="{{ json_encode($configuratorRules, JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) }}" data-configurator-translations="{{ json_encode($copy['javascript'], JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) }}" data-has-errors="{{ $errors->any() ? 'true' : 'false' }}">
            @csrf
            <input type="hidden" name="configured" value="1">

            <div class="min-w-0">
                <div class="mb-8" aria-label="{{ $copy['progress']['aria'] }}">
                    <div class="mb-3 flex items-center justify-between gap-4 text-xs font-medium text-anthracite/70">
                        <span data-progress-label>{{ $copy['progress']['initial'] }}</span>
                        <span>{{ $copy['progress']['time'] }}</span>
                    </div>
                    <div class="h-1.5 overflow-hidden rounded-full bg-sand" aria-hidden="true">
                        <div class="h-full w-1/5 rounded-full bg-olive transition-[width] duration-300 motion-reduce:transition-none" data-progress-bar></div>
                    </div>
                </div>

                <div class="mb-6 flex items-center justify-between gap-5 rounded-2xl bg-anthracite px-5 py-4 text-ivory lg:hidden" data-mobile-price-card>
                    <div>
                        <p class="text-[0.65rem] font-semibold uppercase tracking-[0.16em] text-oak">{{ $copy['price']['live'] }}</p>
                        <p class="mt-1 text-xs leading-5 text-ivory/70" data-price-status-mobile>{{ $copy['price']['included_short'] }}</p>
                    </div>
                    <p class="shrink-0 font-brand text-2xl font-semibold tracking-[-0.035em]" data-configurator-price-mobile>{{ \App\Support\LocalizedMoney::format($initialConfiguredPrice['estimated_price_cents']) }}</p>
                </div>

                <div class="grid gap-6">
                    <fieldset class="wizard-panel" data-wizard-step="1">
                        <legend class="wizard-legend">{{ $copy['step_one']['legend'] }}</legend>
                        <p class="wizard-help">{{ $copy['step_one']['help'] }}</p>
                        <div class="mt-7 grid gap-3 sm:grid-cols-2">
                            @foreach ($copy['step_one']['types'] as $value => $type)
                                <label class="choice-card">
                                    <input class="peer sr-only" type="radio" name="project_type" value="{{ $value }}" @checked(old('project_type', 'maatkast') === $value) @if ($errors->has('project_type')) aria-invalid="true" aria-describedby="project-type-error" @endif required>
                                    <span class="choice-card-content min-h-24 items-start">
                                        <span class="grid gap-1">
                                            <span data-choice-label>{{ $type['label'] }}</span>
                                            <span class="text-xs font-normal leading-5 text-anthracite/65">{{ $type['description'] }}</span>
                                            <span class="mt-1 text-[0.65rem] font-semibold uppercase tracking-[0.14em] text-olive">{{ $type['live'] ? $copy['step_one']['live_price'] : $copy['step_one']['personal_price'] }}</span>
                                        </span>
                                    </span>
                                </label>
                            @endforeach
                        </div>
                        @error('project_type')<p class="form-error" id="project-type-error">{{ $message }}</p>@enderror
                    </fieldset>

                    <fieldset class="wizard-panel" data-wizard-step="2" id="kast-ontwerper">
                        <legend class="wizard-legend">{{ $copy['step_two']['legend'] }}</legend>
                        <div class="mt-7 rounded-[1.75rem] border border-taupe/50 bg-sand p-6 sm:p-8" data-personal-project-panel hidden>
                            <span class="grid size-12 place-items-center rounded-full bg-olive font-brand text-lg font-semibold text-ivory" aria-hidden="true">MA</span>
                            <h2 class="mt-5 font-brand text-2xl font-semibold tracking-[-0.03em]">{{ $copy['step_two']['personal_title'] }}</h2>
                            <p class="mt-3 max-w-2xl text-sm leading-6 text-anthracite/70">{{ $copy['step_two']['personal_copy'] }}</p>
                            <p class="mt-5 text-xs font-semibold uppercase tracking-[0.14em] text-olive">{{ $copy['step_two']['personal_note'] }}</p>
                        </div>

                        <div data-live-configurator-panel>
                            <p class="wizard-help">{{ $copy['step_two']['help'] }}</p>

                            <div class="mt-8 grid gap-8 xl:grid-cols-[minmax(20rem,1.05fr)_minmax(19rem,0.95fr)] xl:items-start">
                            <div class="configurator-stage">
                                <div class="flex flex-wrap items-start justify-between gap-4">
                                    <div>
                                        <p class="section-label">{{ $copy['step_two']['preview_label'] }}</p>
                                        <p class="mt-2 text-sm text-anthracite/65">{{ $copy['step_two']['preview_view'] }}</p>
                                    </div>
                                    <span class="rounded-full border border-olive/40 bg-ivory px-3 py-2 text-[0.65rem] font-semibold uppercase tracking-[0.14em] text-olive">{{ $copy['step_two']['live'] }}</span>
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
                                <p class="mt-4 min-h-12 text-sm leading-6 text-anthracite/70" data-configurator-description>{{ $copy['step_two']['preview_default'] }}</p>
                            </div>

                            <div class="grid gap-5">
                                <fieldset class="configurator-group">
                                    <legend class="configurator-group-title"><span>01</span> {{ $copy['step_two']['dimensions'] }}</legend>
                                    <div class="mt-5 grid gap-5">
                                        @foreach ([
                                            ['width_mm', 600, 5000, 50, 2400],
                                            ['height_mm', 500, 3000, 50, 2500],
                                            ['depth_mm', 250, 800, 10, 600],
                                        ] as [$name, $min, $max, $step, $default])
                                            @php($label = $copy['step_two']['measurements'][$name])
                                            <div data-measurement-control>
                                                <div class="flex flex-col items-start gap-3 sm:flex-row sm:items-center sm:justify-between sm:gap-4">
                                                    <label class="form-label" for="{{ $name }}">{{ $label }}</label>
                                                    <div class="measurement-stepper">
                                                        <button type="button" data-step-down aria-label="{{ __('configurator.step_two.decrease_mm', ['label' => $label, 'step' => $step]) }}">−</button>
                                                        <div class="relative">
                                                            <input class="measurement-number" id="{{ $name }}" name="{{ $name }}" type="number" min="{{ $min }}" max="{{ $max }}" step="{{ $step }}" inputmode="numeric" value="{{ old($name, $default) }}" @error($name) aria-invalid="true" aria-describedby="{{ $name }}-error" @enderror required>
                                                            <span aria-hidden="true">mm</span>
                                                        </div>
                                                        <button type="button" data-step-up aria-label="{{ __('configurator.step_two.increase_mm', ['label' => $label, 'step' => $step]) }}">+</button>
                                                    </div>
                                                </div>
                                                <label class="sr-only" for="{{ $name }}-range">{{ __('configurator.step_two.set_mm', ['label' => $label]) }}</label>
                                                <input class="configurator-range mt-4" id="{{ $name }}-range" type="range" min="{{ $min }}" max="{{ $max }}" step="{{ $step }}" value="{{ old($name, $default) }}" data-range-for="{{ $name }}">
                                                @error($name)<p class="form-error" id="{{ $name }}-error">{{ $message }}</p>@enderror
                                            </div>
                                        @endforeach
                                    </div>
                                    <details class="mt-5 rounded-xl border border-taupe/50 bg-ivory px-4 py-3 text-sm">
                                        <summary class="min-h-11 cursor-pointer py-2 font-semibold text-olive">{{ $copy['step_two']['measure_help_title'] }}</summary>
                                        <p class="pb-2 leading-6 text-anthracite/70">{{ $copy['step_two']['measure_help_copy'] }}</p>
                                    </details>
                                </fieldset>

                                <fieldset class="configurator-group">
                                    <legend class="configurator-group-title"><span>02</span> {{ $copy['step_two']['layout'] }}</legend>
                                    <div class="mt-5 grid gap-5">
                                        <div>
                                            <label class="form-label" for="layout_columns">{{ $copy['step_two']['module_count'] }}</label>
                                            <div class="measurement-stepper mt-2 w-fit">
                                                <button type="button" data-counter-down="layout_columns" aria-label="{{ $copy['step_two']['module_less'] }}">−</button>
                                                <input class="counter-number" id="layout_columns" name="layout_columns" type="number" min="{{ $configuratorRules['modules']['min'] }}" max="{{ $configuratorRules['modules']['max'] }}" step="1" inputmode="numeric" value="{{ old('layout_columns', $configuratorRules['modules']['default']) }}" @error('layout_columns') aria-invalid="true" aria-describedby="layout-columns-error" @enderror required>
                                                <button type="button" data-counter-up="layout_columns" aria-label="{{ $copy['step_two']['module_more'] }}">+</button>
                                            </div>
                                            @error('layout_columns')<p class="form-error" id="layout-columns-error">{{ $message }}</p>@enderror
                                        </div>

                                        <fieldset>
                                            <legend class="form-label">{{ $copy['step_two']['front'] }}</legend>
                                            <div class="mt-3 grid grid-cols-3 gap-2">
                                                @foreach ($copy['step_two']['fronts'] as $value => $label)
                                                    <label class="mini-choice">
                                                        <input class="peer sr-only" type="radio" name="front_style" value="{{ $value }}" @checked(old('front_style', 'draaideuren') === $value) @if ($errors->has('front_style')) aria-invalid="true" aria-describedby="front-style-error" @endif required>
                                                        <span>{{ $label }}</span>
                                                    </label>
                                                @endforeach
                                            </div>
                                            @error('front_style')<p class="form-error" id="front-style-error">{{ $message }}</p>@enderror
                                        </fieldset>

                                        <fieldset>
                                            <legend class="form-label">{{ $copy['step_two']['interior'] }}</legend>
                                            <div class="mt-3 grid gap-2">
                                                @foreach ($copy['step_two']['interiors'] as $value => $interior)
                                                    <label class="choice-card">
                                                        <input class="peer sr-only" type="radio" name="interior_level" value="{{ $value }}" @checked(old('interior_level', 'comfort') === $value) @if ($errors->has('interior_level')) aria-invalid="true" aria-describedby="interior-level-error" @endif required>
                                                        <span class="choice-card-content justify-between">
                                                            <span><strong class="block">{{ $interior['label'] }}</strong><small class="mt-1 block font-normal text-anthracite/65">{{ $interior['description'] }}</small></span>
                                                            <span class="text-olive" aria-hidden="true">✓</span>
                                                        </span>
                                                    </label>
                                                @endforeach
                                            </div>
                                            @error('interior_level')<p class="form-error" id="interior-level-error">{{ $message }}</p>@enderror
                                        </fieldset>

                                        <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-1 2xl:grid-cols-2">
                                            @foreach ([
                                                ['drawer_count', $configuratorRules['extras']['laden']['min'], $configuratorRules['extras']['laden']['max']],
                                                ['rail_count', $configuratorRules['extras']['roedes']['min'], $configuratorRules['extras']['roedes']['max']],
                                            ] as [$name, $min, $max])
                                                @php($counter = $copy['step_two']['counters'][$name])
                                                <div>
                                                    <label class="form-label" for="{{ $name }}">{{ $counter['label'] }}</label>
                                                    <div class="measurement-stepper mt-2 w-fit">
                                                        <button type="button" data-counter-down="{{ $name }}" aria-label="{{ $counter['less'] }}">−</button>
                                                        <input class="counter-number" id="{{ $name }}" name="{{ $name }}" type="number" min="{{ $min }}" max="{{ $max }}" step="1" inputmode="numeric" value="{{ old($name, $name === 'drawer_count' ? 2 : 1) }}" @error($name) aria-invalid="true" aria-describedby="{{ str($name)->replace('_', '-') }}-error" @enderror required>
                                                        <button type="button" data-counter-up="{{ $name }}" aria-label="{{ $counter['more'] }}">+</button>
                                                    </div>
                                                    @error($name)<p class="form-error" id="{{ str($name)->replace('_', '-') }}-error">{{ $message }}</p>@enderror
                                                </div>
                                            @endforeach
                                        </div>

                                        <input type="hidden" name="led_lighting" value="0">
                                        <label class="check-row flex min-h-14 cursor-pointer items-center justify-between gap-3 rounded-xl border border-taupe/50 bg-ivory px-4 py-3 text-sm transition-colors hover:border-olive hover:bg-sand/45">
                                            <span class="flex items-center gap-3"><input class="form-checkbox" type="checkbox" name="led_lighting" value="1" @checked(old('led_lighting') === '1') @error('led_lighting') aria-invalid="true" aria-describedby="led-lighting-error" @enderror> {{ $copy['step_two']['led'] }}</span>
                                            <span class="text-xs font-semibold text-olive" data-option-price="led_lighting"></span>
                                        </label>
                                        @error('led_lighting')<p class="form-error" id="led-lighting-error">{{ $message }}</p>@enderror
                                    </div>
                                </fieldset>
                            </div>
                        </div>

                        <fieldset class="configurator-group mt-8">
                            <legend class="configurator-group-title"><span>03</span> {{ $copy['step_two']['material'] }}</legend>
                            <div class="mt-5 grid gap-3 sm:grid-cols-2 lg:grid-cols-5">
                                @foreach ($copy['step_two']['materials'] as $value => $material)
                                    <label class="material-choice">
                                        <input class="peer sr-only" type="radio" name="finish" value="{{ $value }}" @checked(old('finish', 'licht-eiken') === $value) @if ($errors->has('finish')) aria-invalid="true" aria-describedby="finish-error" @endif required>
                                        <span class="material-choice-content">
                                            <span class="material-swatch" style="--swatch: {{ $material['colour'] }}" aria-hidden="true"></span>
                                            <span class="font-semibold">{{ $material['label'] }}</span>
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
                            {{ $copy['step_two']['approximate'] }}
                            </label>
                        </div>
                    </fieldset>

                    <fieldset class="wizard-panel" data-wizard-step="3">
                        <legend class="wizard-legend">{{ $copy['step_three']['legend'] }}</legend>
                        <p class="wizard-help">{{ $copy['step_three']['help'] }}</p>
                        <div class="mt-7">
                            <span class="form-label">{{ $copy['step_three']['style'] }}</span>
                            <div class="mt-3 grid gap-3 sm:grid-cols-2">
                                @foreach ($copy['step_three']['styles'] as $value => $label)
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
                                <label class="form-label" for="budget">{{ $copy['step_three']['budget'] }}</label>
                                <select class="form-input" id="budget" name="budget" @error('budget') aria-invalid="true" aria-describedby="budget-error" @enderror required>
                                    <option value="">{{ $copy['step_three']['budget_placeholder'] }}</option>
                                    @foreach ($copy['step_three']['budgets'] as $value => $label)
                                        <option value="{{ $value }}" @selected(old('budget') === $value)>{{ $label }}</option>
                                    @endforeach
                                </select>
                                @error('budget')<p class="form-error" id="budget-error">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="form-label" for="timing">{{ $copy['step_three']['timing'] }}</label>
                                <select class="form-input" id="timing" name="timing" @error('timing') aria-invalid="true" aria-describedby="timing-error" @enderror required>
                                    <option value="">{{ $copy['step_three']['timing_placeholder'] }}</option>
                                    @foreach ($copy['step_three']['timings'] as $value => $label)
                                        <option value="{{ $value }}" @selected(old('timing') === $value)>{{ $label }}</option>
                                    @endforeach
                                </select>
                                @error('timing')<p class="form-error" id="timing-error">{{ $message }}</p>@enderror
                            </div>
                        </div>
                    </fieldset>

                    <fieldset class="wizard-panel" data-wizard-step="4">
                        <legend class="wizard-legend">{{ $copy['step_four']['legend'] }}</legend>
                        <p class="wizard-help">{{ $copy['step_four']['help'] }}</p>
                        <div class="mt-7">
                            <span class="form-label">{{ $copy['step_four']['label'] }} <span class="font-normal text-anthracite/70">{{ $copy['step_four']['optional'] }}</span></span>
                            <label class="upload-zone" for="attachments" data-upload-zone>
                                <input class="sr-only" id="attachments" name="attachments[]" type="file" accept=".jpg,.jpeg,.png,.webp,.pdf" multiple aria-describedby="attachments-help{{ $errors->has('attachments') || $errors->has('attachments.*') ? ' attachments-error' : '' }}" @if ($errors->has('attachments') || $errors->has('attachments.*')) aria-invalid="true" @endif>
                                <span class="grid size-14 place-items-center rounded-full bg-sand font-brand text-2xl font-semibold text-olive" aria-hidden="true">+</span>
                                <span class="mt-5 font-brand text-lg font-semibold">{{ $copy['step_four']['drop'] }}</span>
                                <span class="mt-2 text-sm leading-6 text-anthracite/70">{{ $copy['step_four']['choose_help'] }}</span>
                                <span class="mt-4 rounded-full border border-olive px-4 py-2 font-brand text-xs font-semibold text-olive">{{ $copy['step_four']['choose'] }}</span>
                            </label>
                            <div class="mt-4 hidden grid-cols-2 gap-3 sm:grid-cols-3" data-file-previews aria-live="polite"></div>
                            <p class="mt-3 text-xs text-anthracite/70" id="attachments-help" data-file-summary data-empty-text="{{ $copy['step_four']['empty'] }}">{{ $copy['step_four']['empty'] }}</p>
                            @if ($errors->has('attachments') || $errors->has('attachments.*'))
                                <p class="form-error" id="attachments-error">{{ $errors->first('attachments') ?: $errors->first('attachments.*') }}</p>
                            @endif
                        </div>
                        <div class="mt-7">
                            <label class="form-label" for="notes">{{ $copy['step_four']['notes'] }} <span class="font-normal text-anthracite/70">{{ $copy['step_four']['optional'] }}</span></label>
                            <textarea class="form-input min-h-36" id="notes" name="notes" maxlength="2000" placeholder="{{ $copy['step_four']['notes_placeholder'] }}" @error('notes') aria-invalid="true" aria-describedby="notes-error" @enderror>{{ old('notes') }}</textarea>
                            @error('notes')<p class="form-error" id="notes-error">{{ $message }}</p>@enderror
                        </div>
                    </fieldset>

                    <fieldset class="wizard-panel" data-wizard-step="5">
                        <legend class="wizard-legend">{{ $copy['step_five']['legend'] }}</legend>
                        <p class="wizard-help">{{ $copy['step_five']['help'] }}</p>
                        <div class="mt-7 grid gap-5 sm:grid-cols-2">
                            <div class="sm:col-span-2">
                                <label class="form-label" for="name">{{ $copy['step_five']['name'] }}</label>
                                <input class="form-input" id="name" name="name" type="text" autocomplete="name" value="{{ old('name') }}" maxlength="100" @error('name') aria-invalid="true" aria-describedby="name-error" @enderror required>
                                @error('name')<p class="form-error" id="name-error">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="form-label" for="email">{{ $copy['step_five']['email'] }}</label>
                                <input class="form-input" id="email" name="email" type="email" autocomplete="email" inputmode="email" value="{{ old('email') }}" maxlength="254" @error('email') aria-invalid="true" aria-describedby="email-error" @enderror required>
                                @error('email')<p class="form-error" id="email-error">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="form-label" for="phone">{{ $copy['step_five']['phone'] }}</label>
                                <input class="form-input" id="phone" name="phone" type="tel" autocomplete="tel" inputmode="tel" value="{{ old('phone') }}" maxlength="30" @error('phone') aria-invalid="true" aria-describedby="phone-error" @enderror required>
                                @error('phone')<p class="form-error" id="phone-error">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="form-label" for="postal_code">{{ $copy['step_five']['postal_code'] }}</label>
                                <input class="form-input" id="postal_code" name="postal_code" type="text" autocomplete="postal-code" inputmode="numeric" pattern="[0-9]{4}" value="{{ old('postal_code') }}" maxlength="4" @error('postal_code') aria-invalid="true" aria-describedby="postal-code-error" @enderror required>
                                @error('postal_code')<p class="form-error" id="postal-code-error">{{ $message }}</p>@enderror
                            </div>
                        </div>
                        <div class="hidden" aria-hidden="true">
                            <label for="website">{{ $copy['step_five']['honeypot'] }}</label>
                            <input id="website" name="website" type="text" tabindex="-1" autocomplete="off">
                        </div>
                        <label class="check-row mt-7 flex cursor-pointer items-start gap-3 rounded-2xl border border-transparent bg-sand/45 p-5 text-sm leading-6 transition-colors hover:border-taupe">
                            <input class="form-checkbox mt-0.5" type="checkbox" name="consent" value="1" @checked(old('consent')) @error('consent') aria-invalid="true" aria-describedby="consent-error" @enderror required>
                            <span>{!! __('configurator.step_five.consent', ['privacy' => '<a class="underline decoration-olive underline-offset-4 hover:text-olive" href="'.e(\App\Support\LocalizedRoute::url('privacy')).'">'.e($copy['step_five']['privacy']).'</a>']) !!}</span>
                        </label>
                        @error('consent')<p class="form-error" id="consent-error">{{ $message }}</p>@enderror
                    </fieldset>
                </div>

                <div class="mt-6 flex flex-col-reverse gap-3 sm:flex-row sm:justify-between" data-wizard-actions>
                    <button class="secondary-button hidden" type="button" data-wizard-back>{{ $copy['actions']['back'] }}</button>
                    <button class="primary-button hidden sm:ml-auto" type="button" data-wizard-next>{{ $copy['actions']['next'] }}</button>
                    <button class="primary-button sm:ml-auto" type="submit" data-wizard-submit>{{ $copy['actions']['submit'] }}</button>
                </div>
            </div>

            <aside class="h-fit rounded-[2rem] bg-sand p-6 lg:sticky lg:top-6" aria-label="{{ $copy['summary']['aria'] }}">
                <p class="section-label">{{ $copy['summary']['title'] }}</p>
                <div class="mt-5 rounded-[1.5rem] bg-anthracite p-5 text-ivory" data-price-card>
                    <p class="text-xs font-semibold uppercase tracking-[0.16em] text-oak">{{ $copy['price']['calculated'] }}</p>
                    <p class="mt-3 font-brand text-4xl font-semibold tracking-[-0.045em]" data-configurator-price>{{ \App\Support\LocalizedMoney::format($initialConfiguredPrice['estimated_price_cents']) }}</p>
                    <p class="mt-1 text-xs text-ivory/65">{{ $copy['price']['included'] }}</p>
                    <p class="mt-4 border-t border-ivory/20 pt-4 text-xs leading-5 text-ivory/70" data-price-status>{{ $copy['price']['status'] }}</p>
                </div>
                <p class="sr-only" role="status" aria-live="polite" aria-atomic="true" data-configurator-announcement></p>
                <details class="mt-4 rounded-xl border border-taupe/60 bg-ivory px-4 py-3 text-sm" data-price-details>
                    <summary class="min-h-11 cursor-pointer py-2 font-semibold text-olive">{{ $copy['price']['details'] }}</summary>
                    <dl class="grid gap-3 border-t border-taupe/40 py-4 text-xs">
                        <div class="flex justify-between gap-4"><dt>{{ $copy['price']['benchmark'] }}</dt><dd data-benchmark-price>{{ \App\Support\LocalizedMoney::format($initialConfiguredPrice['benchmark_price_cents']) }}</dd></div>
                        <div class="flex justify-between gap-4 text-olive"><dt>{{ $copy['price']['advantage'] }}</dt><dd data-price-discount>− {{ \App\Support\LocalizedMoney::format($initialConfiguredPrice['savings_cents']) }}</dd></div>
                        <div class="flex justify-between gap-4 border-t border-taupe/40 pt-3 font-semibold"><dt>{{ $copy['price']['your_price'] }}</dt><dd data-price-total>{{ \App\Support\LocalizedMoney::format($initialConfiguredPrice['estimated_price_cents']) }}</dd></div>
                    </dl>
                    <p class="pb-2 text-xs leading-5 text-anthracite/65">{{ __('configurator.price.book', ['version' => $initialConfiguredPrice['pricing_version'], 'date' => $copy['benchmark_checked_at']]) }}</p>
                </details>
                <dl class="mt-5 grid gap-4 text-sm">
                    <div class="border-b border-taupe/20 pb-4">
                        <dt class="text-anthracite/70">{{ $copy['summary']['project'] }}</dt>
                        <dd class="mt-1 font-medium" data-summary="project_type">{{ $copy['summary']['not_selected'] }}</dd>
                    </div>
                    <div class="border-b border-taupe/20 pb-4">
                        <dt class="text-anthracite/70">{{ $copy['summary']['style'] }}</dt>
                        <dd class="mt-1 font-medium" data-summary="style">{{ $copy['summary']['not_selected'] }}</dd>
                    </div>
                    <div class="border-b border-taupe/20 pb-4">
                        <dt class="text-anthracite/70">{{ $copy['summary']['configuration'] }}</dt>
                        <dd class="mt-1 font-medium" data-summary="configuration">{{ $copy['summary']['default_configuration'] }}</dd>
                    </div>
                    <div class="border-b border-taupe/20 pb-4">
                        <dt class="text-anthracite/70">{{ $copy['summary']['budget'] }}</dt>
                        <dd class="mt-1 font-medium" data-summary="budget">{{ $copy['summary']['not_selected'] }}</dd>
                    </div>
                    <div>
                        <dt class="text-anthracite/70">{{ $copy['summary']['files'] }}</dt>
                        <dd class="mt-1 font-medium" data-summary="attachments">{{ $copy['summary']['no_files'] }}</dd>
                    </div>
                </dl>
                <p class="mt-6 rounded-2xl bg-ivory/60 p-4 text-xs leading-5 text-anthracite/70">{{ $copy['price']['disclaimer'] }}</p>
            </aside>
        </form>
    </section>
</x-layouts.app>
