@extends('layouts.admin')

@section('title', 'Manage Amenities')

@section('content')
<div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between mb-4 gap-3">
    <div>
        <h2 class="fw-bold mb-1" style="color: var(--fs-emerald);">Villa Amenities & Features</h2>
        <p class="text-muted mb-0 small">Define master amenities, icons, categories, and search filter pills.</p>
    </div>
    <button type="button" class="btn btn-gold d-inline-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#addAmenityModal">
        <i class="bi bi-plus-lg"></i> Add Amenity
    </button>
</div>

<div class="card card-custom overflow-hidden">
    <div class="table-responsive">
        <table class="table table-custom mb-0">
            <thead>
                <tr>
                    <th style="width: 60px;">Icon</th>
                    <th>Amenity Name</th>
                    <th>Slug Key</th>
                    <th>Category</th>
                    <th>Search Filter Pill</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($amenities as $amenity)
                    <tr>
                        <td class="text-center fs-5 text-secondary">
                            <i class="bi {{ $amenity->icon ?? 'bi-stars' }}"></i>
                        </td>
                        <td>
                            <div class="fw-bold text-dark">{{ $amenity->name }}</div>
                        </td>
                        <td>
                            <code class="text-secondary small">{{ $amenity->key }}</code>
                        </td>
                        <td>
                            <span class="badge bg-light text-dark border">{{ $amenity->category }}</span>
                        </td>
                        <td>
                            @if($amenity->is_filter)
                                <span class="badge bg-success-subtle text-success"><i class="bi bi-check-circle me-1"></i> Active Filter</span>
                            @else
                                <span class="badge bg-light text-muted">Feature Only</span>
                            @endif
                        </td>
                        <td class="text-end">
                            <div class="d-inline-flex gap-1">
                                <button type="button" class="btn btn-sm btn-light border" data-bs-toggle="modal" data-bs-target="#editAmenityModal{{ $amenity->id }}">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <form action="{{ route('admin.amenities.destroy', $amenity->id) }}" method="POST" onsubmit="return confirm('Delete amenity {{ $amenity->name }}?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-light border text-danger">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>

                            <!-- Edit Modal -->
                            <div class="modal fade text-start" id="editAmenityModal{{ $amenity->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <form action="{{ route('admin.amenities.update', $amenity->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-header">
                                                <h6 class="modal-title fw-bold">Edit Amenity: {{ $amenity->name }}</h6>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label class="form-label small fw-semibold">Amenity Name</label>
                                                    <input type="text" name="name" class="form-control" value="{{ $amenity->name }}" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label small fw-semibold">Key Identifier</label>
                                                    <input type="text" name="key" class="form-control font-monospace small" value="{{ $amenity->key }}" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label small fw-semibold">Category</label>
                                                    <select name="category" class="form-select" required>
                                                        <option value="General" {{ $amenity->category === 'General' ? 'selected' : '' }}>General</option>
                                                        <option value="Outdoor" {{ $amenity->category === 'Outdoor' ? 'selected' : '' }}>Outdoor</option>
                                                        <option value="Comfort" {{ $amenity->category === 'Comfort' ? 'selected' : '' }}>Comfort & Luxury</option>
                                                        <option value="Services" {{ $amenity->category === 'Services' ? 'selected' : '' }}>Services & Staff</option>
                                                        <option value="Location" {{ $amenity->category === 'Location' ? 'selected' : '' }}>Location</option>
                                                        <option value="Booking" {{ $amenity->category === 'Booking' ? 'selected' : '' }}>Booking</option>
                                                    </select>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label small fw-semibold">Bootstrap Icon Class</label>
                                                    <input type="text" name="icon" class="form-control font-monospace small" value="{{ $amenity->icon }}">
                                                    <small class="text-muted">e.g. bi-water, bi-umbrella, bi-wifi, bi-cup-hot</small>
                                                </div>
                                                <div class="form-check form-switch mb-2">
                                                    <input class="form-check-input" type="checkbox" name="is_filter" value="1" id="filter_{{ $amenity->id }}" {{ $amenity->is_filter ? 'checked' : '' }}>
                                                    <label class="form-check-label small" for="filter_{{ $amenity->id }}">Show as Quick Filter Pill in Search Bar</label>
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
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted">No amenities defined.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Add Amenity Modal -->
<div class="modal fade text-start" id="addAmenityModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('admin.amenities.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h6 class="modal-title fw-bold">Add New Amenity</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Amenity Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" placeholder="e.g. Infinity Pool, Private Jacuzzi" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Key Identifier (leave blank for auto)</label>
                        <input type="text" name="key" class="form-control font-monospace small" placeholder="e.g. jacuzzi">
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Category <span class="text-danger">*</span></label>
                        <select name="category" class="form-select" required>
                            <option value="General">General</option>
                            <option value="Outdoor">Outdoor</option>
                            <option value="Comfort">Comfort & Luxury</option>
                            <option value="Services">Services & Staff</option>
                            <option value="Location">Location</option>
                            <option value="Booking">Booking</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Bootstrap Icon Class</label>
                        <input type="text" name="icon" class="form-control font-monospace small" placeholder="bi-stars" value="bi-stars">
                    </div>
                    <div class="form-check form-switch mb-2">
                        <input class="form-check-input" type="checkbox" name="is_filter" value="1" id="new_is_filter" checked>
                        <label class="form-check-label small" for="new_is_filter">Show as Quick Filter Pill in Search Bar</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-sm btn-gold">Create Amenity</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
