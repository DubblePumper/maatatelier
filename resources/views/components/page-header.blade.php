@props(['eyebrow', 'title', 'intro'])

<section {{ $attributes->merge(['class' => 'border-b border-taupe/40 bg-sand']) }}>
    <div class="mx-auto max-w-7xl px-5 py-16 sm:px-8 sm:py-20 lg:px-10 lg:py-24">
        <p class="section-label">{{ $eyebrow }}</p>
        <h1 class="mt-5 max-w-5xl font-brand text-5xl font-semibold leading-[1.02] tracking-[-0.05em] text-anthracite sm:text-7xl">{{ $title }}</h1>
        <p class="mt-6 max-w-2xl border-l-2 border-olive pl-5 text-base leading-7 text-anthracite/70 sm:text-lg">{{ $intro }}</p>
    </div>
</section>
