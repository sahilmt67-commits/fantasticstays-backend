@extends('layouts.admin')

@section('title', 'Edit Villa - ' . $villa->name)

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h2 class="fw-bold mb-1" style="color: var(--fs-emerald);">Edit Luxury Villa</h2>
        <p class="text-muted mb-0 small">Updating <strong>{{ $villa->name }}</strong> (Slug: <code>{{ $villa->slug }}</code>)</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.villas.rooms', $villa->id) }}" class="btn btn-outline-primary">
            <i class="bi bi-layout-text-window me-1"></i> Manage Bedrooms ({{ $villa->rooms()->count() }})
        </a>
        <a href="{{ route('admin.villas.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> Back to Villas
        </a>
    </div>
</div>

<form action="{{ route('admin.villas.update', $villa->id) }}" method="POST" id="villa-form">
    @csrf
    @method('PUT')

    <div class="row g-4">
        <!-- ===== LEFT COLUMN ===== -->
        <div class="col-lg-8">
            <!-- Basic Information Card -->
            <div class="card card-custom p-4 mb-4">
                <h5 class="fw-bold mb-3" style="color: var(--fs-emerald);">Basic Information</h5>
                
                <div class="mb-3">
                    <label class="form-label fw-semibold small">Villa Name <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', $villa->name) }}" required>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-4">
                        <label class="form-label fw-semibold small">URL Slug <span class="text-danger">*</span></label>
                        <input type="text" name="slug" class="form-control font-monospace small" value="{{ old('slug', $villa->slug) }}" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold small">Property Type <span class="text-danger">*</span></label>
                        <select name="property_type" class="form-select" required>
                            <option value="luxe" {{ old('property_type', $villa->property_type) === 'luxe' ? 'selected' : '' }}>Luxe (Luxury)</option>
                            <option value="classic" {{ old('property_type', $villa->property_type) === 'classic' ? 'selected' : '' }}>Classic</option>
                            <option value="budgeted" {{ old('property_type', $villa->property_type) === 'budgeted' ? 'selected' : '' }}>Budgeted</option>
                            <option value="apartments" {{ old('property_type', $villa->property_type) === 'apartments' ? 'selected' : '' }}>Apartments</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold small">Listing Badge (Optional)</label>
                        <input type="text" name="badge" class="form-control" value="{{ old('badge', $villa->badge) }}">
                    </div>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold small">Destination Location</label>
                        <select name="location_id" class="form-select">
                            <option value="">Select Goa Village / Location</option>
                            @foreach($locations as $loc)
                                <option value="{{ $loc->id }}" {{ old('location_id', $villa->location_id) == $loc->id ? 'selected' : '' }}>
                                    {{ $loc->name }} ({{ $loc->region }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold small">Region <span class="text-danger">*</span></label>
                        <select name="region" class="form-select" required>
                            <option value="North Goa" {{ old('region', $villa->region) === 'North Goa' ? 'selected' : '' }}>North Goa</option>
                            <option value="South Goa" {{ old('region', $villa->region) === 'South Goa' ? 'selected' : '' }}>South Goa</option>
                        </select>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold small">Custom Location Name (Backup)</label>
                    <input type="text" name="location_name" class="form-control" value="{{ old('location_name', $villa->location_name) }}">
                </div>
            </div>

            <!-- Capacity, Pricing & Specs Card -->
            <div class="card card-custom p-4 mb-4">
                <h5 class="fw-bold mb-3" style="color: var(--fs-emerald);">Capacity & Pricing</h5>

                <div class="row g-3 mb-3">
                    <div class="col-sm-6 col-md-4">
                        <label class="form-label fw-semibold small">Price per Night (₹) <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text">₹</span>
                            <input type="number" step="0.01" name="price_per_night" class="form-control" value="{{ old('price_per_night', $villa->price_per_night) }}" required>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-4">
                        <label class="form-label fw-semibold small">Bedrooms <span class="text-danger">*</span></label>
                        <input type="number" name="bedrooms" class="form-control" value="{{ old('bedrooms', $villa->bedrooms) }}" required>
                    </div>
                    <div class="col-sm-6 col-md-4">
                        <label class="form-label fw-semibold small">Bathrooms <span class="text-danger">*</span></label>
                        <input type="number" name="bathrooms" class="form-control" value="{{ old('bathrooms', $villa->bathrooms) }}" required>
                    </div>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-sm-6 col-md-4">
                        <label class="form-label fw-semibold small">Max Guests Capacity <span class="text-danger">*</span></label>
                        <input type="number" name="guests" class="form-control" value="{{ old('guests', $villa->guests) }}" required>
                    </div>
                    <div class="col-sm-6 col-md-4">
                        <label class="form-label fw-semibold small">Beds Count <span class="text-danger">*</span></label>
                        <input type="number" name="beds" class="form-control" value="{{ old('beds', $villa->beds) }}" required>
                    </div>
                    <div class="col-sm-6 col-md-4">
                        <label class="form-label fw-semibold small">Rating (1.0 - 5.0)</label>
                        <input type="number" step="0.1" name="rating" class="form-control" value="{{ old('rating', $villa->rating) }}">
                    </div>
                </div>
            </div>

            <!-- Photos & Gallery Card -->
            <div class="card card-custom p-4 mb-4">
                <h5 class="fw-bold mb-3" style="color: var(--fs-emerald);">Photos &amp; Media</h5>

                {{-- ===== HERO IMAGE ===== --}}
                <div class="mb-4">
                    <label class="form-label fw-semibold small">Primary Card / Hero Image <span class="text-danger">*</span></label>
                    <p class="text-muted small mb-2">You can upload images up to 200 MB. Photos are saved as compressed WebP with good quality.</p>
                    <div class="d-flex gap-2 align-items-center mb-2">
                        <input type="text" name="image" id="hero_image_input"
                               class="form-control font-monospace small"
                               value="{{ old('image', $villa->image) }}"
                               required oninput="previewHeroImage(this.value)">
                        <label class="btn btn-outline-secondary btn-sm mb-0 text-nowrap" style="cursor:pointer;">
                            <i class="bi bi-folder2-open me-1"></i> Browse
                            <input type="file" id="hero_file_input" accept="image/*" class="d-none" onchange="uploadHeroImage(this)">
                        </label>
                    </div>
                    <div id="hero_upload_progress" class="d-none mb-2">
                        <div class="progress" style="height:4px;">
                            <div class="progress-bar progress-bar-striped progress-bar-animated bg-success w-100"></div>
                        </div>
                        <small class="text-muted">Uploading&hellip;</small>
                    </div>
                    <div class="mt-1">
                        <img id="hero_preview" src="{{ $villa->image }}" alt="Hero Preview"
                             class="rounded-3 shadow-sm border object-fit-cover"
                             style="max-height: 180px; width: auto; max-width: 100%;">
                    </div>
                </div>

                {{-- ===== GALLERY IMAGES ===== --}}
                @php
                    $galleryArr = is_array($villa->gallery) ? $villa->gallery : [];
                @endphp
                <div class="mb-2">
                    <label class="form-label fw-semibold small mb-2">Photo Gallery (up to 20 images)</label>
                    <small class="text-muted d-block mb-3">Select photos below. Use the trash button to remove one, then save the villa.</small>

                    <div class="mb-3 p-3 rounded-3 border" data-image-loader style="background:#f8faf9;">
                        <label class="form-label fw-semibold small mb-1" for="gallery_multi_input">Upload multiple images</label>
                        <input type="file" id="gallery_multi_input" class="form-control form-control-sm" accept="image/*" multiple onchange="uploadGalleryImages(this)">
                        <input type="file" id="gallery_single_holder" class="d-none" accept="image/*">
                        <div class="form-text">Select several photos from your computer. They upload one after another, and the percent loader appears below.</div>
                    </div>

                    <input type="hidden" name="gallery_raw" id="gallery_raw_input">

                    <div id="gallery_slots_container">
                        @forelse($galleryArr as $gi => $gUrl)
                        @php
                            $slotUrl = old('gallery_raw.' . $gi, $gUrl);
                            $thumbUrl = '';
                            if (is_string($slotUrl) && $slotUrl !== '') {
                                if (preg_match('#^https?://#i', $slotUrl)) {
                                    $thumbUrl = $slotUrl;
                                } elseif (str_starts_with($slotUrl, '/')) {
                                    $thumbUrl = 'http://localhost:3000' . $slotUrl;
                                } else {
                                    $thumbUrl = $slotUrl;
                                }
                            }
                        @endphp
                        <div class="gallery-slot">
                            <span class="badge bg-secondary" style="min-width:28px;">{{ $gi + 1 }}</span>
                            <div class="gallery-thumb-wrap">
                                <img class="gallery-thumb rounded border object-fit-cover {{ $thumbUrl ? '' : 'd-none' }}"
                                     src="{{ $thumbUrl }}" alt="Gallery image {{ $gi + 1 }}"
                                     onerror="this.classList.add('d-none'); this.parentElement.querySelector('.gallery-thumb-empty').classList.remove('d-none');">
                                <div class="gallery-thumb-empty rounded border {{ $thumbUrl ? 'd-none' : '' }}">
                                    <i class="bi bi-image"></i>
                                </div>
                            </div>
                            <input type="hidden" class="gallery-url-field" value="{{ $slotUrl }}">
                            <button type="button" class="btn btn-outline-danger btn-sm" onclick="removeGallerySlot(this)" title="Remove">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
                        @empty
                        @endforelse
                    </div>

                    <div id="gallery_upload_progress" class="d-none mt-2">
                        <div class="progress" style="height:4px;">
                            <div class="progress-bar progress-bar-striped progress-bar-animated bg-success w-100"></div>
                        </div>
                        <small class="text-muted">Uploading gallery image&hellip;</small>
                    </div>
                </div>
            </div>

            @include('admin.villas.partials.video-tour')

            <!-- Descriptions Card -->
            <div class="card card-custom p-4 mb-4">
                <h5 class="fw-bold mb-3" style="color: var(--fs-emerald);">Descriptions</h5>

                <div class="mb-3">
                    <label class="form-label fw-semibold small">Short Summary Description</label>
                    <textarea name="short_description" rows="2" class="form-control">{{ old('short_description', $villa->short_description) }}</textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold small">Where You'll Sleep heading</label>
                    <input type="text" name="sleep_heading" class="form-control" value="{{ old('sleep_heading', $villa->sleep_heading) }}" placeholder="Five serene sleeping sanctuaries">
                    <small class="text-muted">Shown above the bedroom photos. Leave blank to use the bedroom count.</small>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold small">Where You'll Sleep intro</label>
                    <textarea name="sleep_intro" rows="2" class="form-control" placeholder="Each bedroom is air-conditioned with an en-suite bathroom, premium linen and a view of the pool, garden or courtyard.">{{ old('sleep_intro', $villa->sleep_intro) }}</textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold small d-flex align-items-center gap-2">
                        Full Detailed Overview
                        <span class="badge text-bg-primary" style="font-size:0.65rem;">Rich Text Editor</span>
                    </label>
                    <textarea name="description" id="ck_description" class="form-control">{{ old('description', $villa->description) }}</textarea>
                </div>
            </div>

            @include('admin.villas.partials.extras')
            @include('admin.villas.partials.policy-editors')

        </div>{{-- /col-lg-8 --}}

        <!-- ===== RIGHT COLUMN ===== -->
        <div class="col-lg-4">
            <!-- Publishing Actions -->
            <div class="card card-custom p-4 mb-4">
                <h5 class="fw-bold mb-3" style="color: var(--fs-emerald);">Publishing</h5>

                <div class="mb-3">
                    <label class="form-label fw-semibold small">Listing Status</label>
                    <select name="status" class="form-select">
                        <option value="active" {{ old('status', $villa->status) === 'active' ? 'selected' : '' }}>Active (Live on website)</option>
                        <option value="inactive" {{ old('status', $villa->status) === 'inactive' ? 'selected' : '' }}>Inactive (Draft)</option>
                        <option value="maintenance" {{ old('status', $villa->status) === 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                    </select>
                </div>

                <div class="form-check form-switch mb-3">
                    <input class="form-check-input" type="checkbox" name="is_featured" id="is_featured" value="1" {{ old('is_featured', $villa->is_featured) ? 'checked' : '' }}>
                    <label class="form-check-label fw-semibold small" for="is_featured">Feature on Home Page</label>
                </div>

                <div class="form-check form-switch mb-3">
                    <input class="form-check-input" type="checkbox" name="show_on_sale" id="show_on_sale" value="1" {{ old('show_on_sale', $villa->show_on_sale) ? 'checked' : '' }}>
                    <label class="form-check-label fw-semibold small" for="show_on_sale">Villas for Sale</label>
                </div>

                <div class="form-check form-switch mb-3">
                    <input class="form-check-input" type="checkbox" name="show_on_list" id="show_on_list" value="1" {{ old('show_on_list', $villa->show_on_list) ? 'checked' : '' }}>
                    <label class="form-check-label fw-semibold small" for="show_on_list">List Your Villas</label>
                </div>

                <div class="form-check form-switch mb-4">
                    <input class="form-check-input" type="checkbox" name="instant_booking" id="instant_booking" value="1" {{ old('instant_booking', $villa->instant_booking) ? 'checked' : '' }}>
                    <label class="form-check-label fw-semibold small" for="instant_booking">Instant Booking Available</label>
                </div>

                <button type="submit" class="btn btn-gold w-100 py-2 mb-2">
                    <i class="bi bi-check2-circle me-1"></i> Update Villa Listing
                </button>
            </div>


            <!-- Amenities Selector -->
            <div class="card card-custom p-4 mb-4">
                <h5 class="fw-bold mb-2" style="color: var(--fs-emerald);">Amenities Checklist</h5>
                <p class="text-muted small mb-3">Select the amenities available at this villa.</p>

                @php
                    $currentKeys = $villa->amenity_keys ?? [];
                @endphp
                <div style="max-height: 280px; overflow-y: auto;" class="pe-2">
                    @foreach($amenities as $amenity)
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" name="amenities_list[]" value="{{ $amenity->key }}" id="amenity_{{ $amenity->id }}" {{ in_array($amenity->key, old('amenities_list', $currentKeys)) ? 'checked' : '' }}>
                            <label class="form-check-label small" for="amenity_{{ $amenity->id }}">
                                <i class="bi {{ $amenity->icon ?? 'bi-stars' }} me-1 text-muted"></i> {{ $amenity->name }}
                            </label>
                        </div>
                    @endforeach
                </div>
            </div>

            @include('admin.villas.partials.stay-content')

            <!-- SEO & Meta Data -->
            <div class="card card-custom p-4 mb-4">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <h5 class="fw-bold mb-0" style="color: var(--fs-emerald);">
                        <i class="bi bi-search me-2 text-primary"></i> SEO &amp; Meta Data
                    </h5>
                    <span class="badge bg-light text-secondary border" style="font-size:0.65rem;">Seo</span>
                </div>
                <p class="text-muted small mb-3">Optimize how this luxury villa appears in Google search results and social media.</p>

                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <label class="form-label fw-semibold small mb-0">Meta Title</label>
                        <small class="text-muted" id="meta_title_count">0 / 60 chars</small>
                    </div>
                    <input type="text" name="meta_title" id="meta_title" class="form-control"
                           placeholder="e.g. Sunset Luxury Villa | Fantastic Stays"
                           value="{{ old('meta_title', $villa->meta_title) }}" maxlength="70" oninput="updateSeoPreview()">
                    <small class="text-muted">Recommended 50&ndash;60 characters.</small>
                </div>

                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <label class="form-label fw-semibold small mb-0">Meta Description</label>
                        <small class="text-muted" id="meta_desc_count">0 / 160 chars</small>
                    </div>
                    <textarea name="meta_description" id="meta_description" rows="3" class="form-control"
                              placeholder="Book Sunset Villa in Assagao, North Goa. 4 bedrooms, private pool."
                              maxlength="200" oninput="updateSeoPreview()">{{ old('meta_description', $villa->meta_description) }}</textarea>
                    <small class="text-muted">Recommended 140&ndash;160 characters.</small>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold small">Meta Keywords</label>
                    <input type="text" name="meta_keywords" class="form-control"
                           placeholder="luxury villa goa, private pool villa assagao, holiday rental"
                           value="{{ old('meta_keywords', $villa->meta_keywords) }}">
                    <small class="text-muted">Comma-separated search tags.</small>
                </div>

                <!-- Google Snippet Preview -->
                <div class="p-3 rounded-3 border" style="background:#f8f9fa;">
                    <small class="text-uppercase fw-bold text-muted d-block mb-2" style="font-size:0.68rem; letter-spacing:0.8px;">
                        <i class="bi bi-google me-1"></i> Google Snippet Preview
                    </small>
                    <div class="small text-muted font-monospace mb-1" style="font-size:0.74rem;">
                        https://fantasticstays.com/villas/<span id="seo_preview_slug">{{ $villa->slug }}</span>
                    </div>
                    <div class="fw-semibold fs-6 mb-1 text-truncate" id="seo_preview_title" style="color:#1a0dab;">
                        {{ $villa->meta_title ?: ($villa->name . ' | Fantastic Stays') }}
                    </div>
                    <div class="text-secondary small" id="seo_preview_desc" style="font-size:0.8rem; line-height:1.4;">
                        {{ $villa->meta_description ?: ($villa->short_description ?: 'Villa description snippet will appear here on Google search results...') }}
                    </div>
                </div>
            </div>

        </div>
    </div>
</form>
@endsection

@section('scripts')
{{-- CKEditor 4 full toolbar. Image button opens Laravel File Manager. --}}
<script src="https://cdn.ckeditor.com/4.22.1/full/ckeditor.js"></script>

<script>
// ─── CKEditor Init ─────────────────────────────────────────────────────────
let ckEditorInstance = null;

document.addEventListener('DOMContentLoaded', function () {

    const ckeditorOptions = {
        versionCheck: false,
        filebrowserImageBrowseUrl: @json(url('/admin/lfm?type=Images')),
        filebrowserImageUploadUrl: @json(url('/admin/lfm/upload?type=Images&_token='.csrf_token())),
        filebrowserBrowseUrl: @json(url('/admin/lfm?type=Files')),
        filebrowserUploadUrl: @json(url('/admin/lfm/upload?type=Files&_token='.csrf_token())),
        filebrowserWindowWidth: 980,
        filebrowserWindowHeight: 640,
        removeDialogTabs: 'image:advanced;link:advanced'
    };

    function mountEditor(id, height) {
        const field = document.getElementById(id);
        if (!field) return null;
        return CKEDITOR.replace(id, Object.assign({ height: height }, ckeditorOptions));
    }

    ckEditorInstance = mountEditor('ck_description', 320);
    const ckCancellation = mountEditor('ck_cancellation', 260);
    const ckHouseRules = mountEditor('ck_house_rules', 260);

    document.getElementById('villa-form').addEventListener('submit', function () {
        [ckEditorInstance, ckCancellation, ckHouseRules].forEach(function (editor) {
            if (editor) editor.updateElement();
        });
        syncGalleryRaw();
    });

    // Initialize gallery slot count from server-rendered slots
    gallerySlotCount = document.querySelectorAll('.gallery-slot').length;
    syncGalleryRaw();

    updateSeoPreview();
});

// ─── Hero Image Upload ──────────────────────────────────────────────────────
function previewHeroImage(url) {
    const preview = document.getElementById('hero_preview');
    if (preview && url) preview.src = url;
}

function uploadHeroImage(fileInput) {
    const progress = document.getElementById('hero_upload_progress');
    progress.classList.remove('d-none');
    window.uploadAdminImage(fileInput, @json(route('admin.villas.upload-image')))
        .then((data) => {
            if (!data || !data.url) return;
            document.getElementById('hero_image_input').value = data.url;
            previewHeroImage(data.url);
        })
        .finally(() => progress.classList.add('d-none'));
}

// ─── Gallery Slots ──────────────────────────────────────────────────────────
let gallerySlotCount = 0;
const GALLERY_MAX = 20;

function syncGalleryRaw() {
    const urls = [];
    document.querySelectorAll('.gallery-url-field').forEach(f => {
        if (f.value.trim()) urls.push(f.value.trim());
    });
    document.getElementById('gallery_raw_input').value = urls.join('\n');
}

function galleryPreviewUrl(url) {
    const value = (url || '').trim();
    if (!value) return '';
    if (/^https?:\/\//i.test(value)) return value;
    if (value.charAt(0) === '/') return 'http://localhost:3000' + value;
    return value;
}

function refreshGalleryThumb(slot) {
    const field = slot.querySelector('.gallery-url-field');
    const thumb = slot.querySelector('.gallery-thumb');
    const empty = slot.querySelector('.gallery-thumb-empty');
    const src = galleryPreviewUrl(field ? field.value : '');
    if (!thumb || !empty) return;
    if (!src) {
        thumb.classList.add('d-none');
        thumb.removeAttribute('src');
        empty.classList.remove('d-none');
        return;
    }
    empty.classList.add('d-none');
    thumb.onload = function () { empty.classList.add('d-none'); thumb.classList.remove('d-none'); };
    thumb.onerror = function () { thumb.classList.add('d-none'); empty.classList.remove('d-none'); };
    thumb.src = src;
    thumb.classList.remove('d-none');
}

function gallerySlotHtml(index) {
    return '<span class="badge bg-secondary" style="min-width:28px;">' + index + '</span>' +
        '<div class="gallery-thumb-wrap">' +
        '<img class="gallery-thumb rounded border object-fit-cover d-none" alt="Gallery image ' + index + '">' +
        '<div class="gallery-thumb-empty rounded border"><i class="bi bi-image"></i></div>' +
        '</div>' +
        '<input type="hidden" class="gallery-url-field" value="">' +
        '<button type="button" class="btn btn-outline-danger btn-sm" onclick="removeGallerySlot(this)" title="Remove">' +
        '<i class="bi bi-trash"></i></button>';
}

function addGallerySlot() {
    if (gallerySlotCount >= GALLERY_MAX) return;
    gallerySlotCount++;
    const container = document.getElementById('gallery_slots_container');
    const div = document.createElement('div');
    div.className = 'gallery-slot';
    div.innerHTML = gallerySlotHtml(gallerySlotCount);
    container.appendChild(div);
}

function removeGallerySlot(btn) {
    btn.closest('.gallery-slot').remove();
    document.querySelectorAll('.gallery-slot').forEach((s, i) => {
        const badge = s.querySelector('.badge');
        if (badge) badge.textContent = i + 1;
    });
    gallerySlotCount = document.querySelectorAll('.gallery-slot').length;
    syncGalleryRaw();
}

function placeGalleryImage(url) {
    let slot = null;
    document.querySelectorAll('#gallery_slots_container .gallery-slot').forEach(function (candidate) {
        if (slot) return;
        const field = candidate.querySelector('.gallery-url-field');
        if (field && !field.value.trim()) slot = candidate;
    });
    if (!slot) {
        if (document.querySelectorAll('#gallery_slots_container .gallery-slot').length >= GALLERY_MAX) return;
        addGallerySlot();
        const slots = document.querySelectorAll('#gallery_slots_container .gallery-slot');
        slot = slots[slots.length - 1];
    }
    if (!slot) return;
    slot.querySelector('.gallery-url-field').value = url;
    refreshGalleryThumb(slot);
    syncGalleryRaw();
}

function uploadGalleryImages(fileInput) {
    const selected = Array.from(fileInput.files || []);
    fileInput.value = '';
    if (!selected.length) return;

    const filled = Array.from(document.querySelectorAll('#gallery_slots_container .gallery-url-field'))
        .filter(function (field) { return field.value.trim() !== ''; }).length;
    const room = GALLERY_MAX - filled;
    if (room <= 0) {
        alert('Maximum 20 gallery images allowed.');
        return;
    }
    const batch = selected.slice(0, room);
    if (selected.length > room) {
        alert('Only ' + room + ' more image' + (room === 1 ? '' : 's') + ' can be added. The rest were skipped.');
    }

    const holder = document.getElementById('gallery_single_holder');
    const submit = document.querySelector('#villa-form button[type="submit"]');
    const uploadUrl = @json(route('admin.villas.upload-image'));
    fileInput.disabled = true;
    if (submit) submit.disabled = true;

    let chain = Promise.resolve();
    batch.forEach(function (file, index) {
        chain = chain.then(function () {
            const transfer = new DataTransfer();
            transfer.items.add(file);
            holder.files = transfer.files;
            return window.uploadAdminImage(holder, uploadUrl, (index + 1) + ' of ' + batch.length).then(function (data) {
                if (!data || !data.url) return Promise.reject();
                placeGalleryImage(data.url);
            });
        });
    });

    chain.catch(function () {}).finally(function () {
        holder.value = '';
        fileInput.disabled = false;
        if (submit) submit.disabled = false;
    });
}

function uploadGalleryImage(fileInput) {
    const progress = document.getElementById('gallery_upload_progress');
    progress.classList.remove('d-none');
    window.uploadAdminImage(fileInput, @json(route('admin.villas.upload-image')))
        .then((data) => {
            if (!data || !data.url) return;
            const slot = fileInput.closest('.gallery-slot');
            slot.querySelector('.gallery-url-field').value = data.url;
            refreshGalleryThumb(slot);
            syncGalleryRaw();
        })
        .finally(() => progress.classList.add('d-none'));
}

// ─── SEO Preview ────────────────────────────────────────────────────────────
function updateSeoPreview() {
    const titleInput = document.getElementById('meta_title');
    const descInput  = document.getElementById('meta_description');
    const defaultTitle = "{{ addslashes($villa->name) }} | Fantastic Stays";

    const previewTitle = document.getElementById('seo_preview_title');
    const previewDesc  = document.getElementById('seo_preview_desc');
    const titleCount   = document.getElementById('meta_title_count');
    const descCount    = document.getElementById('meta_desc_count');

    if (titleInput && previewTitle) {
        const val = titleInput.value.trim() || defaultTitle;
        previewTitle.textContent = val;
        if (titleCount) titleCount.textContent = titleInput.value.length + ' / 60 chars';
    }
    if (descInput && previewDesc) {
        const val = descInput.value.trim() || 'Book luxury private villa in Goa with private pool, chef service and premium hospitality.';
        previewDesc.textContent = val;
        if (descCount) descCount.textContent = descInput.value.length + ' / 160 chars';
    }
}
</script>

<style>
    #gallery_slots_container {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 12px;
    }
    @media (min-width: 768px) {
        #gallery_slots_container { grid-template-columns: repeat(4, minmax(0, 1fr)); }
    }
    .gallery-slot { position: relative; }
    .gallery-thumb-wrap { width: 100%; height: 120px; position: relative; }
    .gallery-thumb, .gallery-thumb-empty {
        width: 100%; height: 120px; background: #f4f1ea;
    }
    .gallery-slot .badge { position: absolute; top: 8px; left: 8px; z-index: 2; }
    .gallery-slot > .btn {
        position: absolute; top: 8px; right: 8px; z-index: 2;
        background: #fff; padding: 0.15rem 0.4rem;
    }
    .gallery-thumb-empty {
        display: flex; align-items: center; justify-content: center; color: #8a8175;
    }
    .cke { border-radius: 0.375rem !important; }
    .cke_top { border-radius: 0.375rem 0.375rem 0 0 !important; }
    .cke_bottom { border-radius: 0 0 0.375rem 0.375rem !important; }
</style>
@endsection
