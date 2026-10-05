<div class="mb-3">
    <label class="form-label small fw-semibold">Question</label>
    <input type="text" name="question" class="form-control" value="{{ $item->question ?? '' }}" required>
</div>
<div class="mb-3">
    <label class="form-label small fw-semibold">Answer</label>
    <textarea name="answer" rows="4" class="form-control" required>{{ $item->answer ?? '' }}</textarea>
</div>
<div class="mb-3">
    <label class="form-label small fw-semibold">Order</label>
    <input type="number" name="display_order" class="form-control" value="{{ $item->display_order ?? 0 }}">
</div>
<div class="form-check">
    <input type="checkbox" name="is_active" value="1" class="form-check-input" id="faq_active_{{ $item->id ?? 'new' }}" {{ ($item->is_active ?? true) ? 'checked' : '' }}>
    <label class="form-check-label" for="faq_active_{{ $item->id ?? 'new' }}">Show on homepage</label>
</div>
