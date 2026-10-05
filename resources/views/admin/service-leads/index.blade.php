@extends('layouts.admin')

@section('title', 'Service Leads')

@section('content')
<div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between mb-4 gap-3">
    <div>
        <h2 class="fw-bold mb-1" style="color: var(--fs-emerald);">Service Leads</h2>
        <p class="text-muted mb-0 small">Requests from Villas for Sale and List Your Villas.</p>
    </div>
</div>

<div class="card card-custom p-3 mb-4">
    <form method="GET" action="{{ route('admin.service-leads.index') }}" class="row g-3 align-items-center">
        <div class="col-md-5">
            <input type="text" name="search" class="form-control" placeholder="Search name, phone, email, or message" value="{{ request('search') }}">
        </div>
        <div class="col-sm-6 col-md-3">
            <select name="kind" class="form-select">
                <option value="">All pages</option>
                <option value="sale" {{ request('kind') === 'sale' ? 'selected' : '' }}>Villas for Sale</option>
                <option value="list" {{ request('kind') === 'list' ? 'selected' : '' }}>List Your Villas</option>
            </select>
        </div>
        <div class="col-sm-6 col-md-2">
            <select name="status" class="form-select">
                <option value="">All statuses</option>
                <option value="new" {{ request('status') === 'new' ? 'selected' : '' }}>New</option>
                <option value="contacted" {{ request('status') === 'contacted' ? 'selected' : '' }}>Contacted</option>
                <option value="closed" {{ request('status') === 'closed' ? 'selected' : '' }}>Closed</option>
            </select>
        </div>
        <div class="col-md-2 d-flex gap-2">
            <button type="submit" class="btn btn-emerald w-100">Filter</button>
            @if(request()->anyFilled(['search', 'kind', 'status']))
                <a href="{{ route('admin.service-leads.index') }}" class="btn btn-light border">Clear</a>
            @endif
        </div>
    </form>
</div>

<div class="card card-custom overflow-hidden">
    <div class="table-responsive">
        <table class="table table-custom mb-0">
            <thead>
                <tr>
                    <th>Lead Name</th>
                    <th>Page</th>
                    <th>Message</th>
                    <th>Status</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($leads as $lead)
                    <tr>
                        <td>
                            <div class="fw-bold text-dark">{{ $lead->name }}</div>
                            <div class="small text-muted">{{ $lead->phone }}</div>
                            @if($lead->email)
                                <div class="small text-muted">{{ $lead->email }}</div>
                            @endif
                        </td>
                        <td>
                            <span class="badge bg-light text-dark border">
                                {{ $lead->kind === 'list' ? 'List Your Villas' : 'Villas for Sale' }}
                            </span>
                        </td>
                        <td>
                            <div class="small text-secondary" style="max-width: 280px;">{{ $lead->message ?: '—' }}</div>
                        </td>
                        <td>
                            @if($lead->status === 'new')
                                <span class="badge bg-danger">New</span>
                            @elseif($lead->status === 'contacted')
                                <span class="badge bg-primary-subtle text-primary">Contacted</span>
                            @else
                                <span class="badge bg-secondary-subtle text-secondary">Closed</span>
                            @endif
                        </td>
                        <td class="text-end">
                            <div class="d-inline-flex gap-1 align-items-center">
                                @php
                                    $cleanPhone = preg_replace('/[^0-9]/', '', $lead->phone);
                                    if (!str_starts_with($cleanPhone, '91') && strlen($cleanPhone) === 10) {
                                        $cleanPhone = '91' . $cleanPhone;
                                    }
                                @endphp
                                <a href="https://wa.me/{{ $cleanPhone }}" target="_blank" class="btn btn-sm btn-success rounded-pill px-2 py-1" title="WhatsApp">
                                    <i class="bi bi-whatsapp"></i>
                                </a>
                                <form action="{{ route('admin.service-leads.status', $lead) }}" method="POST" class="d-inline-flex">
                                    @csrf
                                    @method('PUT')
                                    <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                                        <option value="new" {{ $lead->status === 'new' ? 'selected' : '' }}>New</option>
                                        <option value="contacted" {{ $lead->status === 'contacted' ? 'selected' : '' }}>Contacted</option>
                                        <option value="closed" {{ $lead->status === 'closed' ? 'selected' : '' }}>Closed</option>
                                    </select>
                                </form>
                                <form action="{{ route('admin.service-leads.destroy', $lead) }}" method="POST" onsubmit="return confirm('Delete this lead?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-light border text-danger px-2 py-1" title="Delete">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center py-5 text-muted">No service leads yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($leads->hasPages())
        <div class="p-3 border-top bg-light">
            {{ $leads->links('pagination::bootstrap-5') }}
        </div>
    @endif
</div>
@endsection
