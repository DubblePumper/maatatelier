@php($copy = trans('pages.cookies'))

<x-layouts.app :title="$copy['meta_title']" :description="$copy['meta_description']">
    <x-page-header :eyebrow="$copy['header']['eyebrow']" :title="$copy['header']['title']" :intro="$copy['header']['intro']" />

    <article class="prose-brand mx-auto max-w-3xl px-5 py-20 sm:px-8 lg:py-28">
        <h2>{{ $copy['sections']['storage']['title'] }}</h2>
        <p>{{ $copy['sections']['storage']['before_code'] }} <code>maatatelier_consent_v1</code>. {{ $copy['sections']['storage']['after_code'] }}</p>

        <h2>{{ $copy['sections']['analytics']['title'] }}</h2>
        <p>{{ $copy['sections']['analytics']['before_code'] }} <code>G-7HHM0CZN91</code> {{ $copy['sections']['analytics']['after_code'] }}</p>

        <h2>{{ $copy['sections']['cookies']['title'] }}</h2>
        <p>{{ $copy['sections']['cookies']['before_codes'] }} <code>_ga</code> {{ $copy['sections']['cookies']['between_codes'] }} <code>_ga_7HHM0CZN91</code> {{ $copy['sections']['cookies']['after_codes'] }}</p>

        <h2>{{ $copy['sections']['advertising']['title'] }}</h2>
        <p>{{ $copy['sections']['advertising']['before_code'] }} <code>analytics_storage</code> {{ $copy['sections']['advertising']['after_code'] }}</p>

        <h2>{{ $copy['sections']['change']['title'] }}</h2>
        <p>{{ $copy['sections']['change']['copy'] }}</p>

        <h2>{{ $copy['sections']['retention']['title'] }}</h2>
        <p>{{ $copy['sections']['retention']['before_email'] }} <a href="mailto:{{ config('maatatelier.contact_email') }}">{{ config('maatatelier.contact_email') }}</a>.</p>
    </article>
</x-layouts.app>
