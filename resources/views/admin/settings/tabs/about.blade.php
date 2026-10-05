@php
    $aboutImage = $settings['about_image'] ?? '/images/demo/about.webp';
    $aboutPreview = str_starts_with($aboutImage, '/images/') ? 'http://localhost:3000'.$aboutImage : $aboutImage;
@endphp

<div class="card card-custom p-4 mb-4">
    <form action="{{ route('admin.settings.sections') }}" method="POST">
        @csrf
        <input type="hidden" name="tab" value="about">
        <div class="row g-3">
            <div class="col-md-4">
                <label class="form-label small fw-semibold">Eyebrow</label>
                <input type="text" name="about_eyebrow" class="form-control" value="{{ $settings['about_eyebrow'] ?? 'About the Company' }}">
            </div>
            <div class="col-md-8">
                <label class="form-label small fw-semibold">Heading</label>
                <input type="text" name="about_heading" class="form-control" value="{{ $settings['about_heading'] ?? 'Your Trusted Partner for Luxury Villa Rentals in Goa' }}">
            </div>
            <div class="col-12">
                <label class="form-label small fw-semibold">Paragraph</label>
                <textarea name="about_body" rows="4" class="form-control">{{ $settings['about_body'] ?? '' }}</textarea>
            </div>
            <div class="col-md-8">
                <label class="form-label small fw-semibold">Rating caption</label>
                <input type="text" name="about_rating_caption" class="form-control" value="{{ $settings['about_rating_caption'] ?? 'Average guest rating across verified stays' }}">
                <small class="text-muted">The 4.9 / 5 number stays calculated from villa ratings.</small>
            </div>
            <div class="col-12">
                <label class="form-label small fw-semibold">Cover image</label>
                <div class="d-flex align-items-center gap-3">
                    <img id="about_preview" src="{{ $aboutPreview }}" alt="" width="72" height="52" style="object-fit:cover;border-radius:8px;">
                    <input type="text" id="about_image" name="about_image" class="form-control" value="{{ $aboutImage }}" readonly>
                    <label class="btn btn-outline-dark mb-0">
                        Browse
                        <input type="file" accept="image/*" class="d-none" onchange="uploadAboutImage(this)">
                    </label>
                </div>
                <div id="about_upload_progress" class="small text-muted mt-2 d-none">Uploading…</div>
            </div>
        </div>
        <button type="submit" class="btn btn-gold mt-3">Save About Section</button>
    </form>
</div>

<div class="d-flex justify-content-between align-items-center mb-3">
    <p class="text-muted small mb-0">Villa count, location count and average rating can fill in automatically. Choose Manual to type a number such as 10+ or 5,000+.</p>
    <button type="button" class="btn btn-gold" data-bs-toggle="modal" data-bs-target="#addStatModal">
        <i class="bi bi-plus-lg"></i> Add Stat
    </button>
</div>

<div class="card card-custom overflow-hidden">
    <div class="table-responsive">
        <table class="table table-custom mb-0 align-middle">
            <thead>
                <tr>
                    <th style="width:70px;">Order</th>
                    <th>Value</th>
                    <th>Label</th>
                    <th>Source</th>
                    <th>Status</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($stats as $item)
                    <tr>
                        <td>{{ $item->display_order }}</td>
                        <td class="fw-bold">{{ $item->source === 'manual' ? $item->value : 'Auto' }}</td>
                        <td>{{ $item->label }}</td>
                        <td><code class="small">{{ $item->source }}</code></td>
                        <td>
                            <span class="badge {{ $item->is_active ? 'bg-success-subtle text-success' : 'bg-secondary-subtle text-secondary' }}">
                                {{ $item->is_active ? 'Active' : 'Hidden' }}
                            </span>
                        </td>
                        <td class="text-end">
                            <button type="button" class="btn btn-sm btn-light border" data-bs-toggle="modal" data-bs-target="#editStat{{ $item->id }}">
                                <i class="bi bi-pencil"></i>
                            </button>
                            <form action="{{ route('admin.settings.stats.destroy', $item) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this stat?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-light border text-danger"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center text-muted py-4">No stats yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="modal fade" id="addStatModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('admin.settings.stats.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h6 class="modal-title fw-bold">Add Stat</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    @include('admin.settings.tabs.fields.stat', ['item' => null])
                </div>
                <div class="modal-footer"><button type="submit" class="btn btn-gold">Save</button></div>
            </form>
        </div>
    </div>
</div>

@foreach($stats as $item)
<div class="modal fade" id="editStat{{ $item->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('admin.settings.stats.update', $item) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h6 class="modal-title fw-bold">Edit Stat</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    @include('admin.settings.tabs.fields.stat', ['item' => $item])
                </div>
                <div class="modal-footer"><button type="submit" class="btn btn-gold">Save Changes</button></div>
            </form>
        </div>
    </div>
</div>
@endforeach

<script>
function uploadAboutImage(fileInput) {
    const file = fileInput.files && fileInput.files[0];
    if (!file) return;
    const progress = document.getElementById('about_upload_progress');
    if (progress) progress.classList.remove('d-none');
    const formData = new FormData();
    formData.append('image', file);
    formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);
    fetch(@json(route('admin.settings.upload-image')), { method: 'POST', body: formData })
        .then(function (response) { return response.json(); })
        .then(function (data) {
            if (!data.url) {
                alert('Upload failed');
                return;
            }
            document.getElementById('about_image').value = data.url;
            document.getElementById('about_preview').src = data.url;
        })
        .catch(function (error) { alert('Upload error: ' + error); })
        .finally(function () {
            if (progress) progress.classList.add('d-none');
            fileInput.value = '';
        });
}
</script>
