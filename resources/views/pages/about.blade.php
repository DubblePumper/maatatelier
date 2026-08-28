@php($copy = trans('pages.about'))

<x-layouts.app :title="$copy['meta_title']" :description="$copy['meta_description']">
    <x-page-header :eyebrow="$copy['header']['eyebrow']" :title="$copy['header']['title']" :intro="$copy['header']['intro']" />

    <section class="mx-auto grid max-w-[94rem] gap-12 px-5 py-24 sm:px-8 lg:grid-cols-2 lg:items-center lg:px-10 lg:py-32">
        <figure class="overflow-hidden rounded-t-[9rem] rounded-br-[2.5rem] bg-anthracite">
            <img src="{{ asset('images/hero-interior-v2.webp') }}" width="1536" height="1024" alt="{{ $copy['image_alt'] }}" class="aspect-[4/5] w-full object-cover object-[58%_center]" fetchpriority="high" decoding="async">
            <figcaption class="bg-anthracite px-6 py-4 font-brand text-[0.62rem] font-semibold uppercase tracking-[0.18em] text-oak">{{ $copy['image_caption'] }}</figcaption>
        </figure>
        <div>
            <p class="section-label">{{ $copy['story']['label'] }}</p>
            <h2 class="section-title">{{ $copy['story']['title'] }}</h2>
            <p class="mt-7 text-lg leading-8 text-anthracite/65">{{ $copy['story']['copy'] }}</p>
        </div>
    </section>

    <section class="bg-sand py-24 text-anthracite">
        <div class="mx-auto max-w-[94rem] px-5 sm:px-8 lg:px-10">
            <div class="grid gap-px overflow-hidden rounded-[2.5rem] bg-taupe/50 sm:grid-cols-2 lg:grid-cols-5">
                @foreach ($copy['values'] as $value)
                    <article class="min-h-52 bg-ivory p-6">
                        <h2 class="font-brand text-base font-semibold">{{ $value['title'] }}</h2>
                        <p class="mt-8 text-sm leading-6 text-anthracite/70">{{ $value['copy'] }}</p>
                    </article>
                @endforeach
            </div>
        </div>
    </section>
</x-layouts.app>
