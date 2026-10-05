@extends('layouts.admin')

@section('title', 'Reservation ' . $booking->booking_number)

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h2 class="fw-bold mb-1" style="color: var(--fs-emerald);">Reservation Details</h2>
        <p class="text-muted mb-0 small">Booking Number: <span class="font-monospace fw-bold text-dark">{{ $booking->booking_number }}</span> &bull; Booked on {{ $booking->created_at->format('d M Y, h:i A') }}</p>
    </div>
    <a href="{{ route('admin.bookings.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i> Back to Bookings
    </a>
</div>

<div class="row g-4">
    <!-- Left Column: Details -->
    <div class="col-lg-8">
        <!-- Villa & Stay Summary -->
        <div class="card card-custom p-4 mb-4">
            <h5 class="fw-bold mb-3" style="color: var(--fs-emerald);"><i class="bi bi-house-door-fill text-warning me-2"></i> Villa Stay Information</h5>

            <div class="d-flex align-items-center gap-3 p-3 bg-light rounded-3 mb-4">
                <img src="{{ $booking->villa->image ?? '/images/demo/casa-serenity.webp' }}" alt="{{ $booking->villa->name ?? '' }}" class="rounded-3 object-fit-cover shadow-sm" style="width: 100px; height: 75px;">
                <div>
                    <h5 class="fw-bold mb-1 text-dark">{{ $booking->villa->name ?? 'Villa' }}</h5>
                    <p class="text-muted small mb-0"><i class="bi bi-geo-alt-fill text-danger me-1"></i>{{ $booking->villa->location_name ?? '' }} &bull; {{ $booking->villa->region ?? '' }}</p>
                </div>
            </div>

            <div class="row g-3">
                <div class="col-sm-6 col-md-3">
                    <small class="text-muted text-uppercase fw-semibold" style="font-size: 0.75rem;">Check-in</small>
                    <div class="fw-bold fs-6 text-dark">{{ $booking->check_in->format('D, d M Y') }}</div>
                    <small class="text-muted">{{ $booking->villa->check_in_time ?? '02:00 PM' }}</small>
                </div>
                <div class="col-sm-6 col-md-3">
                    <small class="text-muted text-uppercase fw-semibold" style="font-size: 0.75rem;">Check-out</small>
                    <div class="fw-bold fs-6 text-dark">{{ $booking->check_out->format('D, d M Y') }}</div>
                    <small class="text-muted">{{ $booking->villa->check_out_time ?? '11:00 AM' }}</small>
                </div>
                <div class="col-sm-6 col-md-3">
                    <small class="text-muted text-uppercase fw-semibold" style="font-size: 0.75rem;">Duration</small>
                    <div class="fw-bold fs-6 text-dark">{{ $booking->nights }} Nights</div>
                </div>
                <div class="col-sm-6 col-md-3">
                    <small class="text-muted text-uppercase fw-semibold" style="font-size: 0.75rem;">Total Guests</small>
                    <div class="fw-bold fs-6 text-dark">{{ $booking->total_guests }} Guests</div>
                    <small class="text-muted">{{ $booking->adults }} Adults, {{ $booking->children }} Children</small>
                </div>
            </div>
        </div>

        <!-- Guest Information Card -->
        <div class="card card-custom p-4 mb-4">
            <h5 class="fw-bold mb-3" style="color: var(--fs-emerald);"><i class="bi bi-person-badge-fill text-primary me-2"></i> Primary Guest Details</h5>

            <div class="row g-3">
                <div class="col-md-4">
                    <small class="text-muted">Guest Name</small>
                    <div class="fw-bold text-dark fs-6">{{ $booking->guest_name }}</div>
                </div>
                <div class="col-md-4">
                    <small class="text-muted">Phone Number</small>
                    <div class="fw-bold text-dark">
                        <a href="tel:{{ $booking->guest_phone }}" class="text-decoration-none text-dark">
                            <i class="bi bi-telephone text-success me-1"></i>{{ $booking->guest_phone }}
                        </a>
                    </div>
                </div>
                <div class="col-md-4">
                    <small class="text-muted">Email Address</small>
                    <div class="fw-bold text-dark">
                        <a href="mailto:{{ $booking->guest_email }}" class="text-decoration-none text-dark">
                            <i class="bi bi-envelope text-primary me-1"></i>{{ $booking->guest_email }}
                        </a>
                    </div>
                </div>
            </div>

            @if($booking->special_requests)
                <div class="mt-4 p-3 bg-light rounded-3">
                    <div class="fw-semibold small text-secondary mb-1"><i class="bi bi-chat-left-quote me-1"></i> Special Requests & Preferences:</div>
                    <div class="text-dark small">{{ $booking->special_requests }}</div>
                </div>
            @endif
        </div>

        <!-- Price Breakdown -->
        <div class="card card-custom p-4">
            <h5 class="fw-bold mb-3" style="color: var(--fs-emerald);"><i class="bi bi-receipt text-success me-2"></i> Financial Invoice Breakdown</h5>

            <div class="table-responsive">
                <table class="table mb-0">
                    <tbody>
                        <tr>
                            <td class="text-muted">Rate per night</td>
                            <td class="text-end fw-semibold">₹{{ number_format($booking->price_per_night) }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Nights stay ({{ $booking->nights }} nights)</td>
                            <td class="text-end fw-semibold">₹{{ number_format($booking->subtotal) }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Luxury Hospitality & GST (18%)</td>
                            <td class="text-end fw-semibold">₹{{ number_format($booking->taxes) }}</td>
                        </tr>
                        <tr class="table-light">
                            <td class="fw-bold fs-6">Grand Total Amount</td>
                            <td class="text-end fw-bold fs-5 text-dark" style="color: var(--fs-gold-dark) !important;">₹{{ number_format($booking->total_amount) }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Right Column: Status & Operations -->
    <div class="col-lg-4">
        <div class="card card-custom p-4 mb-4">
            <h5 class="fw-bold mb-3" style="color: var(--fs-emerald);"><i class="bi bi-sliders me-2"></i> Update Status</h5>

            <form action="{{ route('admin.bookings.status', $booking->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label fw-semibold small">Reservation Status</label>
                    <select name="status" class="form-select">
                        <option value="pending" {{ $booking->status === 'pending' ? 'selected' : '' }}>Pending Confirmation</option>
                        <option value="confirmed" {{ $booking->status === 'confirmed' ? 'selected' : '' }}>Confirmed (Dates Reserved)</option>
                        <option value="completed" {{ $booking->status === 'completed' ? 'selected' : '' }}>Completed (Stay Finished)</option>
                        <option value="cancelled" {{ $booking->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold small">Payment Status</label>
                    <select name="payment_status" class="form-select">
                        <option value="unpaid" {{ $booking->payment_status === 'unpaid' ? 'selected' : '' }}>Unpaid</option>
                        <option value="partial" {{ $booking->payment_status === 'partial' ? 'selected' : '' }}>Partial Deposit Received</option>
                        <option value="paid" {{ $booking->payment_status === 'paid' ? 'selected' : '' }}>Fully Paid</option>
                        <option value="refunded" {{ $booking->payment_status === 'refunded' ? 'selected' : '' }}>Refunded</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label class="form-label fw-semibold small">Internal Admin Notes</label>
                    <textarea name="admin_notes" rows="3" class="form-control small" placeholder="Any internal notes or coordination with caretaker/chef...">{{ $booking->admin_notes }}</textarea>
                </div>

                <button type="submit" class="btn btn-gold w-100 py-2 mb-3">
                    <i class="bi bi-check2-circle me-1"></i> Save Changes
                </button>
            </form>

            @php
                $cleanPhone = preg_replace('/[^0-9]/', '', $booking->guest_phone);
                if (!str_starts_with($cleanPhone, '91') && strlen($cleanPhone) === 10) {
                    $cleanPhone = '91' . $cleanPhone;
                }
                $waMsg = urlencode("Hello {$booking->guest_name}, this is Fantastic Stays confirming your reservation #{$booking->booking_number} for {$booking->villa->name}. Your check-in is on {$booking->check_in->format('d M Y')}.");
            @endphp
            <a href="https://wa.me/{{ $cleanPhone }}?text={{ $waMsg }}" target="_blank" class="btn btn-outline-success w-100 py-2 d-inline-flex align-items-center justify-content-center gap-2">
                <i class="bi bi-whatsapp"></i> Send WhatsApp Confirmation
            </a>
        </div>

        <div class="card card-custom p-4 border-danger-subtle">
            <h6 class="fw-bold text-danger mb-2">Danger Zone</h6>
            <p class="text-muted small mb-3">Permanently remove this reservation record.</p>
            <form action="{{ route('admin.bookings.destroy', $booking->id) }}" method="POST" onsubmit="return confirm('Delete this reservation completely?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-sm btn-outline-danger w-100">
                    <i class="bi bi-trash me-1"></i> Delete Booking Record
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
