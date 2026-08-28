@php($copy = trans('pages.privacy'))

<x-layouts.app :title="$copy['meta_title']" :description="$copy['meta_description']">
    <x-page-header :eyebrow="$copy['header']['eyebrow']" :title="$copy['header']['title']" :intro="$copy['header']['intro']" />

    <article class="prose-brand mx-auto max-w-3xl px-5 py-20 sm:px-8 lg:py-28">
        <h2>{{ $copy['sections']['data']['title'] }}</h2>
        <p>{{ $copy['sections']['data']['copy'] }}</p>

        <h2>{{ $copy['sections']['purpose']['title'] }}</h2>
        <p>{{ $copy['sections']['purpose']['copy'] }}</p>

        <h2>{{ $copy['sections']['files']['title'] }}</h2>
        <p>{{ trans('pages.privacy.sections.files.copy', ['days' => config('maatatelier.attachment_link_lifetime_days')]) }}</p>

        <h2>{{ $copy['sections']['retention']['title'] }}</h2>
        <p>{{ $copy['sections']['retention']['copy'] }}</p>

        <h2>{{ $copy['sections']['sharing']['title'] }}</h2>
        <p>{{ $copy['sections']['sharing']['copy'] }}</p>

        <h2>{{ $copy['sections']['analytics']['title'] }}</h2>
        <p>{{ $copy['sections']['analytics']['before_link'] }} <a href="{{ \App\Support\LocalizedRoute::url('cookies') }}">{{ $copy['sections']['analytics']['link'] }}</a>.</p>

        <h2>{{ $copy['sections']['rights']['title'] }}</h2>
        <p>{{ $copy['sections']['rights']['before_email'] }} <a href="mailto:{{ config('maatatelier.contact_email') }}">{{ config('maatatelier.contact_email') }}</a> {{ $copy['sections']['rights']['after_email'] }}</p>
    </article>
</x-layouts.app>
