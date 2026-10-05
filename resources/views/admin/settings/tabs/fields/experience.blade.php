<div class="mb-3">
    <label class="form-label small fw-semibold">Title</label>
    <input type="text" name="title" class="form-control" value="{{ $item->title ?? '' }}" required>
</div>
<div class="mb-3">
    <label class="form-label small fw-semibold">Description</label>
    <textarea name="description" rows="3" class="form-control">{{ $item->description ?? '' }}</textarea>
</div>
<div class="mb-3">
    <label class="form-label small fw-semibold">Order</label>
    <input type="number" name="display_order" class="form-control" value="{{ $item->display_order ?? 0 }}">
</div>
<div class="form-check">
    <input type="checkbox" name="is_active" value="1" class="form-check-input" id="exp_active_{{ $item->id ?? 'new' }}" {{ ($item->is_active ?? true) ? 'checked' : '' }}>
    <label class="form-check-label" for="exp_active_{{ $item->id ?? 'new' }}">Show on homepage</label>
</div>
