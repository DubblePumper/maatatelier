@php($copy = trans('pages.accessibility'))

<x-layouts.app :title="$copy['meta_title']" :description="$copy['meta_description']">
    <x-page-header :eyebrow="$copy['header']['eyebrow']" :title="$copy['header']['title']" :intro="$copy['header']['intro']" />

    <article class="prose-brand mx-auto max-w-3xl px-5 py-20 sm:px-8 lg:py-28">
        <h2>{{ $copy['sections']['support']['title'] }}</h2>
        <p>{{ $copy['sections']['support']['copy'] }}</p>

        <h2>{{ $copy['sections']['configurator']['title'] }}</h2>
        <p>{{ $copy['sections']['configurator']['copy'] }}</p>

        <h2>{{ $copy['sections']['report']['title'] }}</h2>
        <p>{{ $copy['sections']['report']['before_email'] }} <a href="mailto:{{ config('maatatelier.contact_email') }}">{{ config('maatatelier.contact_email') }}</a> {{ $copy['sections']['report']['after_email'] }}</p>

        <h2>{{ $copy['sections']['status']['title'] }}</h2>
        <p>{{ $copy['sections']['status']['copy'] }}</p>
    </article>
</x-layouts.app>
