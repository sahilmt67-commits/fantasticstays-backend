@extends('layouts.admin')

@section('title', 'Why Choose')

@section('content')
<div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between mb-4 gap-3">
    <div>
        <h2 class="fw-bold mb-1" style="color: var(--fs-emerald);">Why Choose Fantastic Stays</h2>
        <p class="text-muted mb-0 small">These points appear on the homepage under “More Than a Villa, Your Private Goa Experience”.</p>
    </div>
    <button type="button" class="btn btn-gold d-inline-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#addWhyChooseModal">
        <i class="bi bi-plus-lg"></i> Add Point
    </button>
</div>

<div class="card card-custom overflow-hidden">
    <div class="table-responsive">
        <table class="table table-custom mb-0 align-middle">
            <thead>
                <tr>
                    <th style="width:70px;">Order</th>
                    <th>Title</th>
                    <th>Description</th>
                    <th>Icon</th>
                    <th>Status</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($points as $point)
                    <tr>
                        <td>{{ $point->display_order }}</td>
                        <td class="fw-bold text-dark">{{ $point->title }}</td>
                        <td class="text-muted small">{{ \Illuminate\Support\Str::limit($point->description, 90) }}</td>
                        <td><code class="small">{{ $point->icon }}</code></td>
                        <td>
                            <span class="badge {{ $point->is_active ? 'bg-success-subtle text-success' : 'bg-secondary-subtle text-secondary' }}">
                                {{ $point->is_active ? 'Active' : 'Hidden' }}
                            </span>
                        </td>
                        <td class="text-end">
                            <button type="button" class="btn btn-sm btn-light border" data-bs-toggle="modal" data-bs-target="#editWhyChoose{{ $point->id }}">
                                <i class="bi bi-pencil"></i>
                            </button>
                            <form action="{{ route('admin.why-choose.destroy', $point) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this point?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-light border text-danger">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">No points yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@foreach($points as $point)
<div class="modal fade" id="editWhyChoose{{ $point->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('admin.why-choose.update', $point) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h6 class="modal-title fw-bold">Edit Point</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Title</label>
                        <input type="text" name="title" class="form-control" value="{{ $point->title }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Description</label>
                        <textarea name="description" rows="3" class="form-control small">{{ $point->description }}</textarea>
                    </div>
                    <div class="row g-3">
                        <div class="col-7">
                            <label class="form-label small fw-semibold">Icon</label>
                            <select name="icon" class="form-select" required>
                                @foreach($icons as $icon)
                                    <option value="{{ $icon }}" {{ $point->icon === $icon ? 'selected' : '' }}>{{ $icon }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-5">
                            <label class="form-label small fw-semibold">Order</label>
                            <input type="number" name="display_order" class="form-control" value="{{ $point->display_order }}">
                        </div>
                    </div>
                    <div class="form-check form-switch mt-3">
                        <input class="form-check-input" type="checkbox" name="is_active" value="1" id="why_active_{{ $point->id }}" {{ $point->is_active ? 'checked' : '' }}>
                        <label class="form-check-label small" for="why_active_{{ $point->id }}">Show on homepage</label>
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
@endforeach

<div class="modal fade" id="addWhyChooseModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('admin.why-choose.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h6 class="modal-title fw-bold">Add Point</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Title <span class="text-danger">*</span></label>
                        <input type="text" name="title" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Description</label>
                        <textarea name="description" rows="3" class="form-control small"></textarea>
                    </div>
                    <div class="row g-3">
                        <div class="col-7">
                            <label class="form-label small fw-semibold">Icon</label>
                            <select name="icon" class="form-select" required>
                                @foreach($icons as $icon)
                                    <option value="{{ $icon }}">{{ $icon }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-5">
                            <label class="form-label small fw-semibold">Order</label>
                            <input type="number" name="display_order" class="form-control" value="{{ ($points->max('display_order') ?? 0) + 1 }}">
                        </div>
                    </div>
                    <div class="form-check form-switch mt-3">
                        <input class="form-check-input" type="checkbox" name="is_active" value="1" id="why_active_new" checked>
                        <label class="form-check-label small" for="why_active_new">Show on homepage</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-sm btn-gold">Create Point</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
