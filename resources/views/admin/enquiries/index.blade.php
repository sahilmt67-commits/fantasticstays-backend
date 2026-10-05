@extends('layouts.admin')

@section('title', 'Customer Enquiries')

@section('content')
<div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between mb-4 gap-3">
    <div>
        <h2 class="fw-bold mb-1" style="color: var(--fs-emerald);">Customer Enquiries & Leads</h2>
        <p class="text-muted mb-0 small">Manage booking leads from website forms and WhatsApp inquiry triggers.</p>
    </div>
</div>

<!-- Filters Card -->
<div class="card card-custom p-3 mb-4">
    <form method="GET" action="{{ route('admin.enquiries.index') }}" class="row g-3 align-items-center">
        <div class="col-md-6">
            <div class="input-group">
                <span class="input-group-text bg-white border-end-0 text-muted"><i class="bi bi-search"></i></span>
                <input type="text" name="search" class="form-control border-start-0" placeholder="Search by name, phone, email, or villa..." value="{{ request('search') }}">
            </div>
        </div>
        <div class="col-sm-6 col-md-4">
            <select name="status" class="form-select">
                <option value="">All Lead Statuses</option>
                <option value="new" {{ request('status') === 'new' ? 'selected' : '' }}>New Leads</option>
                <option value="contacted" {{ request('status') === 'contacted' ? 'selected' : '' }}>Contacted</option>
                <option value="qualified" {{ request('status') === 'qualified' ? 'selected' : '' }}>Qualified</option>
                <option value="booked" {{ request('status') === 'booked' ? 'selected' : '' }}>Converted / Booked</option>
                <option value="closed" {{ request('status') === 'closed' ? 'selected' : '' }}>Closed</option>
            </select>
        </div>
        <div class="col-sm-6 col-md-2 d-flex gap-2">
            <button type="submit" class="btn btn-emerald w-100"><i class="bi bi-funnel-fill"></i></button>
            @if(request()->anyFilled(['search', 'status']))
                <a href="{{ route('admin.enquiries.index') }}" class="btn btn-light border"><i class="bi bi-x-lg"></i></a>
            @endif
        </div>
    </form>
</div>

<!-- Enquiries List Table -->
<div class="card card-custom overflow-hidden">
    <div class="table-responsive">
        <table class="table table-custom mb-0">
            <thead>
                <tr>
                    <th>Lead Name</th>
                    <th>Villa Interest</th>
                    <th>Check-in</th>
                    <th>Check-out</th>
                    <th>Guests</th>
                    <th>Preferred location</th>
                    <th>Message</th>
                    <th>Status</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($enquiries as $enquiry)
                    <tr>
                        <td>
                            <div class="fw-bold text-dark">{{ $enquiry->name }}</div>
                            <div class="small text-muted"><i class="bi bi-telephone me-1"></i>{{ $enquiry->phone }}</div>
                            @if($enquiry->email)
                                <div class="small text-muted"><i class="bi bi-envelope me-1"></i>{{ $enquiry->email }}</div>
                            @endif
                        </td>
                        <td>
                            <span class="badge bg-light text-dark border">{{ $enquiry->villa_name ?? ($enquiry->villa->name ?? 'General Inquiry') }}</span>
                            <div class="small text-muted mt-1"><i class="bi bi-arrow-up-right-circle me-1"></i>Via {{ ucfirst($enquiry->source) }}</div>
                        </td>
                        <td>
                            <span class="fw-semibold small">{{ $enquiry->check_in ? $enquiry->check_in->format('d M Y') : '—' }}</span>
                        </td>
                        <td>
                            <span class="fw-semibold small">{{ $enquiry->check_out ? $enquiry->check_out->format('d M Y') : '—' }}</span>
                        </td>
                        <td>
                            <span class="fw-semibold">{{ $enquiry->guests ? $enquiry->guests . ' Guests' : '—' }}</span>
                        </td>
                        <td>
                            <div class="small text-dark" style="max-width: 180px;">
                                {{ $enquiry->preferred_location ?: '—' }}
                            </div>
                        </td>
                        <td>
                            <div class="small text-secondary" style="max-width: 220px;">
                                {{ $enquiry->message ?: '—' }}
                            </div>
                        </td>
                        <td>
                            @if($enquiry->status === 'new')
                                <span class="badge bg-danger">New Lead</span>
                            @elseif($enquiry->status === 'contacted')
                                <span class="badge bg-primary-subtle text-primary">Contacted</span>
                            @elseif($enquiry->status === 'qualified')
                                <span class="badge bg-info-subtle text-info">Qualified</span>
                            @elseif($enquiry->status === 'booked')
                                <span class="badge bg-success">Booked</span>
                            @else
                                <span class="badge bg-secondary-subtle text-secondary">Closed</span>
                            @endif
                        </td>
                        <td class="text-end">
                            <div class="d-inline-flex gap-1 align-items-center">
                                @php
                                    $cleanPhone = preg_replace('/[^0-9]/', '', $enquiry->phone);
                                    if (!str_starts_with($cleanPhone, '91') && strlen($cleanPhone) === 10) {
                                        $cleanPhone = '91' . $cleanPhone;
                                    }
                                    $waMsg = urlencode("Hello {$enquiry->name}, this is Fantastic Stays Luxury Villas regarding your inquiry for " . ($enquiry->villa_name ?? 'Goa villa stay') . ".");
                                @endphp
                                <a href="https://wa.me/{{ $cleanPhone }}?text={{ $waMsg }}" target="_blank" class="btn btn-sm btn-success rounded-pill px-2 py-1" title="Chat on WhatsApp">
                                    <i class="bi bi-whatsapp"></i>
                                </a>

                                <!-- Status Updater Modal Trigger -->
                                <button type="button" class="btn btn-sm btn-light border px-2 py-1" data-bs-toggle="modal" data-bs-target="#statusModal{{ $enquiry->id }}" title="Change Status">
                                    <i class="bi bi-pencil"></i>
                                </button>

                                <form action="{{ route('admin.enquiries.destroy', $enquiry->id) }}" method="POST" onsubmit="return confirm('Delete this inquiry?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-light border text-danger px-2 py-1" title="Delete">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>

                            <!-- Modal for Status Update -->
                            <div class="modal fade" id="statusModal{{ $enquiry->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content text-start">
                                        <form action="{{ route('admin.enquiries.status', $enquiry->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-header">
                                                <h6 class="modal-title fw-bold">Update Lead: {{ $enquiry->name }}</h6>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label class="form-label small fw-semibold">Lead Status</label>
                                                    <select name="status" class="form-select">
                                                        <option value="new" {{ $enquiry->status === 'new' ? 'selected' : '' }}>New Lead</option>
                                                        <option value="contacted" {{ $enquiry->status === 'contacted' ? 'selected' : '' }}>Contacted (Follow up)</option>
                                                        <option value="qualified" {{ $enquiry->status === 'qualified' ? 'selected' : '' }}>Qualified (Dates available)</option>
                                                        <option value="booked" {{ $enquiry->status === 'booked' ? 'selected' : '' }}>Booked (Converted)</option>
                                                        <option value="closed" {{ $enquiry->status === 'closed' ? 'selected' : '' }}>Closed (Lost / Inactive)</option>
                                                    </select>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label small fw-semibold">Staff Notes</label>
                                                    <textarea name="admin_notes" rows="3" class="form-control small" placeholder="Customer requirements, quote offered...">{{ $enquiry->admin_notes }}</textarea>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-sm btn-light" data-bs-dismiss="modal">Cancel</button>
                                                <button type="submit" class="btn btn-sm btn-gold">Save Status</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="text-center py-5 text-muted">
                            <i class="bi bi-inbox fs-1 d-block mb-2 text-muted"></i>
                            No customer inquiries received yet.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($enquiries->hasPages())
        <div class="p-3 border-top bg-light">
            {{ $enquiries->links('pagination::bootstrap-5') }}
        </div>
    @endif
</div>
@endsection
