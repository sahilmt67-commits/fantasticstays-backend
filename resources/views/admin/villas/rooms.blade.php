@extends('layouts.admin')

@section('title', 'Bedrooms Layout - ' . $villa->name)

@section('content')
<div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between mb-4 gap-3">
    <div>
        <h2 class="fw-bold mb-1" style="color: var(--fs-emerald);">Bedrooms & Layout Manager</h2>
        <p class="text-muted mb-0 small">Configure individual room cards, bed types, and ensuite bathrooms for <strong>{{ $villa->name }}</strong> (Shown on Villa Details Page).</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.villas.edit', $villa->id) }}" class="btn btn-outline-secondary">
            <i class="bi bi-pencil-square me-1"></i> Edit Villa
        </a>
        <a href="{{ route('admin.villas.index') }}" class="btn btn-outline-dark">
            <i class="bi bi-arrow-left me-1"></i> All Villas
        </a>
    </div>
</div>

<div class="row g-4">
    <!-- Existing Bedrooms Grid -->
    <div class="col-lg-8">
        <div class="card card-custom p-4 mb-4">
            <div class="d-flex align-items-center justify-content-between mb-3 border-bottom pb-3">
                <h5 class="fw-bold mb-0" style="color: var(--fs-emerald);"><i class="bi bi-door-open-fill text-warning me-2"></i> Current Bedrooms ({{ $villa->rooms->count() }})</h5>
                <span class="badge bg-light text-dark border">{{ $villa->bedrooms }} Total Bedrooms in Villa</span>
            </div>

            <div class="row g-3">
                @forelse($villa->rooms as $room)
                    <div class="col-md-6">
                        <div class="card h-100 border rounded-3 overflow-hidden shadow-sm">
                            <div class="position-relative" style="height: 160px; background-color: #eee;">
                                <img src="{{ $room->image ?? $villa->image }}" alt="{{ $room->room_name }}" class="w-100 h-100 object-fit-cover" onerror="this.src='{{ $villa->image }}'">
                                <span class="position-absolute top-0 end-0 m-2 badge {{ $room->ensuite_bath ? 'bg-success' : 'bg-secondary' }}">
                                    <i class="bi bi-droplet-half me-1"></i> {{ $room->ensuite_bath ? 'Ensuite Bath' : 'Shared Bath' }}
                                </span>
                            </div>
                            <div class="card-body p-3">
                                <h6 class="fw-bold mb-1 text-dark">{{ $room->room_name }}</h6>
                                <div class="badge bg-light text-dark border mb-2"><i class="bi bi-badge-ad me-1 text-muted"></i>{{ $room->bed_type }}</div>
                                <p class="text-muted small mb-3">{{ $room->description ?: 'Luxury linen, air conditioned, bespoke furniture.' }}</p>
                                <div class="d-flex gap-2">
                                    <button type="button" class="btn btn-sm btn-outline-dark flex-grow-1" data-bs-toggle="modal" data-bs-target="#editRoom{{ $room->id }}">
                                        <i class="bi bi-pencil me-1"></i> Edit
                                    </button>
                                    <form action="{{ route('admin.rooms.destroy', $room->id) }}" method="POST" class="flex-grow-1" onsubmit="return confirm('Remove this room layout?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger w-100">
                                            <i class="bi bi-trash me-1"></i> Remove
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 py-5 text-center text-muted">
                        <i class="bi bi-door-closed fs-1 d-block mb-2 text-muted"></i>
                        No bedrooms configured yet. Add your first bedroom layout using the form on the right.
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Add Bedroom Form -->
    <div class="col-lg-4">
        <div class="card card-custom p-4">
            <h5 class="fw-bold mb-3" style="color: var(--fs-emerald);"><i class="bi bi-plus-circle-fill text-success me-2"></i> Add Bedroom Suite</h5>

            <form action="{{ route('admin.villas.rooms.store', $villa->id) }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label fw-semibold small">Room Name <span class="text-danger">*</span></label>
                    <input type="text" name="room_name" class="form-control" placeholder="e.g. Master Bedroom 1" required>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold small">Bed Type <span class="text-danger">*</span></label>
                    <select name="bed_type" class="form-select" required>
                        <option value="King Bed">King Bed</option>
                        <option value="Queen Bed">Queen Bed</option>
                        <option value="Twin Beds">Twin Beds</option>
                        <option value="Double Bed">Double Bed</option>
                        <option value="Bunk Beds">Bunk Beds</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold small">Room Photo</label>
                    <div class="d-flex align-items-center gap-2">
                        <img id="new_room_preview" src="http://localhost:3000/images/demo/detail-primary-bedroom-with-four-poster-teak-bed-and-sheer-d.jpg" alt="" width="72" height="52" style="object-fit:cover;border-radius:8px;">
                        <input type="text" id="new_room_image" name="image" class="form-control font-monospace small" value="/images/demo/detail-primary-bedroom-with-four-poster-teak-bed-and-sheer-d.jpg" readonly>
                        <label class="btn btn-outline-dark mb-0">
                            Browse
                            <input type="file" accept="image/*" class="d-none" onchange="uploadRoomImage(this, 'new_room_image', 'new_room_preview')">
                        </label>
                    </div>
                </div>

                <div class="form-check form-switch mb-3">
                    <input class="form-check-input" type="checkbox" name="ensuite_bath" id="ensuite_bath" value="1" checked>
                    <label class="form-check-label fw-semibold small" for="ensuite_bath">Ensuite Private Bathroom</label>
                </div>

                <div class="mb-4">
                    <label class="form-label fw-semibold small">Room Description</label>
                    <textarea name="description" rows="3" class="form-control small" placeholder="e.g. Four-poster teak king bed, pool view balcony, walk-in shower & rain bath."></textarea>
                </div>

                <button type="submit" class="btn btn-gold w-100 py-2">
                    <i class="bi bi-plus-lg me-1"></i> Add Room Layout
                </button>
            </form>
        </div>
    </div>
</div>

@foreach($villa->rooms as $room)
    @php
        $roomImage = $room->image ?: '';
        $roomPreview = str_starts_with($roomImage, '/images/') ? 'http://localhost:3000'.$roomImage : ($roomImage ?: $villa->image);
        $bedTypes = ['King Bed', 'Queen Bed', 'Twin Beds', 'Double Bed', 'Bunk Beds'];
    @endphp
    <div class="modal fade" id="editRoom{{ $room->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form action="{{ route('admin.rooms.update', $room) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h6 class="modal-title fw-bold">Edit {{ $room->room_name }}</h6>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label fw-semibold small">Room Name</label>
                            <input type="text" name="room_name" class="form-control" value="{{ $room->room_name }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold small">Bed Type</label>
                            <select name="bed_type" class="form-select" required>
                                @foreach($bedTypes as $bedType)
                                    <option value="{{ $bedType }}" {{ $room->bed_type === $bedType ? 'selected' : '' }}>{{ $bedType }}</option>
                                @endforeach
                                @if(!in_array($room->bed_type, $bedTypes, true))
                                    <option value="{{ $room->bed_type }}" selected>{{ $room->bed_type }}</option>
                                @endif
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold small">Room Photo</label>
                            <div class="d-flex align-items-center gap-2">
                                <img id="room_preview_{{ $room->id }}" src="{{ $roomPreview }}" alt="" width="72" height="52" style="object-fit:cover;border-radius:8px;">
                                <input type="text" id="room_image_{{ $room->id }}" name="image" class="form-control font-monospace small" value="{{ $roomImage }}" readonly>
                                <label class="btn btn-outline-dark mb-0">
                                    Browse
                                    <input type="file" accept="image/*" class="d-none" onchange="uploadRoomImage(this, 'room_image_{{ $room->id }}', 'room_preview_{{ $room->id }}')">
                                </label>
                            </div>
                        </div>
                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" name="ensuite_bath" id="ensuite_{{ $room->id }}" value="1" {{ $room->ensuite_bath ? 'checked' : '' }}>
                            <label class="form-check-label fw-semibold small" for="ensuite_{{ $room->id }}">Ensuite Private Bathroom</label>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold small">Room Description</label>
                            <textarea name="description" rows="3" class="form-control small">{{ $room->description }}</textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-gold">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endforeach

<script>
function uploadRoomImage(fileInput, inputId, previewId) {
    const file = fileInput.files && fileInput.files[0];
    if (!file) return;
    const formData = new FormData();
    formData.append('image', file);
    formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);
    fetch(@json(route('admin.villas.upload-image')), { method: 'POST', body: formData })
        .then(function (response) { return response.json(); })
        .then(function (data) {
            if (!data.url) {
                alert('Upload failed');
                return;
            }
            const input = document.getElementById(inputId);
            const preview = document.getElementById(previewId);
            if (input) input.value = data.url;
            if (preview) preview.src = data.url;
        })
        .catch(function (error) { alert('Upload error: ' + error); })
        .finally(function () { fileInput.value = ''; });
}
</script>
@endsection
