@php
    $mainImage = $settings['listing_image'] ?? '/images/demo/home-villa-specialist-welcoming-guests-at-a-luxury-goa-.webp';
    $smallImage = $settings['listing_image_small'] ?? '/images/demo/loc-assagao.webp';
    $preview = function (string $path) {
        return str_starts_with($path, '/images/') ? 'http://localhost:3000'.$path : $path;
    };
@endphp

<div class="card card-custom p-4">
    <form action="{{ route('admin.settings.sections') }}" method="POST">
        @csrf
        <input type="hidden" name="tab" value="listing">
        <p class="text-muted small mb-3">Shown at the top of the villas listing page. Villa count and guest rating stay calculated from live villas.</p>
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label small fw-semibold">Gold label</label>
                <input type="text" name="listing_eyebrow" class="form-control" value="{{ $settings['listing_eyebrow'] ?? 'Handpicked Villas · Goa, India' }}">
            </div>
            <div class="col-md-6">
                <label class="form-label small fw-semibold">Image badge</label>
                <input type="text" name="listing_badge" class="form-control" value="{{ $settings['listing_badge'] ?? 'Assagao · North Goa' }}">
            </div>
            <div class="col-12">
                <label class="form-label small fw-semibold">Heading</label>
                <input type="text" name="listing_heading" class="form-control" value="{{ $settings['listing_heading'] ?? 'Luxury Villas in Goa for an Exceptional Private Stay' }}">
            </div>
            <div class="col-12">
                <label class="form-label small fw-semibold">First paragraph</label>
                <textarea name="listing_paragraph_1" rows="4" class="form-control">{{ $settings['listing_paragraph_1'] ?? 'Explore our handpicked collection of <strong>luxury villas in Goa</strong>, featuring private pools, stylish interiors and carefully selected locations. Whether you are planning a family holiday, a group getaway or a special celebration, find a villa that gives you privacy, comfort and easy access to Goa\'s beaches, restaurants and attractions.' }}</textarea>
                <small class="text-muted">Use &lt;strong&gt; around words that should be bold.</small>
            </div>
            <div class="col-12">
                <label class="form-label small fw-semibold">Second paragraph</label>
                <textarea name="listing_paragraph_2" rows="3" class="form-control">{{ $settings['listing_paragraph_2'] ?? 'From <strong>private pool villas</strong> in the leafy lanes of Assagao to <strong>holiday villas</strong> along the quiet southern coast, every <strong>Goa villa for rent</strong> in our portfolio is visited in person before it joins the collection.' }}</textarea>
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-semibold">Villas label</label>
                <input type="text" name="listing_stat_villas_label" class="form-control" value="{{ $settings['listing_stat_villas_label'] ?? 'Curated Villas' }}">
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-semibold">Rating label</label>
                <input type="text" name="listing_stat_rating_label" class="form-control" value="{{ $settings['listing_stat_rating_label'] ?? 'Guest Rating' }}">
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-semibold">Response time</label>
                <input type="text" name="listing_response" class="form-control" value="{{ $settings['listing_response'] ?? '30 min' }}">
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-semibold">Response label</label>
                <input type="text" name="listing_response_label" class="form-control" value="{{ $settings['listing_response_label'] ?? 'Specialist Response' }}">
            </div>
            <div class="col-12">
                <label class="form-label small fw-semibold">Main photo</label>
                <div class="d-flex align-items-center gap-3">
                    <img id="listing_main_preview" src="{{ $preview($mainImage) }}" alt="" width="72" height="90" style="object-fit:cover;border-radius:8px;">
                    <input type="text" id="listing_image" name="listing_image" class="form-control" value="{{ $mainImage }}" readonly>
                    <label class="btn btn-outline-dark mb-0">
                        Browse
                        <input type="file" accept="image/*" class="d-none" onchange="uploadListingImage(this, 'listing_image', 'listing_main_preview')">
                    </label>
                </div>
            </div>
            <div class="col-12">
                <label class="form-label small fw-semibold">Small photo</label>
                <div class="d-flex align-items-center gap-3">
                    <img id="listing_small_preview" src="{{ $preview($smallImage) }}" alt="" width="72" height="90" style="object-fit:cover;border-radius:8px;">
                    <input type="text" id="listing_image_small" name="listing_image_small" class="form-control" value="{{ $smallImage }}" readonly>
                    <label class="btn btn-outline-dark mb-0">
                        Browse
                        <input type="file" accept="image/*" class="d-none" onchange="uploadListingImage(this, 'listing_image_small', 'listing_small_preview')">
                    </label>
                </div>
            </div>
        </div>
        <div id="listing_upload_progress" class="small text-muted mt-2 d-none">Uploading…</div>
        <button type="submit" class="btn btn-gold mt-3">Save Category List Page</button>
    </form>
</div>

<script>
function uploadListingImage(fileInput, inputId, previewId) {
    const file = fileInput.files && fileInput.files[0];
    if (!file) return;
    const progress = document.getElementById('listing_upload_progress');
    if (progress) progress.classList.remove('d-none');
    const formData = new FormData();
    formData.append('image', file);
    formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);
    fetch(@json(route('admin.settings.upload-image')), { method: 'POST', body: formData })
        .then(function (response) { return response.json(); })
        .then(function (data) {
            if (!data.url) {
                alert('Upload failed');
                return;
            }
            document.getElementById(inputId).value = data.url;
            document.getElementById(previewId).src = data.url;
        })
        .catch(function (error) { alert('Upload error: ' + error); })
        .finally(function () {
            if (progress) progress.classList.add('d-none');
            fileInput.value = '';
        });
}
</script>
