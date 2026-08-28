@php($copy = trans('pages.werkwijze'))

<x-layouts.app :title="$copy['meta_title']" :description="$copy['meta_description']">
    <x-page-header :eyebrow="$copy['header']['eyebrow']" :title="$copy['header']['title']" :intro="$copy['header']['intro']" />

    <section class="mx-auto max-w-[94rem] px-5 py-24 sm:px-8 lg:px-10 lg:py-32">
        <ol class="border-t border-anthracite/20">
            @foreach ($copy['steps'] as $step)
                <li class="grid gap-5 border-b border-anthracite/20 py-9 sm:grid-cols-[7rem_0.8fr_1fr] sm:items-start lg:py-12">
                    <span class="font-brand text-xs font-semibold text-olive">{{ $step['number'] }}</span>
                    <h2 class="font-brand text-2xl font-semibold leading-tight tracking-[-0.03em] sm:text-3xl">{{ $step['title'] }}</h2>
                    <p class="max-w-xl leading-7 text-anthracite/70">{{ $step['copy'] }}</p>
                </li>
            @endforeach
        </ol>
    </section>

    <section class="bg-sand py-24 text-anthracite">
        <div class="mx-auto grid max-w-[94rem] gap-12 px-5 sm:px-8 lg:grid-cols-[0.8fr_1.2fr] lg:px-10">
            <div>
                <p class="section-label">{{ $copy['preparation']['label'] }}</p>
                <h2 class="section-title">{{ $copy['preparation']['title'] }}</h2>
            </div>
            <ul class="grid gap-px overflow-hidden rounded-[2.5rem] bg-taupe/50 sm:grid-cols-2">
                @foreach ($copy['preparation']['items'] as $item)
                    <li class="flex gap-3 bg-ivory p-6 text-sm leading-6 text-anthracite/70"><span class="mt-2 size-1.5 shrink-0 rounded-full bg-olive" aria-hidden="true"></span>{{ $item }}</li>
                @endforeach
            </ul>
        </div>
    </section>

    <section class="mx-auto max-w-5xl px-5 py-24 sm:px-8 lg:py-32">
        <p class="section-label">{{ $copy['faq']['label'] }}</p>
        <h2 class="section-title">{{ $copy['faq']['title'] }}</h2>
        <div class="mt-10 border-t border-anthracite/20">
            @foreach ($copy['faq']['items'] as $faq)
                <details class="group border-b border-anthracite/20 py-6">
                    <summary class="flex min-h-12 cursor-pointer list-none items-center justify-between gap-4 font-brand font-semibold marker:hidden">
                        {{ $faq['question'] }}<span class="text-3xl font-light text-olive transition-transform group-open:rotate-45" aria-hidden="true">+</span>
                    </summary>
                    <p class="mt-4 max-w-2xl leading-7 text-anthracite/70">{{ $faq['answer'] }}</p>
                </details>
            @endforeach
        </div>
    </section>
</x-layouts.app>
