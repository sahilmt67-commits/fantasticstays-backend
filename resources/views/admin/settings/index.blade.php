@extends('layouts.admin')

@section('title', 'Site Settings')

@section('content')
@php
    $tab = in_array($tab ?? 'general', ['general', 'experiences', 'about', 'inspiration', 'faqs', 'listing', 'services'], true)
        ? $tab
        : 'general';
    $tabs = [
        'general' => 'General',
        'listing' => 'Category List Page',
        'services' => 'Services Pages',
        'experiences' => 'Goa Experiences',
        'about' => 'About Company',
        'inspiration' => 'Travel Inspiration',
        'faqs' => 'FAQs',
    ];
@endphp

<div class="d-flex align-items-center justify-content-between mb-3">
    <div>
        <h2 class="fw-bold mb-1" style="color: var(--fs-emerald);">Site Settings</h2>
        <p class="text-muted mb-0 small">Contact details, and the homepage sections shown below the guest reviews.</p>
    </div>
</div>

<ul class="nav nav-tabs mb-4">
    @foreach($tabs as $key => $label)
        <li class="nav-item">
            <a class="nav-link {{ $tab === $key ? 'active' : '' }}" href="{{ route('admin.settings.index', ['tab' => $key]) }}">{{ $label }}</a>
        </li>
    @endforeach
</ul>

@if($tab === 'general')
    @include('admin.settings.tabs.general')
@elseif($tab === 'experiences')
    @include('admin.settings.tabs.experiences')
@elseif($tab === 'about')
    @include('admin.settings.tabs.about')
@elseif($tab === 'inspiration')
    @include('admin.settings.tabs.inspiration')
@elseif($tab === 'listing')
    @include('admin.settings.tabs.listing')
@elseif($tab === 'services')
    @include('admin.settings.tabs.services')
@else
    @include('admin.settings.tabs.faqs')
@endif
@endsection
