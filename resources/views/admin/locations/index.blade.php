@extends('layouts.admin')

@section('title', 'Locations')

@section('content')
<div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between mb-4 gap-3">
    <div>
        <h2 class="fw-bold mb-1" style="color: var(--fs-emerald);">Locations</h2>
        <p class="text-muted mb-0 small">Manage destination areas for the homepage grid and the villa detail Location section. A villa uses whichever location is selected on its add or edit page.</p>
    </div>
    <button type="button" class="btn btn-gold d-inline-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#addLocationModal">
        <i class="bi bi-plus-lg"></i> Add Location
    </button>
</div>

<div class="row g-4">
    @forelse($locations as $loc)
        <div class="col-md-6 col-xl-4">
            <div class="card card-custom h-100 overflow-hidden">
                <div class="position-relative d-flex align-items-center justify-content-center text-muted" style="height: 180px; background-color: #eee;">
                    @if(filled($loc->image))
                        <img src="{{ $loc->image }}" alt="{{ $loc->name }}" class="w-100 h-100 object-fit-cover" onerror="this.onerror=null;this.removeAttribute('src');this.classList.add('d-none');">
                    @else
                        <i class="bi bi-image fs-3"></i>
                    @endif
                    <span class="position-absolute top-0 end-0 m-3 badge bg-dark">
                        {{ $loc->region }}
                    </span>
                    <span class="position-absolute bottom-0 start-0 m-3 badge bg-white text-dark shadow-sm">
                        <i class="bi bi-house-door-fill text-warning me-1"></i> {{ $loc->villas_count }} Villas
                    </span>
                </div>
                <div class="card-body p-4 d-flex flex-column justify-content-between">
                    <div>
                        <h5 class="fw-bold text-dark mb-1">{{ $loc->name }}</h5>
                        <p class="text-muted small mb-3">{{ Str::limit(trim(html_entity_decode(strip_tags(str_replace('&nbsp;', ' ', (string) $loc->description)))), 110) }}</p>
                    </div>

                    <div class="d-flex align-items-center justify-content-between pt-2 border-top">
                        <span class="badge {{ $loc->is_featured ? 'bg-warning-subtle text-warning-emphasis' : 'bg-light text-muted' }}">
                            {{ $loc->is_featured ? 'Featured on Home' : 'Standard' }}
                        </span>
                        <div class="d-flex gap-1">
                            <button type="button" class="btn btn-sm btn-light border" data-bs-toggle="modal" data-bs-target="#editLocationModal{{ $loc->id }}">
                                <i class="bi bi-pencil"></i>
                            </button>
                            <form action="{{ route('admin.locations.destroy', $loc->id) }}" method="POST" onsubmit="return confirm('Delete location {{ $loc->name }}?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-light border text-danger">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Edit Modal -->
            <div class="modal fade" id="editLocationModal{{ $loc->id }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
                    <div class="modal-content">
                        <form action="{{ route('admin.locations.update', $loc->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="modal-header">
                                <h6 class="modal-title fw-bold">Edit Location: {{ $loc->name }}</h6>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label class="form-label small fw-semibold">Location Name</label>
                                    <input type="text" name="name" class="form-control" value="{{ $loc->name }}" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label small fw-semibold">Region</label>
                                    <input type="text" name="region" class="form-control" value="{{ $loc->region }}" placeholder="e.g. North Goa" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label small fw-semibold">Cover Image</label>
                                    <div class="d-flex gap-2 align-items-center">
                                        <img id="loc_preview_{{ $loc->id }}" @if(filled($loc->image)) src="{{ $loc->image }}" @endif alt=""
                                             class="rounded border object-fit-cover {{ $loc->image ? '' : 'd-none' }}"
                                             style="width:72px;height:52px;background:#f4f1ea;object-fit:cover;"
                                             onerror="this.onerror=null;this.removeAttribute('src');this.classList.add('d-none');">
                                        <input type="text" name="image" id="loc_image_{{ $loc->id }}" class="form-control font-monospace small" value="{{ $loc->image }}" readonly>
                                        <label class="btn btn-outline-secondary btn-sm mb-0 text-nowrap" style="cursor:pointer;">
                                            <i class="bi bi-folder2-open me-1"></i> Browse
                                            <input type="file" accept="image/*" class="d-none" onchange="uploadLocationImage(this, 'loc_image_{{ $loc->id }}', 'loc_preview_{{ $loc->id }}')">
                                        </label>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label small fw-semibold">Detail page heading</label>
                                    <input type="text" name="detail_heading" class="form-control" value="{{ $loc->detail_heading }}" placeholder="{{ $loc->name }}, {{ $loc->region }}">
                                    <div class="form-text">Leave blank to show “{{ $loc->name }}, {{ $loc->region }}”.</div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label small fw-semibold d-flex align-items-center gap-2">
                                        Description
                                        <span class="badge text-bg-primary" style="font-size:0.65rem;">Rich Text Editor</span>
                                    </label>
                                    <textarea name="description" id="loc_description_{{ $loc->id }}" class="form-control location-editor">{{ $loc->description }}</textarea>
                                    <div class="form-text">Shown under the heading on the villa detail page.</div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label small fw-semibold">Nearby places</label>
                                    <textarea name="places_raw" rows="6" class="form-control font-monospace small" placeholder="Anjuna Beach | 3.8 km | Beach">{{ $loc->placesAsText() }}</textarea>
                                    <div class="form-text">One place per line: Name | distance | category. Category is optional.</div>
                                </div>
                                @include('admin.locations.partials.seo', ['location' => $loc])
                                @include('admin.locations.partials.page-type', ['location' => $loc, 'suffix' => $loc->id])
                                <div class="form-check form-switch mb-2">
                                    <input class="form-check-input" type="checkbox" name="is_featured" value="1" id="loc_feat_{{ $loc->id }}" {{ $loc->is_featured ? 'checked' : '' }}>
                                    <label class="form-check-label small" for="loc_feat_{{ $loc->id }}">Featured on Homepage Grid</label>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-sm btn-light" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-sm btn-gold">Save Changes</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12 py-5 text-center text-muted">No locations added yet.</div>
    @endforelse
</div>

<!-- Add Modal -->
<div class="modal fade" id="addLocationModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <form action="{{ route('admin.locations.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h6 class="modal-title fw-bold">Add Location</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Location Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" placeholder="e.g. Assagao, Candolim, Vagator" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Region <span class="text-danger">*</span></label>
                        <input type="text" name="region" class="form-control" placeholder="e.g. North Goa" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Cover Image</label>
                        <div class="d-flex gap-2 align-items-center">
                            <img id="loc_preview_new" alt="" class="rounded border object-fit-cover d-none" style="width:72px;height:52px;background:#f4f1ea;object-fit:cover;">
                            <input type="text" name="image" id="loc_image_new" class="form-control font-monospace small" placeholder="Browse to upload into storage" readonly>
                            <label class="btn btn-outline-secondary btn-sm mb-0 text-nowrap" style="cursor:pointer;">
                                <i class="bi bi-folder2-open me-1"></i> Browse
                                <input type="file" accept="image/*" class="d-none" onchange="uploadLocationImage(this, 'loc_image_new', 'loc_preview_new')">
                            </label>
                        </div>
                        <div id="loc_upload_progress" class="d-none mt-2">
                            <small class="text-muted">Uploading&hellip;</small>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Detail page heading</label>
                        <input type="text" name="detail_heading" class="form-control" placeholder="Anjuna — the garden village of North Goa">
                        <div class="form-text">Leave blank to show the location name and region.</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold d-flex align-items-center gap-2">
                            Description
                            <span class="badge text-bg-primary" style="font-size:0.65rem;">Rich Text Editor</span>
                        </label>
                        <textarea name="description" id="loc_description_new" class="form-control location-editor" placeholder="Village highlights, vibe, and atmosphere..."></textarea>
                        <div class="form-text">Shown under the heading on the villa detail page.</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Nearby places</label>
                        <textarea name="places_raw" rows="6" class="form-control font-monospace small" placeholder="Anjuna Beach | 3.8 km | Beach&#10;Jamun Assagao | 0.5 km | Dining"></textarea>
                        <div class="form-text">One place per line: Name | distance | category. Category is optional.</div>
                    </div>
                    @include('admin.locations.partials.seo', ['location' => new \App\Models\Location()])
                    @include('admin.locations.partials.page-type', ['location' => new \App\Models\Location(), 'suffix' => 'new'])
                    <div class="form-check form-switch mb-2">
                        <input class="form-check-input" type="checkbox" name="is_featured" value="1" id="new_feat" checked>
                        <label class="form-check-label small" for="new_feat">Featured on Homepage Grid</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-sm btn-gold">Create Location</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.ckeditor.com/4.22.1/full/ckeditor.js"></script>
<script>
const locationEditorOptions = {
    versionCheck: false,
    height: 220,
    filebrowserImageBrowseUrl: @json(url('/admin/lfm?type=Images')),
    filebrowserImageUploadUrl: @json(url('/admin/lfm/upload?type=Images&_token='.csrf_token())),
    filebrowserBrowseUrl: @json(url('/admin/lfm?type=Files')),
    filebrowserUploadUrl: @json(url('/admin/lfm/upload?type=Files&_token='.csrf_token())),
    filebrowserWindowWidth: 980,
    filebrowserWindowHeight: 640,
    removeDialogTabs: 'image:advanced;link:advanced'
};

function mountLocationEditor(field) {
    if (!field || !field.id || !window.CKEDITOR) return;
    if (CKEDITOR.instances[field.id]) return;
    CKEDITOR.replace(field.id, locationEditorOptions);
}

document.querySelectorAll('#addLocationModal form, [id^="editLocationModal"] form').forEach(function (form) {
    form.addEventListener('submit', function () {
        form.querySelectorAll('textarea.location-editor').forEach(function (field) {
            const editor = window.CKEDITOR && CKEDITOR.instances[field.id];
            if (editor) editor.updateElement();
        });
    });
});

function slugifyLocation(value) {
    return String(value || '')
        .toLowerCase()
        .trim()
        .replace(/[^a-z0-9]+/g, '-')
        .replace(/^-+|-+$/g, '') || 'location-name';
}

function bindLocationSeo(root) {
    const form = root.closest('form') || root;
    const title = root.querySelector('[data-seo="title"]');
    const description = root.querySelector('[data-seo="description"]');
    const name = form.querySelector('[name="name"]');
    const titleCount = root.querySelector('[data-seo="title-count"]');
    const descCount = root.querySelector('[data-seo="desc-count"]');
    const previewTitle = root.querySelector('[data-seo="preview-title"]');
    const previewDesc = root.querySelector('[data-seo="preview-desc"]');
    const previewSlug = root.querySelector('[data-seo="preview-slug"]');
    const savedSlug = previewSlug ? previewSlug.textContent.trim() : '';

    function refresh() {
        const nameText = name && name.value.trim() ? name.value.trim() : 'Location';
        const titleText = title && title.value.trim() ? title.value.trim() : nameText + ' | Fantastic Stays';
        const descText = description && description.value.trim()
            ? description.value.trim()
            : 'Location description will appear here in Google search results.';
        if (titleCount && title) titleCount.textContent = title.value.length + ' / 60 chars';
        if (descCount && description) descCount.textContent = description.value.length + ' / 160 chars';
        if (previewTitle) previewTitle.textContent = titleText;
        if (previewDesc) previewDesc.textContent = descText;
        if (previewSlug && savedSlug === 'location-name') previewSlug.textContent = slugifyLocation(name && name.value);
    }

    [title, description, name].forEach(function (field) {
        if (field) field.addEventListener('input', refresh);
    });
    refresh();
}

document.querySelectorAll('[data-location-seo]').forEach(bindLocationSeo);

document.querySelectorAll('#addLocationModal, [id^="editLocationModal"]').forEach(function (modal) {
    modal.addEventListener('shown.bs.modal', function () {
        modal.querySelectorAll('textarea.location-editor').forEach(mountLocationEditor);
    });
});

function uploadLocationImage(fileInput, inputId, previewId) {
    const progress = document.getElementById('loc_upload_progress');
    if (progress) progress.classList.remove('d-none');
    window.uploadAdminImage(fileInput, @json(route('admin.locations.upload-image')))
        .then(function (data) {
            if (!data || !data.url) return;
            const input = document.getElementById(inputId);
            const preview = document.getElementById(previewId);
            if (input) input.value = data.url;
            if (preview) {
                preview.src = data.url;
                preview.classList.remove('d-none');
            }
        })
        .finally(function () {
            if (progress) progress.classList.add('d-none');
            fileInput.value = '';
        });
}
</script>
@endsection
