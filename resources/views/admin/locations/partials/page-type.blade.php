@php
    $pageType = $location->page_type ?: 'listing';
@endphp

<div class="mb-3">
    <label class="form-label small fw-semibold">Open this location as</label>
    <div class="form-check">
        <input class="form-check-input" type="radio" name="page_type" id="page_type_listing_{{ $suffix }}" value="listing" {{ $pageType === 'listing' ? 'checked' : '' }}>
        <label class="form-check-label small" for="page_type_listing_{{ $suffix }}">Villa listing</label>
    </div>
    <div class="form-check">
        <input class="form-check-input" type="radio" name="page_type" id="page_type_luxury_{{ $suffix }}" value="luxury_rental" {{ $pageType === 'luxury_rental' ? 'checked' : '' }}>
        <label class="form-check-label small" for="page_type_luxury_{{ $suffix }}">Luxury rental page</label>
    </div>
    <div class="form-text">Off keeps <span class="font-monospace">/villas?location={{ $location->name ?: 'Assagao' }}</span>. On opens <span class="font-monospace">/luxury-rental/villas-in-{{ $location->slug ?: 'dubai' }}</span> with this location’s heading, description, and cover image.</div>
</div>
