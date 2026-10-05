<div class="mb-3">
    <label class="form-label small fw-semibold">Label</label>
    <input type="text" name="label" class="form-control" value="{{ $item->label ?? '' }}" required>
</div>
<div class="mb-3">
    <label class="form-label small fw-semibold">Number source</label>
    <select name="source" class="form-select">
        @foreach(['manual' => 'Manual number', 'villas' => 'Villa count', 'locations' => 'Location count', 'rating' => 'Average rating'] as $value => $label)
            <option value="{{ $value }}" {{ ($item->source ?? 'manual') === $value ? 'selected' : '' }}>{{ $label }}</option>
        @endforeach
    </select>
</div>
<div class="mb-3">
    <label class="form-label small fw-semibold">Manual value</label>
    <input type="text" name="value" class="form-control" value="{{ $item->value ?? '' }}" placeholder="10+">
    <small class="text-muted">Used only when the source is Manual number.</small>
</div>
<div class="mb-3">
    <label class="form-label small fw-semibold">Order</label>
    <input type="number" name="display_order" class="form-control" value="{{ $item->display_order ?? 0 }}">
</div>
<div class="form-check">
    <input type="checkbox" name="is_active" value="1" class="form-check-input" id="stat_active_{{ $item->id ?? 'new' }}" {{ ($item->is_active ?? true) ? 'checked' : '' }}>
    <label class="form-check-label" for="stat_active_{{ $item->id ?? 'new' }}">Show on homepage</label>
</div>
