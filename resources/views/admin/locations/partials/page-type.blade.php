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
    <div class="form-text">Off opens <span class="font-monospace">/{{ $location->slug ?: 'north-goa' }}</span> and lists villas for this location. On opens <span class="font-monospace">/luxury-rental/villas-in-{{ $location->slug ?: 'north-goa' }}</span>.</div>
</div>
