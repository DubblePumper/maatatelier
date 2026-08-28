@php($copy = trans('pages.inspiratie'))

<x-layouts.app :title="$copy['meta_title']" :description="$copy['meta_description']">
    <x-page-header :eyebrow="$copy['header']['eyebrow']" :title="$copy['header']['title']" :intro="$copy['header']['intro']" />

    <section class="mx-auto max-w-[94rem] px-5 py-24 sm:px-8 lg:px-10 lg:py-32">
        <figure class="relative overflow-hidden rounded-t-[10rem] rounded-br-[2.5rem] bg-anthracite sm:rounded-t-[16rem]">
            <img src="{{ asset('images/hero-interior-v2.webp') }}" width="1536" height="1024" alt="{{ $copy['image_alt'] }}" class="aspect-[4/5] w-full object-cover sm:aspect-[16/9]" fetchpriority="high" decoding="async">
            <figcaption class="absolute bottom-5 left-5 max-w-sm rounded-2xl bg-anthracite/85 p-5 text-ivory backdrop-blur-md sm:bottom-8 sm:left-8">
                <p class="font-brand text-[0.62rem] font-semibold uppercase tracking-[0.18em] text-oak">{{ $copy['concept_label'] }}</p>
                <p class="mt-2 text-sm leading-6 text-ivory/70">{{ $copy['concept_copy'] }}</p>
            </figcaption>
        </figure>
    </section>

    <section class="bg-anthracite py-24 text-ivory lg:py-32">
        <div class="mx-auto max-w-[94rem] px-5 sm:px-8 lg:px-10">
            <div class="grid gap-12 lg:grid-cols-[0.55fr_1.45fr]">
                <p class="font-brand text-[0.68rem] font-semibold uppercase tracking-[0.24em] text-oak">{{ $copy['ingredients']['label'] }}</p>
                <h2 class="max-w-5xl font-brand text-5xl font-semibold leading-[0.93] tracking-[-0.055em] sm:text-7xl">{{ $copy['ingredients']['title_before'] }} <span class="display-italic font-normal text-oak">{{ $copy['ingredients']['title_emphasis'] }}</span></h2>
            </div>
            <div class="mt-16 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                @foreach ($copy['ingredients']['items'] as $ingredient)
                    <article class="{{ $ingredient['classes'] }} flex min-h-64 flex-col justify-between rounded-[2.5rem] p-7">
                        <span class="size-10 rounded-full border border-current/30" aria-hidden="true"></span>
                        <div>
                            <h3 class="font-brand text-2xl font-semibold">{{ $ingredient['title'] }}</h3>
                            <p class="mt-2 text-sm">{{ $ingredient['copy'] }}</p>
                        </div>
                    </article>
                @endforeach
            </div>
        </div>
    </section>

    <section class="mx-auto max-w-[94rem] px-5 py-24 sm:px-8 lg:px-10 lg:py-32">
        <div class="grid gap-10 md:grid-cols-3">
            @foreach ($copy['principles'] as $principle)
                <article class="border-t border-anthracite/25 pt-6">
                    <span class="font-brand text-xs font-semibold text-olive">{{ $principle['number'] }}</span>
                    <h2 class="mt-10 font-brand text-3xl font-semibold tracking-[-0.035em]">{{ $principle['title'] }}</h2>
                    <p class="mt-4 max-w-sm leading-7 text-anthracite/70">{{ $principle['copy'] }}</p>
                </article>
            @endforeach
        </div>
    </section>
</x-layouts.app>
