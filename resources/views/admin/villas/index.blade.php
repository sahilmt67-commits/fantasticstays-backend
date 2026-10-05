@extends('layouts.admin')

@section('title', 'Manage Villas')

@section('content')
<div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between mb-4 gap-3">
    <div>
        <h2 class="fw-bold mb-1" style="color: var(--fs-emerald);">Luxury Villas Catalog</h2>
        <p class="text-muted mb-0 small">Manage your portfolio of luxury private villas, pricing, capacities, and photo galleries.</p>
    </div>
    <div>
        <a href="{{ route('admin.villas.create') }}" class="btn btn-gold d-inline-flex align-items-center gap-2">
            <i class="bi bi-plus-lg"></i> Add New Villa
        </a>
    </div>
</div>

<!-- Filters Card -->
<div class="card card-custom p-3 mb-4">
    <form method="GET" action="{{ route('admin.villas.index') }}" class="row g-3 align-items-center">
        <div class="col-md-4">
            <div class="input-group">
                <span class="input-group-text bg-white border-end-0 text-muted"><i class="bi bi-search"></i></span>
                <input type="text" name="search" class="form-control border-start-0" placeholder="Search by name or location..." value="{{ request('search') }}">
            </div>
        </div>
        <div class="col-sm-6 col-md-3">
            <select name="region" class="form-select">
                <option value="">All Regions</option>
                <option value="North Goa" {{ request('region') === 'North Goa' ? 'selected' : '' }}>North Goa</option>
                <option value="South Goa" {{ request('region') === 'South Goa' ? 'selected' : '' }}>South Goa</option>
            </select>
        </div>
        <div class="col-sm-6 col-md-2">
            <select name="status" class="form-select">
                <option value="">All Statuses</option>
                <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                <option value="maintenance" {{ request('status') === 'maintenance' ? 'selected' : '' }}>Maintenance</option>
            </select>
        </div>
        <div class="col-sm-6 col-md-2">
            <select name="featured" class="form-select">
                <option value="">Featured Any</option>
                <option value="1" {{ request('featured') === '1' ? 'selected' : '' }}>Featured Only</option>
            </select>
        </div>
        <div class="col-sm-6 col-md-1 d-flex gap-2">
            <button type="submit" class="btn btn-emerald w-100"><i class="bi bi-funnel-fill"></i></button>
            @if(request()->anyFilled(['search', 'region', 'status', 'featured']))
                <a href="{{ route('admin.villas.index') }}" class="btn btn-light border"><i class="bi bi-x-lg"></i></a>
            @endif
        </div>
    </form>
</div>

<!-- Villas Table -->
<div class="card card-custom overflow-hidden">
    <div class="table-responsive">
        <table class="table table-custom mb-0">
            <thead>
                <tr>
                    <th style="width: 80px;">Image</th>
                    <th>Villa Name & Location</th>
                    <th>Price / Night</th>
                    <th>Specs</th>
                    <th>Rating</th>
                    <th>Featured</th>
                    <th>Status</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($villas as $villa)
                    <tr>
                        <td>
                            <img src="{{ $villa->image }}" alt="{{ $villa->name }}" class="rounded-3 shadow-sm object-fit-cover" style="width: 65px; height: 50px; background-color: #eee;" onerror="this.src='/images/demo/casa-serenity.webp'">
                        </td>
                        <td>
                            <div class="fw-bold text-dark fs-6">{{ $villa->name }}</div>
                            <div class="d-flex align-items-center gap-2 text-muted small mt-1">
                                <span class="badge bg-light text-dark border"><i class="bi bi-geo-alt-fill text-danger me-1"></i>{{ $villa->location_name ?? ($villa->location->name ?? 'Goa') }}</span>
                                <span>&bull; {{ $villa->region }}</span>
                                @if($villa->badge)
                                    <span class="badge bg-warning-subtle text-warning-emphasis">{{ $villa->badge }}</span>
                                @endif
                            </div>
                        </td>
                        <td>
                            <div class="fw-bold text-dark fs-6">₹{{ number_format($villa->price_per_night) }}</div>
                            <small class="text-muted">per night</small>
                        </td>
                        <td>
                            <div class="small fw-semibold text-secondary">
                                <i class="bi bi-door-closed me-1"></i>{{ $villa->bedrooms }} BHK &bull;
                                <i class="bi bi-people me-1"></i>{{ $villa->guests }} Guests
                            </div>
                            <small class="text-muted">{{ $villa->bathrooms }} Baths &bull; {{ $villa->beds }} Beds</small>
                        </td>
                        <td>
                            <div class="d-flex align-items-center gap-1 text-warning fw-bold small">
                                <i class="bi bi-star-fill"></i>
                                <span class="text-dark">{{ number_format($villa->rating, 1) }}</span>
                            </div>
                            <small class="text-muted">({{ $villa->reviews_count }} reviews)</small>
                        </td>
                        <td>
                            <form action="{{ route('admin.villas.featured', $villa->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-sm {{ $villa->is_featured ? 'btn-warning text-dark' : 'btn-light border text-muted' }}" title="Toggle Featured">
                                    <i class="bi {{ $villa->is_featured ? 'bi-star-fill' : 'bi-star' }}"></i>
                                </button>
                            </form>
                        </td>
                        <td>
                            @if($villa->status === 'active')
                                <span class="badge bg-success-subtle text-success">Active</span>
                            @elseif($villa->status === 'maintenance')
                                <span class="badge bg-warning-subtle text-warning-emphasis">Maintenance</span>
                            @else
                                <span class="badge bg-secondary-subtle text-secondary">Inactive</span>
                            @endif
                        </td>
                        <td class="text-end">
                            <div class="d-inline-flex gap-1">
                                <a href="{{ route('admin.villas.rooms', $villa->id) }}" class="btn btn-sm btn-outline-primary" title="Manage Bedrooms & Layout">
                                    <i class="bi bi-layout-text-window"></i> Rooms
                                </a>
                                <a href="{{ route('admin.villas.edit', $villa->id) }}" class="btn btn-sm btn-light border" title="Edit Villa">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                                <form action="{{ route('admin.villas.destroy', $villa->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete villa {{ $villa->name }}?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-light border text-danger" title="Delete Villa">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center py-5 text-muted">
                            <i class="bi bi-house-x fs-1 d-block mb-2 text-muted"></i>
                            No luxury villas found matching your criteria.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($villas->hasPages())
        <div class="p-3 border-top bg-light">
            {{ $villas->links('pagination::bootstrap-5') }}
        </div>
    @endif
</div>
@endsection
