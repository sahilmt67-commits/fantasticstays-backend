@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between mb-4 gap-3">
    <div>
        <h2 class="fw-bold mb-1" style="color: var(--fs-emerald);">Luxury Villa Operations</h2>
        <p class="text-muted mb-0 small">Welcome back! Overview of your Goa luxury properties, reservations, and customer enquiries.</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.villas.create') }}" class="btn btn-gold d-inline-flex align-items-center gap-2">
            <i class="bi bi-plus-lg"></i> Add New Villa
        </a>
        <a href="{{ route('admin.enquiries.index') }}" class="btn btn-outline-dark d-inline-flex align-items-center gap-2">
            <i class="bi bi-chat-left-dots"></i> View Enquiries
        </a>
    </div>
</div>

<!-- 4 Key Stat Cards -->
<div class="row g-4 mb-5">
    <div class="col-sm-6 col-xl-3">
        <div class="card stat-card p-4">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <span class="text-muted fw-semibold small text-uppercase" style="letter-spacing: 0.8px;">Total Properties</span>
                <div class="stat-icon-wrapper" style="background: rgba(11, 37, 29, 0.08); color: var(--fs-emerald);">
                    <i class="bi bi-house-door-fill"></i>
                </div>
            </div>
            <div class="d-flex align-items-baseline gap-2">
                <h3 class="fw-bold mb-0" style="color: var(--fs-emerald);">{{ $totalVillas }}</h3>
                <span class="badge bg-success-subtle text-success small">{{ $activeVillas }} Active</span>
            </div>
            <div class="text-muted small mt-2">
                <i class="bi bi-check2-circle text-success me-1"></i> Live on Next.js frontend
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-xl-3">
        <div class="card stat-card p-4">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <span class="text-muted fw-semibold small text-uppercase" style="letter-spacing: 0.8px;">Reservations</span>
                <div class="stat-icon-wrapper" style="background: rgba(197, 168, 128, 0.15); color: var(--fs-gold-dark);">
                    <i class="bi bi-calendar-check-fill"></i>
                </div>
            </div>
            <div class="d-flex align-items-baseline gap-2">
                <h3 class="fw-bold mb-0" style="color: var(--fs-emerald);">{{ $totalBookings }}</h3>
                @if($pendingBookings > 0)
                    <span class="badge bg-warning-subtle text-warning-emphasis small">{{ $pendingBookings }} Pending</span>
                @else
                    <span class="badge bg-light text-muted small">0 Pending</span>
                @endif
            </div>
            <div class="text-muted small mt-2">
                <i class="bi bi-clock-history me-1"></i> Villa stays scheduled
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-xl-3">
        <div class="card stat-card p-4">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <span class="text-muted fw-semibold small text-uppercase" style="letter-spacing: 0.8px;">WhatsApp / Web Leads</span>
                <div class="stat-icon-wrapper" style="background: rgba(25, 135, 84, 0.12); color: #198754;">
                    <i class="bi bi-whatsapp"></i>
                </div>
            </div>
            <div class="d-flex align-items-baseline gap-2">
                <h3 class="fw-bold mb-0" style="color: var(--fs-emerald);">{{ $totalEnquiries }}</h3>
                @if($newEnquiries > 0)
                    <span class="badge bg-danger-subtle text-danger small">{{ $newEnquiries }} New</span>
                @endif
            </div>
            <div class="text-muted small mt-2">
                <i class="bi bi-chat-text text-primary me-1"></i> Quick 1-click WhatsApp response
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-xl-3">
        <div class="card stat-card p-4">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <span class="text-muted fw-semibold small text-uppercase" style="letter-spacing: 0.8px;">Confirmed Revenue</span>
                <div class="stat-icon-wrapper" style="background: rgba(169, 133, 60, 0.12); color: var(--fs-gold-dark);">
                    <i class="bi bi-currency-rupee"></i>
                </div>
            </div>
            <div class="d-flex align-items-baseline gap-2">
                <h3 class="fw-bold mb-0" style="color: var(--fs-emerald);">₹{{ number_format($totalRevenue) }}</h3>
            </div>
            <div class="text-muted small mt-2">
                <i class="bi bi-graph-up-arrow text-success me-1"></i> Booked villa revenue
            </div>
        </div>
    </div>
</div>

<!-- Two Column Layout: Recent Bookings & Inquiries -->
<div class="row g-4 mb-5">
    <!-- Recent Bookings Table -->
    <div class="col-xl-7">
        <div class="card card-custom h-100">
            <div class="card-header bg-white py-3 px-4 d-flex align-items-center justify-content-between border-bottom">
                <h5 class="fw-bold mb-0" style="color: var(--fs-emerald);"><i class="bi bi-calendar-week me-2 text-warning"></i> Recent Bookings</h5>
                <a href="{{ route('admin.bookings.index') }}" class="btn btn-sm btn-link text-decoration-none text-muted">View All <i class="bi bi-arrow-right"></i></a>
            </div>
            <div class="table-responsive">
                <table class="table table-custom mb-0">
                    <thead>
                        <tr>
                            <th>Booking Ref</th>
                            <th>Villa & Guest</th>
                            <th>Dates</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentBookings as $booking)
                            <tr>
                                <td>
                                    <span class="fw-bold text-dark">{{ $booking->booking_number }}</span>
                                </td>
                                <td>
                                    <div class="fw-semibold text-dark">{{ $booking->villa->name ?? 'Villa' }}</div>
                                    <div class="text-muted small"><i class="bi bi-person me-1"></i>{{ $booking->guest_name }}</div>
                                </td>
                                <td>
                                    <div class="small fw-semibold">{{ $booking->check_in->format('d M') }} - {{ $booking->check_out->format('d M Y') }}</div>
                                    <div class="text-muted small">{{ $booking->nights }} nights &bull; {{ $booking->total_guests }} guests</div>
                                </td>
                                <td>
                                    <span class="fw-bold text-dark">₹{{ number_format($booking->total_amount) }}</span>
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
                                    <a href="{{ route('admin.bookings.show', $booking->id) }}" class="btn btn-sm btn-light border px-2 py-1">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">No reservations recorded yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Recent Inquiries & WhatsApp Leads -->
    <div class="col-xl-5">
        <div class="card card-custom h-100">
            <div class="card-header bg-white py-3 px-4 d-flex align-items-center justify-content-between border-bottom">
                <h5 class="fw-bold mb-0" style="color: var(--fs-emerald);"><i class="bi bi-whatsapp me-2 text-success"></i> Customer Enquiries</h5>
                <a href="{{ route('admin.enquiries.index') }}" class="btn btn-sm btn-link text-decoration-none text-muted">View All <i class="bi bi-arrow-right"></i></a>
            </div>
            <div class="card-body p-0">
                <div class="list-group list-group-flush">
                    @forelse($recentEnquiries as $enquiry)
                        <div class="list-group-item p-3 d-flex flex-column gap-2">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="fw-bold text-dark">{{ $enquiry->name }}</div>
                                <span class="badge {{ $enquiry->status === 'new' ? 'bg-danger' : 'bg-secondary-subtle text-dark' }} small">
                                    {{ ucfirst($enquiry->status) }}
                                </span>
                            </div>
                            <div class="text-muted small">
                                <span class="fw-semibold text-secondary">{{ $enquiry->villa_name ?? 'General Inquiry' }}</span>
                                @if($enquiry->check_in)
                                    &bull; <span>{{ \Carbon\Carbon::parse($enquiry->check_in)->format('d M') }} - {{ \Carbon\Carbon::parse($enquiry->check_out)->format('d M') }}</span>
                                @endif
                                @if($enquiry->guests)
                                    &bull; <span>{{ $enquiry->guests }} Guests</span>
                                @endif
                            </div>
                            @if($enquiry->message)
                                <p class="text-muted small mb-1 fst-italic bg-light p-2 rounded">
                                    "{{ Str::limit($enquiry->message, 90) }}"
                                </p>
                            @endif
                            <div class="d-flex align-items-center justify-content-between pt-1">
                                <small class="text-muted"><i class="bi bi-telephone me-1"></i>{{ $enquiry->phone }}</small>
                                @php
                                    $cleanPhone = preg_replace('/[^0-9]/', '', $enquiry->phone);
                                    if (!str_starts_with($cleanPhone, '91') && strlen($cleanPhone) === 10) {
                                        $cleanPhone = '91' . $cleanPhone;
                                    }
                                    $waMsg = urlencode("Hi {$enquiry->name}, this is Fantastic Stays Luxury Villas regarding your inquiry for " . ($enquiry->villa_name ?? 'Goa villa stay') . ". How can we assist you?");
                                @endphp
                                <a href="https://wa.me/{{ $cleanPhone }}?text={{ $waMsg }}" target="_blank" class="btn btn-sm btn-success d-inline-flex align-items-center gap-1 rounded-pill px-3 py-1">
                                    <i class="bi bi-whatsapp"></i> Chat on WhatsApp
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="p-4 text-center text-muted">No customer inquiries yet.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Goa Locations & Curated Villa Summary -->
<div class="card card-custom p-4">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h5 class="fw-bold mb-1" style="color: var(--fs-emerald);"><i class="bi bi-geo-alt-fill me-2 text-danger"></i> Goa Destinations Portfolio</h5>
            <p class="text-muted small mb-0">Active villas distributed across North and South Goa locations.</p>
        </div>
        <a href="{{ route('admin.locations.index') }}" class="btn btn-sm btn-outline-secondary">Manage Locations</a>
    </div>

    <div class="row g-3">
        @foreach($locations as $loc)
            <div class="col-6 col-md-4 col-xl-3">
                <div class="p-3 border rounded-3 bg-light d-flex align-items-center justify-content-between">
                    <div>
                        <div class="fw-bold text-dark">{{ $loc->name }}</div>
                        <small class="text-muted">{{ $loc->region }}</small>
                    </div>
                    <span class="badge bg-dark rounded-pill">{{ $loc->villas_count }} villas</span>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection
