<div class="row">
    <div class="col-lg-8">
        <div class="card card-custom p-4">
            <form action="{{ route('admin.settings.update') }}" method="POST">
                @csrf

                <h5 class="fw-bold mb-3" style="color: var(--fs-emerald);"><i class="bi bi-building text-warning me-2"></i> Brand & Identity</h5>
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">Site Name</label>
                        <input type="text" name="site_name" class="form-control" value="{{ $settings['site_name'] ?? 'Fantastic Stays' }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">Brand Tagline</label>
                        <input type="text" name="site_tagline" class="form-control" value="{{ $settings['site_tagline'] ?? 'Luxury Private Villas in Goa' }}">
                    </div>
                </div>

                @php
                    $heroImage = $settings['hero_image'] ?? '/images/demo/hero-cover.webp';
                    $heroPreview = str_starts_with($heroImage, '/images/') ? 'http://localhost:3000'.$heroImage : $heroImage;
                @endphp
                <h5 class="fw-bold mb-3" style="color: var(--fs-emerald);"><i class="bi bi-image text-warning me-2"></i> Homepage banner</h5>
                <p class="text-muted small mb-3">The cover photo and the text above the search bar. The search bar itself stays as it is.</p>
                <div class="row g-3 mb-4">
                    <div class="col-12">
                        <label class="form-label small fw-semibold">Banner image</label>
                        <div class="d-flex align-items-center gap-3">
                            <img id="hero_preview" src="{{ $heroPreview }}" alt="" width="96" height="64" style="object-fit:cover;border-radius:8px;">
                            <input type="text" id="hero_image" name="hero_image" class="form-control" value="{{ $heroImage }}" readonly>
                            <label class="btn btn-outline-dark mb-0">
                                Browse
                                <input type="file" accept="image/*" class="d-none" onchange="uploadHeroBanner(this)">
                            </label>
                        </div>
                        <div id="hero_upload_progress" class="small text-muted mt-2 d-none">Uploading…</div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">Gold label</label>
                        <input type="text" name="hero_eyebrow" class="form-control" value="{{ $settings['hero_eyebrow'] ?? 'Goa · Private Estates' }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">Heading, italic line</label>
                        <input type="text" name="hero_title_lead" class="form-control" value="{{ $settings['hero_title_lead'] ?? 'Experience' }}">
                    </div>
                    <div class="col-md-8">
                        <label class="form-label small fw-semibold">Heading, large line</label>
                        <input type="text" name="hero_title_main" class="form-control" value="{{ $settings['hero_title_main'] ?? 'Luxury Villa Living' }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-semibold">Heading, last line</label>
                        <input type="text" name="hero_title_end" class="form-control" value="{{ $settings['hero_title_end'] ?? 'in Goa' }}">
                    </div>
                    <div class="col-12">
                        <label class="form-label small fw-semibold">Intro</label>
                        <textarea name="hero_subtitle" rows="3" class="form-control">{{ $settings['hero_subtitle'] ?? 'Discover handpicked private villas in Goa for relaxing family holidays, romantic escapes, group getaways, weddings and memorable celebrations.' }}</textarea>
                    </div>
                </div>

                <h5 class="fw-bold mb-3" style="color: var(--fs-emerald);"><i class="bi bi-whatsapp text-success me-2"></i> Contact details</h5>
                <p class="text-muted small mb-3">Used in the header, the contact page, and the footer.</p>
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">WhatsApp Helpline Number</label>
                        <input type="text" name="whatsapp_number" class="form-control" placeholder="+918860331188" value="{{ $settings['whatsapp_number'] ?? '+918860331188' }}">
                        <small class="text-muted">International format without spaces (e.g. +918860331188)</small>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">Contact Phone Number</label>
                        <input type="text" name="contact_phone" class="form-control" value="{{ $settings['contact_phone'] ?? '+91 88603 31188' }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">Concierge Email</label>
                        <input type="email" name="contact_email" class="form-control" value="{{ $settings['contact_email'] ?? 'Booking@fantasticstays.com' }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">Concierge Support Text</label>
                        <input type="text" name="concierge_support" class="form-control" value="{{ $settings['concierge_support'] ?? '24/7 Dedicated Luxury Concierge Service' }}">
                    </div>
                    <div class="col-12">
                        <label class="form-label small fw-semibold">Office Address</label>
                        <textarea name="office_address" rows="4" class="form-control">{{ $settings['office_address'] ?? "House No. 4/1635 Probavaddo\nCalangute, Bardez, Goa-403516\nContact: 8860331188" }}</textarea>
                        <small class="text-muted">Each line shows on its own row in the footer.</small>
                    </div>
                </div>

                <h5 class="fw-bold mb-3" style="color: var(--fs-emerald);"><i class="bi bi-chat-left-text text-warning me-2"></i> Contact page</h5>
                <div class="row g-3 mb-4">
                    <div class="col-12">
                        <label class="form-label small fw-semibold">Heading</label>
                        <input type="text" name="contact_heading" class="form-control" value="{{ $settings['contact_heading'] ?? 'Speak with a Villa Specialist' }}">
                    </div>
                    <div class="col-12">
                        <label class="form-label small fw-semibold">Intro</label>
                        <textarea name="contact_intro" rows="3" class="form-control">{{ $settings['contact_intro'] ?? 'Share your travel dates and requirements. Our team typically responds within 2 hours between 9 am and 9 pm IST.' }}</textarea>
                    </div>
                </div>

                <h5 class="fw-bold mb-3" style="color: var(--fs-emerald);"><i class="bi bi-layout-text-window-reverse text-warning me-2"></i> Footer brand</h5>
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">Footer label</label>
                        <input type="text" name="footer_kicker" class="form-control" value="{{ $settings['footer_kicker'] ?? 'Goa · Private Estates' }}">
                    </div>
                    <div class="col-12">
                        <label class="form-label small fw-semibold">Footer description</label>
                        <textarea name="footer_about" rows="3" class="form-control">{{ $settings['footer_about'] ?? 'Handpicked private villas in Goa for families, couples, groups, weddings and corporate retreats — with personalised booking support and dedicated guest assistance.' }}</textarea>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-semibold">Instagram URL</label>
                        <input type="url" name="social_instagram" class="form-control" value="{{ $settings['social_instagram'] ?? 'https://instagram.com' }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-semibold">Facebook URL</label>
                        <input type="url" name="social_facebook" class="form-control" value="{{ $settings['social_facebook'] ?? 'https://facebook.com' }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-semibold">YouTube URL</label>
                        <input type="url" name="social_youtube" class="form-control" value="{{ $settings['social_youtube'] ?? 'https://youtube.com' }}">
                    </div>
                </div>

                <h5 class="fw-bold mb-3" style="color: var(--fs-emerald);"><i class="bi bi-cash-stack text-primary me-2"></i> Financial & Tax Rates</h5>
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">Currency Symbol</label>
                        <input type="text" name="currency_symbol" class="form-control" value="{{ $settings['currency_symbol'] ?? '₹' }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">GST / Luxury Tax Rate (%)</label>
                        <div class="input-group">
                            <input type="number" step="0.1" name="tax_rate_percent" class="form-control" value="{{ $settings['tax_rate_percent'] ?? '18' }}">
                            <span class="input-group-text">%</span>
                        </div>
                    </div>
                </div>

                <h5 class="fw-bold mb-3" style="color: var(--fs-emerald);"><i class="bi bi-search text-info me-2"></i> Global SEO & Meta Defaults</h5>
                <div class="row g-3 mb-4">
                    <div class="col-12">
                        <label class="form-label small fw-semibold">Default Meta Title</label>
                        <input type="text" name="default_meta_title" class="form-control" value="{{ $settings['default_meta_title'] ?? 'Fantastic Stays - Luxury Private Villas in Goa with Private Pool' }}">
                    </div>
                    <div class="col-12">
                        <label class="form-label small fw-semibold">Default Meta Description</label>
                        <textarea name="default_meta_description" rows="2" class="form-control">{{ $settings['default_meta_description'] ?? 'Discover and book handpicked luxury villas with private pools, personal chefs, and 24/7 concierge service across North and South Goa.' }}</textarea>
                    </div>
                    <div class="col-12">
                        <label class="form-label small fw-semibold">Default Meta Keywords</label>
                        <input type="text" name="default_meta_keywords" class="form-control" value="{{ $settings['default_meta_keywords'] ?? 'luxury villas in goa, private pool villa, holiday homes assagao, anjuna luxury villa, beachfront villa goa' }}">
                    </div>
                </div>

                <button type="submit" class="btn btn-gold py-2 px-4">
                    <i class="bi bi-check2-circle me-1"></i> Save All Settings
                </button>
            </form>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card card-custom p-4 mb-4">
            <h6 class="fw-bold mb-2 text-dark"><i class="bi bi-info-circle-fill text-primary me-2"></i> Homepage sections</h6>
            <p class="text-muted small mb-0">Use the tabs above to edit Goa Experiences, the About block, travel articles and FAQs. The site reads them from <code>GET /api/v1/homepage-sections</code>.</p>
        </div>
        <div class="card card-custom p-4">
            <h6 class="fw-bold mb-2 text-dark"><i class="bi bi-shield-lock-fill text-success me-2"></i> Default Admin Credentials</h6>
            <p class="text-muted small mb-2">Email: <code>admin@fantasticstays.com</code></p>
            <p class="text-muted small mb-0">Password: <code>admin123</code></p>
        </div>
    </div>
</div>

<script>
function uploadHeroBanner(fileInput) {
    const file = fileInput.files && fileInput.files[0];
    if (!file) return;
    const progress = document.getElementById('hero_upload_progress');
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
            document.getElementById('hero_image').value = data.url;
            document.getElementById('hero_preview').src = data.url;
        })
        .catch(function (error) { alert('Upload error: ' + error); })
        .finally(function () {
            if (progress) progress.classList.add('d-none');
            fileInput.value = '';
        });
}
</script>
