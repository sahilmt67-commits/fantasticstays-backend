<div class="card card-custom p-4 mb-4">
    <form action="{{ route('admin.settings.sections') }}" method="POST">
        @csrf
        <input type="hidden" name="tab" value="inspiration">
        <div class="row g-3">
            <div class="col-md-4">
                <label class="form-label small fw-semibold">Eyebrow</label>
                <input type="text" name="inspiration_eyebrow" class="form-control" value="{{ $settings['inspiration_eyebrow'] ?? 'Goa Travel Inspiration' }}">
            </div>
            <div class="col-md-8">
                <label class="form-label small fw-semibold">Heading</label>
                <input type="text" name="inspiration_heading" class="form-control" value="{{ $settings['inspiration_heading'] ?? 'Plan a Better Villa Holiday in Goa' }}">
            </div>
        </div>
        <button type="submit" class="btn btn-gold mt-3">Save Section Text</button>
    </form>
</div>

<div class="d-flex justify-content-end mb-3">
    <button type="button" class="btn btn-gold" data-bs-toggle="modal" data-bs-target="#addPostModal">
        <i class="bi bi-plus-lg"></i> Add Article
    </button>
</div>

<div class="card card-custom overflow-hidden">
    <div class="table-responsive">
        <table class="table table-custom mb-0 align-middle">
            <thead>
                <tr>
                    <th style="width:70px;">Order</th>
                    <th>Date</th>
                    <th>Title</th>
                    <th>Status</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($posts as $item)
                    <tr>
                        <td>{{ $item->display_order }}</td>
                        <td class="small">{{ $item->published_label }}</td>
                        <td class="fw-bold text-dark">{{ $item->title }}</td>
                        <td>
                            <span class="badge {{ $item->is_active ? 'bg-success-subtle text-success' : 'bg-secondary-subtle text-secondary' }}">
                                {{ $item->is_active ? 'Active' : 'Hidden' }}
                            </span>
                        </td>
                        <td class="text-end">
                            <button type="button" class="btn btn-sm btn-light border" data-bs-toggle="modal" data-bs-target="#editPost{{ $item->id }}">
                                <i class="bi bi-pencil"></i>
                            </button>
                            <form action="{{ route('admin.settings.posts.destroy', $item) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this article?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-light border text-danger"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-center text-muted py-4">No articles yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="modal fade" id="addPostModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('admin.settings.posts.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h6 class="modal-title fw-bold">Add Article</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">@include('admin.settings.tabs.fields.post', ['item' => null])</div>
                <div class="modal-footer"><button type="submit" class="btn btn-gold">Save</button></div>
            </form>
        </div>
    </div>
</div>

@foreach($posts as $item)
<div class="modal fade" id="editPost{{ $item->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('admin.settings.posts.update', $item) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h6 class="modal-title fw-bold">Edit Article</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">@include('admin.settings.tabs.fields.post', ['item' => $item])</div>
                <div class="modal-footer"><button type="submit" class="btn btn-gold">Save Changes</button></div>
            </form>
        </div>
    </div>
</div>
@endforeach
