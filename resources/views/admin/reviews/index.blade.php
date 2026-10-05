@extends('layouts.admin')

@section('title', 'Guest Reviews')

@section('content')
<div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between mb-4 gap-3">
    <div>
        <h2 class="fw-bold mb-1" style="color: var(--fs-emerald);">Guest Reviews Moderation</h2>
        <p class="text-muted mb-0 small">Moderate, verify, and highlight guest reviews and ratings on the website and villa pages.</p>
    </div>
</div>

<!-- Filters Card -->
<div class="card card-custom p-3 mb-4">
    <form method="GET" action="{{ route('admin.reviews.index') }}" class="row g-3 align-items-center">
        <div class="col-md-6">
            <select name="villa_id" class="form-select">
                <option value="">All Villas</option>
                @foreach($villas as $villa)
                    <option value="{{ $villa->id }}" {{ request('villa_id') == $villa->id ? 'selected' : '' }}>{{ $villa->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-4">
            <select name="status" class="form-select">
                <option value="">All Review Statuses</option>
                <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approved Only</option>
                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending Moderation</option>
            </select>
        </div>
        <div class="col-md-2 d-flex gap-2">
            <button type="submit" class="btn btn-emerald w-100"><i class="bi bi-funnel-fill"></i></button>
            @if(request()->anyFilled(['villa_id', 'status']))
                <a href="{{ route('admin.reviews.index') }}" class="btn btn-light border"><i class="bi bi-x-lg"></i></a>
            @endif
        </div>
    </form>
</div>

<!-- Reviews Table -->
<div class="card card-custom overflow-hidden">
    <div class="table-responsive">
        <table class="table table-custom mb-0">
            <thead>
                <tr>
                    <th>Guest</th>
                    <th>Villa</th>
                    <th>Rating</th>
                    <th>Detailed Breakdown</th>
                    <th style="max-width: 320px;">Review Comment</th>
                    <th>Home Feature</th>
                    <th>Status</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($reviews as $rev)
                    <tr>
                        <td>
                            <div class="fw-bold text-dark">{{ $rev->guest_name }}</div>
                            <small class="text-muted">{{ $rev->guest_location ?? 'Guest' }} &bull; {{ $rev->stay_date ?? '' }}</small>
                        </td>
                        <td>
                            <div class="fw-semibold text-dark">{{ $rev->villa->name ?? 'Villa' }}</div>
                        </td>
                        <td>
                            <div class="d-flex align-items-center text-warning fw-bold">
                                <i class="bi bi-star-fill me-1"></i>
                                <span class="text-dark fs-6">{{ number_format($rev->rating, 1) }}</span>
                            </div>
                        </td>
                        <td>
                            <div class="small text-muted" style="font-size: 0.78rem;">
                                Cleanliness: <strong>{{ $rev->cleanliness_rating }}</strong> &bull;
                                Accuracy: <strong>{{ $rev->accuracy_rating }}</strong><br>
                                Location: <strong>{{ $rev->location_rating }}</strong> &bull;
                                Value: <strong>{{ $rev->value_rating }}</strong>
                            </div>
                        </td>
                        <td style="max-width: 320px;">
                            <p class="text-dark small mb-0">
                                "{{ Str::limit($rev->comment, 120) }}"
                            </p>
                        </td>
                        <td>
                            <form action="{{ route('admin.reviews.featured', $rev->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-sm {{ $rev->is_featured ? 'btn-warning text-dark' : 'btn-light border text-muted' }}" title="Toggle Feature on Home Page">
                                    <i class="bi {{ $rev->is_featured ? 'bi-star-fill' : 'bi-star' }}"></i>
                                </button>
                            </form>
                        </td>
                        <td>
                            @if($rev->is_approved)
                                <span class="badge bg-success-subtle text-success">Approved</span>
                            @else
                                <span class="badge bg-warning-subtle text-warning-emphasis">Pending</span>
                            @endif
                        </td>
                        <td class="text-end">
                            <div class="d-inline-flex gap-1">
                                <button type="button" class="btn btn-sm btn-light border" data-bs-toggle="modal" data-bs-target="#editReview{{ $rev->id }}" title="Edit review">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <form action="{{ route('admin.reviews.approval', $rev->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-sm {{ $rev->is_approved ? 'btn-outline-secondary' : 'btn-success' }}" title="{{ $rev->is_approved ? 'Unapprove' : 'Approve Review' }}">
                                        <i class="bi {{ $rev->is_approved ? 'bi-x-circle' : 'bi-check-circle' }}"></i>
                                        {{ $rev->is_approved ? 'Hide' : 'Approve' }}
                                    </button>
                                </form>
                                <form action="{{ route('admin.reviews.destroy', $rev->id) }}" method="POST" onsubmit="return confirm('Delete this review?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-light border text-danger">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center py-5 text-muted">
                            <i class="bi bi-chat-square-quote fs-1 d-block mb-2 text-muted"></i>
                            No reviews found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @foreach($reviews as $rev)
    <div class="modal fade" id="editReview{{ $rev->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form action="{{ route('admin.reviews.update', $rev) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h6 class="modal-title fw-bold">Edit Review</h6>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label small fw-semibold">Guest Name</label>
                            <input type="text" name="guest_name" class="form-control" value="{{ $rev->guest_name }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-semibold">Guest Location</label>
                            <input type="text" name="guest_location" class="form-control" value="{{ $rev->guest_location }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-semibold">Villa</label>
                            <select name="villa_id" class="form-select" required>
                                @foreach($villas as $villa)
                                    <option value="{{ $villa->id }}" {{ $rev->villa_id == $villa->id ? 'selected' : '' }}>{{ $villa->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="row g-3">
                            <div class="col-4">
                                <label class="form-label small fw-semibold">Rating</label>
                                <input type="number" name="rating" class="form-control" min="1" max="5" step="0.1" value="{{ $rev->rating }}" required>
                            </div>
                            <div class="col-8">
                                <label class="form-label small fw-semibold">Stay Date</label>
                                <input type="text" name="stay_date" class="form-control" value="{{ $rev->stay_date }}">
                            </div>
                        </div>
                        <div class="mt-3">
                            <label class="form-label small fw-semibold">Review Comment</label>
                            <textarea name="comment" rows="4" class="form-control" required>{{ $rev->comment }}</textarea>
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

    @if($reviews->hasPages())
        <div class="p-3 border-top bg-light">
            {{ $reviews->links('pagination::bootstrap-5') }}
        </div>
    @endif
</div>
@endsection
