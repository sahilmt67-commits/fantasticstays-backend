@extends('layouts.admin')

@section('title', 'Curated Occasions')

@section('content')
<div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between mb-4 gap-3">
    <div>
        <h2 class="fw-bold mb-1" style="color: var(--fs-emerald);">Curated Stay Occasions</h2>
        <p class="text-muted mb-0 small">Manage theme cards shown on homepage ("Luxury Stays - Tailored to Your Party").</p>
    </div>
    <button type="button" class="btn btn-gold d-inline-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#addOccasionModal">
        <i class="bi bi-plus-lg"></i> Add Occasion
    </button>
</div>

<div class="row g-4">
    @forelse($occasions as $occ)
        <div class="col-md-6 col-xl-4">
            <div class="card card-custom h-100 overflow-hidden">
                <div class="position-relative" style="height: 180px; background-color: #eee;">
                    <img src="{{ $occ->image }}" alt="{{ $occ->title }}" class="w-100 h-100 object-fit-cover" onerror="this.src='/images/demo/occ-group.webp'">
                    @if($occ->badge)
                        <span class="position-absolute top-0 end-0 m-3 badge bg-warning text-dark">
                            {{ $occ->badge }}
                        </span>
                    @endif
                </div>
                <div class="card-body p-4 d-flex flex-column justify-content-between">
                    <div>
                        <h5 class="fw-bold text-dark mb-1">{{ $occ->title }}</h5>
                        <p class="text-muted small mb-3">{{ $occ->description }}</p>
                    </div>

                    <div class="d-flex align-items-center justify-content-between pt-2 border-top">
                        <span class="badge {{ $occ->is_active ? 'bg-success-subtle text-success' : 'bg-secondary-subtle text-secondary' }}">
                            {{ $occ->is_active ? 'Active' : 'Hidden' }}
                        </span>
                        <div class="d-flex gap-1">
                            <button type="button" class="btn btn-sm btn-light border" data-bs-toggle="modal" data-bs-target="#editOccasionModal{{ $occ->id }}">
                                <i class="bi bi-pencil"></i>
                            </button>
                            <form action="{{ route('admin.occasions.destroy', $occ->id) }}" method="POST" onsubmit="return confirm('Delete occasion?');">
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
            <div class="modal fade" id="editOccasionModal{{ $occ->id }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <form action="{{ route('admin.occasions.update', $occ->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="modal-header">
                                <h6 class="modal-title fw-bold">Edit Occasion: {{ $occ->title }}</h6>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label class="form-label small fw-semibold">Occasion Title</label>
                                    <input type="text" name="title" class="form-control" value="{{ $occ->title }}" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label small fw-semibold">Cover Image</label>
                                    <div class="d-flex gap-2 align-items-center">
                                        <img id="occ_preview_{{ $occ->id }}" src="{{ $occ->image }}" alt=""
                                             class="rounded border object-fit-cover {{ $occ->image ? '' : 'd-none' }}"
                                             style="width:72px;height:52px;background:#f4f1ea;object-fit:cover;">
                                        <input type="text" name="image" id="occ_image_{{ $occ->id }}" class="form-control font-monospace small" value="{{ $occ->image }}" readonly>
                                        <label class="btn btn-outline-secondary btn-sm mb-0 text-nowrap" style="cursor:pointer;">
                                            <i class="bi bi-folder2-open me-1"></i> Browse
                                            <input type="file" accept="image/*" class="d-none" onchange="uploadOccasionImage(this, 'occ_image_{{ $occ->id }}', 'occ_preview_{{ $occ->id }}')">
                                        </label>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label small fw-semibold">Badge Label (Optional)</label>
                                    <input type="text" name="badge" class="form-control small" value="{{ $occ->badge }}">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label small fw-semibold">Description</label>
                                    <textarea name="description" rows="3" class="form-control small">{{ $occ->description }}</textarea>
                                </div>
                                <div class="form-check form-switch mb-2">
                                    <input class="form-check-input" type="checkbox" name="is_active" value="1" id="occ_act_{{ $occ->id }}" {{ $occ->is_active ? 'checked' : '' }}>
                                    <label class="form-check-label small" for="occ_act_{{ $occ->id }}">Active on Homepage</label>
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
        <div class="col-12 py-5 text-center text-muted">No occasions configured yet.</div>
    @endforelse
</div>

<!-- Add Modal -->
<div class="modal fade" id="addOccasionModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('admin.occasions.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h6 class="modal-title fw-bold">Add Curated Occasion</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Occasion Title <span class="text-danger">*</span></label>
                        <input type="text" name="title" class="form-control" placeholder="e.g. Pool Parties, Romantic Escapes" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Cover Image</label>
                        <div class="d-flex gap-2 align-items-center">
                            <img id="occ_preview_new" alt="" class="rounded border object-fit-cover d-none" style="width:72px;height:52px;background:#f4f1ea;object-fit:cover;">
                            <input type="text" name="image" id="occ_image_new" class="form-control font-monospace small" placeholder="Browse to upload into storage" readonly>
                            <label class="btn btn-outline-secondary btn-sm mb-0 text-nowrap" style="cursor:pointer;">
                                <i class="bi bi-folder2-open me-1"></i> Browse
                                <input type="file" accept="image/*" class="d-none" onchange="uploadOccasionImage(this, 'occ_image_new', 'occ_preview_new')">
                            </label>
                        </div>
                        <div id="occ_upload_progress" class="d-none mt-2">
                            <small class="text-muted">Uploading&hellip;</small>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Badge (Optional)</label>
                        <input type="text" name="badge" class="form-control" placeholder="e.g. POPULAR, TRENDING">
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Description</label>
                        <textarea name="description" rows="3" class="form-control small" placeholder="Atmosphere and why this style of villa is perfect..."></textarea>
                    </div>
                    <div class="form-check form-switch mb-2">
                        <input class="form-check-input" type="checkbox" name="is_active" value="1" id="new_occ_act" checked>
                        <label class="form-check-label small" for="new_occ_act">Active on Homepage</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-sm btn-gold">Create Occasion</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
function uploadOccasionImage(fileInput, inputId, previewId) {
    const file = fileInput.files && fileInput.files[0];
    if (!file) return;
    const progress = document.getElementById('occ_upload_progress');
    if (progress) progress.classList.remove('d-none');
    const formData = new FormData();
    formData.append('image', file);
    formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);
    fetch(@json(route('admin.occasions.upload-image')), { method: 'POST', body: formData })
        .then(function (response) { return response.json(); })
        .then(function (data) {
            if (!data.url) {
                alert('Upload failed: ' + (data.message || 'Unknown error'));
                return;
            }
            const input = document.getElementById(inputId);
            const preview = document.getElementById(previewId);
            if (input) input.value = data.url;
            if (preview) {
                preview.src = data.url;
                preview.classList.remove('d-none');
            }
        })
        .catch(function (error) { alert('Upload error: ' + error); })
        .finally(function () {
            if (progress) progress.classList.add('d-none');
            fileInput.value = '';
        });
}
</script>
@endsection
