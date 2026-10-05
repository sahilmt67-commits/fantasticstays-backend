<div class="mb-3">
    <label class="form-label small fw-semibold">Date label</label>
    <input type="text" name="published_label" class="form-control" value="{{ $item->published_label ?? '' }}" placeholder="12 Jul 2025">
</div>
<div class="mb-3">
    <label class="form-label small fw-semibold">Title</label>
    <input type="text" name="title" class="form-control" value="{{ $item->title ?? '' }}" required>
</div>
<div class="mb-3">
    <label class="form-label small fw-semibold">Excerpt</label>
    <textarea name="excerpt" rows="3" class="form-control">{{ $item->excerpt ?? '' }}</textarea>
</div>
<div class="mb-3">
    <label class="form-label small fw-semibold">Order</label>
    <input type="number" name="display_order" class="form-control" value="{{ $item->display_order ?? 0 }}">
</div>
<div class="form-check">
    <input type="checkbox" name="is_active" value="1" class="form-check-input" id="post_active_{{ $item->id ?? 'new' }}" {{ ($item->is_active ?? true) ? 'checked' : '' }}>
    <label class="form-check-label" for="post_active_{{ $item->id ?? 'new' }}">Show on homepage</label>
</div>
