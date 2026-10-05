@php
    $reviewCount = old('reviews_count', isset($villa) ? $villa->reviews_count : 0);
    $reviewsRaw = old('reviews_raw', isset($villa) ? $villa->reviewsAsText() : '');
    $faqRows = old('faqs', isset($villa) ? $villa->faqRows() : []);
    if (! is_array($faqRows) || $faqRows === []) {
        $faqRows = [['question' => '', 'answer' => '']];
    }
@endphp

<div class="card card-custom p-4 mb-4">
    <h5 class="fw-bold mb-2" style="color: var(--fs-emerald);">Guest Reviews</h5>
    <p class="text-muted small mb-3">These cards appear in the Guest Reviews section on the villa page. The large star number uses the Rating field under Capacity &amp; Pricing.</p>

    <div class="mb-3" style="max-width: 220px;">
        <label class="form-label fw-semibold small">Verified review count</label>
        <input type="number" name="reviews_count" min="0" class="form-control" value="{{ $reviewCount }}">
    </div>

    <label class="form-label fw-semibold small">Reviews</label>
    <textarea name="reviews_raw" rows="10" class="form-control font-monospace small" placeholder="Priya &amp; Kabir Sen | Delhi NCR | December 2025 | 5&#10;5 | 5 | 5 | 5 | 4.9&#10;The villa was quiet, the pool was perfect, and the team arranged dinner.">{{ $reviewsRaw }}</textarea>
    <div class="form-text">One review per block, with a blank line between reviews. First line: Name | City | Stay date | Rating. Second line: Cleanliness | Accuracy | Service | Location | Value. Remaining lines are the comment.</div>
</div>

<div class="card card-custom p-4 mb-4">
    <div class="d-flex align-items-center justify-content-between gap-3 mb-2">
        <h5 class="fw-bold mb-0" style="color: var(--fs-emerald);">FAQ</h5>
        <button type="button" class="btn btn-sm btn-outline-secondary" id="faq-add">
            <i class="bi bi-plus-lg me-1"></i> Add question
        </button>
    </div>
    <p class="text-muted small mb-3">Each question opens on the villa detail page. Leave a row empty to skip it.</p>
    <div id="faq-list" class="d-flex flex-column gap-3">
        @foreach($faqRows as $faq)
            <div class="faq-row border rounded-3 p-3 bg-light">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <label class="form-label small fw-semibold mb-0">Question</label>
                    <button type="button" class="btn btn-sm btn-light border text-danger faq-remove">Remove</button>
                </div>
                <input type="text" name="faqs[{{ $loop->index }}][question]" class="form-control mb-2" value="{{ $faq['question'] ?? '' }}" placeholder="What is the cancellation policy?">
                <label class="form-label small fw-semibold">Answer</label>
                <textarea name="faqs[{{ $loop->index }}][answer]" rows="3" class="form-control" placeholder="Bookings cancelled up to 14 days before check-in receive a full refund.">{{ $faq['answer'] ?? '' }}</textarea>
            </div>
        @endforeach
    </div>
</div>
<template id="faq-template">
    <div class="faq-row border rounded-3 p-3 bg-light">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <label class="form-label small fw-semibold mb-0">Question</label>
            <button type="button" class="btn btn-sm btn-light border text-danger faq-remove">Remove</button>
        </div>
        <input type="text" data-name="question" class="form-control mb-2" placeholder="What is the cancellation policy?">
        <label class="form-label small fw-semibold">Answer</label>
        <textarea data-name="answer" rows="3" class="form-control" placeholder="Bookings cancelled up to 14 days before check-in receive a full refund."></textarea>
    </div>
</template>
<script>
(function () {
    const list = document.getElementById('faq-list');
    const add = document.getElementById('faq-add');
    const template = document.getElementById('faq-template');
    if (!list || !add || !template) return;

    function reindex() {
        list.querySelectorAll('.faq-row').forEach(function (row, index) {
            const question = row.querySelector('[data-name="question"], [name$="[question]"]');
            const answer = row.querySelector('[data-name="answer"], [name$="[answer]"]');
            if (question) question.name = 'faqs[' + index + '][question]';
            if (answer) answer.name = 'faqs[' + index + '][answer]';
        });
    }

    add.addEventListener('click', function () {
        list.appendChild(template.content.cloneNode(true));
        reindex();
    });

    list.addEventListener('click', function (event) {
        const button = event.target.closest('.faq-remove');
        if (!button) return;
        const rows = list.querySelectorAll('.faq-row');
        if (rows.length === 1) {
            rows[0].querySelectorAll('input, textarea').forEach(function (field) { field.value = ''; });
            return;
        }
        button.closest('.faq-row').remove();
        reindex();
    });
})();
</script>
