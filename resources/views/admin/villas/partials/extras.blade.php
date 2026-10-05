@php
    $amenityGroups = isset($villa) && is_array($villa->amenity_groups) ? $villa->amenity_groups : [];
    $amenityLines = [];
    foreach ($amenityGroups as $group) {
        $amenityLines[] = $group['title'] ?? '';
        foreach ($group['items'] ?? [] as $item) {
            $amenityLines[] = $item;
        }
        $amenityLines[] = '';
    }
    $amenityRaw = old('amenity_groups_raw', trim(implode("\n", $amenityLines)));

    $serviceRows = isset($villa) && is_array($villa->services) ? $villa->services : [];
    $serviceLines = [];
    foreach ($serviceRows as $service) {
        $status = !empty($service['included']) ? 'included' : 'request';
        $serviceLines[] = $status.' | '.($service['name'] ?? '');
    }
    $serviceRaw = old('services_raw', implode("\n", $serviceLines));
@endphp

<div class="card card-custom p-4 mb-4">
    <h5 class="fw-bold mb-1" style="color: var(--fs-emerald);">Amenities & Services</h5>
    <p class="text-muted small mb-3">Shown on the villa page, just above The Location. Leave both boxes empty to hide the section.</p>

    <div class="row g-3 mb-3">
        <div class="col-md-4">
            <label class="form-label fw-semibold small">Amenities label</label>
            <input type="text" name="amenity_eyebrow" class="form-control" value="{{ old('amenity_eyebrow', isset($villa) ? $villa->amenity_eyebrow : 'Amenities') }}">
        </div>
        <div class="col-md-8">
            <label class="form-label fw-semibold small">Amenities heading</label>
            <input type="text" name="amenity_heading" class="form-control" value="{{ old('amenity_heading', isset($villa) ? $villa->amenity_heading : 'Thoughtfully appointed throughout') }}">
        </div>
    </div>
    <div class="mb-4">
        <label class="form-label fw-semibold small">Amenity groups</label>
        <textarea name="amenity_groups_raw" rows="12" class="form-control font-monospace small" placeholder="Popular Amenities&#10;Private Swimming Pool&#10;Air Conditioning&#10;&#10;Kitchen & Dining&#10;Fully Equipped Kitchen">{{ $amenityRaw }}</textarea>
        <small class="text-muted">First line of each block is the column title. Next lines are the items. Separate columns with a blank line.</small>
    </div>

    <div class="row g-3 mb-3">
        <div class="col-md-4">
            <label class="form-label fw-semibold small">Services label</label>
            <input type="text" name="services_eyebrow" class="form-control" value="{{ old('services_eyebrow', isset($villa) ? $villa->services_eyebrow : 'Private Dining & Services') }}">
        </div>
        <div class="col-md-8">
            <label class="form-label fw-semibold small">Services heading</label>
            <input type="text" name="services_heading" class="form-control" value="{{ old('services_heading', isset($villa) ? $villa->services_heading : 'Curated services, included or on request') }}">
        </div>
        <div class="col-12">
            <label class="form-label fw-semibold small">Services intro</label>
            <textarea name="services_intro" rows="2" class="form-control">{{ old('services_intro', isset($villa) ? $villa->services_intro : '') }}</textarea>
        </div>
    </div>
    <div>
        <label class="form-label fw-semibold small">Services list</label>
        <textarea name="services_raw" rows="8" class="form-control font-monospace small" placeholder="included | Local Concierge Support&#10;request | Personal Chef">{{ $serviceRaw }}</textarea>
        <small class="text-muted">One service per line: <code>included | Name</code> or <code>request | Name</code>.</small>
    </div>
</div>
