@php
    $seoTitle = $location->meta_title ?? '';
    $seoDescription = $location->meta_description ?? '';
    $seoKeywords = $location->meta_keywords ?? '';
    $seoName = $location->name ?? '';
    $previewTitle = $seoTitle !== '' ? $seoTitle : (($seoName !== '' ? $seoName : 'Location').' | Fantastic Stays');
    $previewDescription = $seoDescription !== '' ? $seoDescription : 'Location description will appear here in Google search results.';
    $previewSlug = $location->slug ?? 'location-name';
@endphp

<div class="border rounded-3 p-3 mb-3" data-location-seo>
    <div class="d-flex align-items-center justify-content-between mb-2">
        <h6 class="fw-bold mb-0" style="color: var(--fs-emerald);">
            <i class="bi bi-search me-1 text-primary"></i> SEO
        </h6>
        <span class="badge bg-light text-secondary border" style="font-size:0.65rem;">Search</span>
    </div>
    <p class="text-muted small mb-3">These fields control the title, description, and keywords shown for this location in Google.</p>

    <div class="mb-3">
        <div class="d-flex justify-content-between align-items-center mb-1">
            <label class="form-label fw-semibold small mb-0">Meta Title</label>
            <small class="text-muted" data-seo="title-count">{{ strlen($seoTitle) }} / 60 chars</small>
        </div>
        <input type="text" name="meta_title" class="form-control" data-seo="title"
               placeholder="e.g. Assagao Villas | Fantastic Stays"
               value="{{ $seoTitle }}" maxlength="70">
        <div class="form-text">Recommended 50–60 characters.</div>
    </div>

    <div class="mb-3">
        <div class="d-flex justify-content-between align-items-center mb-1">
            <label class="form-label fw-semibold small mb-0">Meta Description</label>
            <small class="text-muted" data-seo="desc-count">{{ strlen($seoDescription) }} / 160 chars</small>
        </div>
        <textarea name="meta_description" rows="3" class="form-control" data-seo="description"
                  placeholder="Luxury villas in Assagao, North Goa, with private pools and concierge."
                  maxlength="200">{{ $seoDescription }}</textarea>
        <div class="form-text">Recommended 140–160 characters.</div>
    </div>

    <div class="mb-3">
        <label class="form-label fw-semibold small">Meta Keywords</label>
        <input type="text" name="meta_keywords" class="form-control"
               placeholder="assagao villas, luxury villas north goa, private pool villa"
               value="{{ $seoKeywords }}">
        <div class="form-text">Comma-separated search tags.</div>
    </div>

    <div class="p-3 rounded-3 border" style="background:#f8f9fa;">
        <small class="text-uppercase fw-bold text-muted d-block mb-2" style="font-size:0.68rem; letter-spacing:0.8px;">
            <i class="bi bi-google me-1"></i> Google Snippet Preview
        </small>
        <div class="small text-muted font-monospace mb-1" style="font-size:0.74rem;">
            https://fantasticstays.com/locations/<span data-seo="preview-slug">{{ $previewSlug }}</span>
        </div>
        <div class="fw-semibold mb-1 text-truncate" data-seo="preview-title" style="color:#1a0dab;">{{ $previewTitle }}</div>
        <div class="text-secondary small" data-seo="preview-desc" style="font-size:0.8rem; line-height:1.4;">{{ $previewDescription }}</div>
    </div>
</div>
