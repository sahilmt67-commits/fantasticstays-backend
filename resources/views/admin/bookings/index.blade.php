@extends('layouts.admin')

@section('title', 'Manage Bookings')

@section('content')
<div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between mb-4 gap-3">
    <div>
        <h2 class="fw-bold mb-1" style="color: var(--fs-emerald);">Reservations & Stays</h2>
        <p class="text-muted mb-0 small">Track confirmed, pending, and completed bookings across all Goa villas.</p>
    </div>
</div>

<!-- Filters Card -->
<div class="card card-custom p-3 mb-4">
    <form method="GET" action="{{ route('admin.bookings.index') }}" class="row g-3 align-items-center">
        <div class="col-md-5">
            <div class="input-group">
                <span class="input-group-text bg-white border-end-0 text-muted"><i class="bi bi-search"></i></span>
                <input type="text" name="search" class="form-control border-start-0" placeholder="Search by booking ref, guest name, email, or phone..." value="{{ request('search') }}">
            </div>
        </div>
        <div class="col-sm-6 col-md-3">
            <select name="status" class="form-select">
                <option value="">All Reservation Statuses</option>
                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="confirmed" {{ request('status') === 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
            </select>
        </div>
        <div class="col-sm-6 col-md-3">
            <select name="payment_status" class="form-select">
                <option value="">All Payment Statuses</option>
                <option value="unpaid" {{ request('payment_status') === 'unpaid' ? 'selected' : '' }}>Unpaid</option>
                <option value="partial" {{ request('payment_status') === 'partial' ? 'selected' : '' }}>Partial Deposit</option>
                <option value="paid" {{ request('payment_status') === 'paid' ? 'selected' : '' }}>Fully Paid</option>
                <option value="refunded" {{ request('payment_status') === 'refunded' ? 'selected' : '' }}>Refunded</option>
            </select>
        </div>
        <div class="col-md-1 d-flex gap-2">
            <button type="submit" class="btn btn-emerald w-100"><i class="bi bi-funnel-fill"></i></button>
            @if(request()->anyFilled(['search', 'status', 'payment_status']))
                <a href="{{ route('admin.bookings.index') }}" class="btn btn-light border"><i class="bi bi-x-lg"></i></a>
            @endif
        </div>
    </form>
</div>

<!-- Bookings Table -->
<div class="card card-custom overflow-hidden">
    <div class="table-responsive">
        <table class="table table-custom mb-0">
            <thead>
                <tr>
                    <th>Ref #</th>
                    <th>Villa</th>
                    <th>Guest Details</th>
                    <th>Dates & Nights</th>
                    <th>Guests</th>
                    <th>Total Price</th>
                    <th>Status</th>
                    <th>Payment</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($bookings as $booking)
                    <tr>
                        <td>
                            <span class="fw-bold text-dark font-monospace">{{ $booking->booking_number }}</span>
                        </td>
                        <td>
                            <div class="fw-semibold text-dark">{{ $booking->villa->name ?? 'Villa' }}</div>
                            <small class="text-muted">{{ $booking->villa->location_name ?? '' }}</small>
                        </td>
                        <td>
                            <div class="fw-bold text-dark">{{ $booking->guest_name }}</div>
                            <div class="small text-muted"><i class="bi bi-telephone me-1"></i>{{ $booking->guest_phone }}</div>
                            <div class="small text-muted"><i class="bi bi-envelope me-1"></i>{{ $booking->guest_email }}</div>
                        </td>
                        <td>
                            <div class="fw-semibold">{{ $booking->check_in->format('d M Y') }}</div>
                            <div class="small text-muted">to {{ $booking->check_out->format('d M Y') }}</div>
                            <span class="badge bg-light text-dark border mt-1">{{ $booking->nights }} nights</span>
                        </td>
                        <td>
                            <div class="fw-semibold">{{ $booking->total_guests }} Guests</div>
                            <small class="text-muted">{{ $booking->adults }} Adults, {{ $booking->children }} Kids</small>
                        </td>
                        <td>
                            <div class="fw-bold fs-6 text-dark">₹{{ number_format($booking->total_amount) }}</div>
                            <small class="text-muted">₹{{ number_format($booking->price_per_night) }}/nt</small>
                        </td>
                        <td>
                            @if($booking->status === 'confirmed')
                                <span class="badge bg-success-subtle text-success">Confirmed</span>
                            @elseif($booking->status === 'pending')
                                <span class="badge bg-warning-subtle text-warning-emphasis">Pending</span>
                            @elseif($booking->status === 'completed')
                                <span class="badge bg-info-subtle text-info">Completed</span>
                            @else
                                <span class="badge bg-secondary-subtle text-secondary">Cancelled</span>
                            @endif
                        </td>
                        <td>
                            @if($booking->payment_status === 'paid')
                                <span class="badge bg-success">Paid</span>
                            @elseif($booking->payment_status === 'partial')
                                <span class="badge bg-info text-dark">Deposit</span>
                            @else
                                <span class="badge bg-danger-subtle text-danger">Unpaid</span>
                            @endif
                        </td>
                        <td class="text-end">
                            <a href="{{ route('admin.bookings.show', $booking->id) }}" class="btn btn-sm btn-light border" title="View / Manage Reservation">
                                <i class="bi bi-eye"></i> View
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="text-center py-5 text-muted">
                            <i class="bi bi-calendar-x fs-1 d-block mb-2 text-muted"></i>
                            No reservations found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($bookings->hasPages())
        <div class="p-3 border-top bg-light">
            {{ $bookings->links('pagination::bootstrap-5') }}
        </div>
    @endif
</div>
@endsection
