<div class="card card-custom p-4">
    <form action="{{ route('admin.settings.sections') }}" method="POST">
        @csrf
        <input type="hidden" name="tab" value="services">

        <h5 class="fw-bold mb-1" style="color: var(--fs-emerald);">Villas for Sale</h5>
        <p class="text-muted small mb-3">Text on /villas-for-sale. The enquiry form fields stay fixed.</p>
        <div class="row g-3">
            <div class="col-md-4">
                <label class="form-label small fw-semibold">Gold label</label>
                <input type="text" name="sale_eyebrow" class="form-control" value="{{ $settings['sale_eyebrow'] ?? 'Private Ownership · Goa' }}">
            </div>
            <div class="col-md-8">
                <label class="form-label small fw-semibold">Heading</label>
                <input type="text" name="sale_heading" class="form-control" value="{{ $settings['sale_heading'] ?? 'Villas available to buy in Goa' }}">
            </div>
            <div class="col-12">
                <label class="form-label small fw-semibold">Intro</label>
                <textarea name="sale_intro" rows="2" class="form-control">{{ $settings['sale_intro'] ?? 'Looking for a home you can keep, not just a week away. Tell us the location, size, and budget you have in mind.' }}</textarea>
            </div>
            <div class="col-md-4">
                <label class="form-label small fw-semibold">Point 1</label>
                <input type="text" name="sale_point_1" class="form-control" value="{{ $settings['sale_point_1'] ?? 'We shortlist villas that match how you want to live in Goa.' }}">
            </div>
            <div class="col-md-4">
                <label class="form-label small fw-semibold">Point 2</label>
                <input type="text" name="sale_point_2" class="form-control" value="{{ $settings['sale_point_2'] ?? 'You hear about price, condition, and what is included before you travel.' }}">
            </div>
            <div class="col-md-4">
                <label class="form-label small fw-semibold">Point 3</label>
                <input type="text" name="sale_point_3" class="form-control" value="{{ $settings['sale_point_3'] ?? 'One person stays with you from the first call to the viewing.' }}">
            </div>
            <div class="col-md-6">
                <label class="form-label small fw-semibold">Form heading</label>
                <input type="text" name="sale_form_heading" class="form-control" value="{{ $settings['sale_form_heading'] ?? 'Tell us what you are looking for' }}">
            </div>
            <div class="col-12">
                <label class="form-label small fw-semibold">Meta Title</label>
                <input type="text" name="sale_meta_title" class="form-control" value="{{ $settings['sale_meta_title'] ?? 'Villas for Sale in Goa | Fantastic Stays' }}">
            </div>
            <div class="col-12">
                <label class="form-label small fw-semibold">Meta Description</label>
                <textarea name="sale_meta_description" rows="2" class="form-control">{{ $settings['sale_meta_description'] ?? 'Looking for a private villa to buy in Goa. Share the location, size, and budget, and a specialist will shortlist homes that are on the market.' }}</textarea>
            </div>
            <div class="col-12">
                <label class="form-label small fw-semibold">Meta Keywords</label>
                <input type="text" name="sale_meta_keywords" class="form-control" value="{{ $settings['sale_meta_keywords'] ?? 'villas for sale in goa, buy villa goa, luxury villa for sale assagao, north goa villa purchase' }}">
            </div>
        </div>

        <hr class="my-4">

        <h5 class="fw-bold mb-1" style="color: var(--fs-emerald);">List Your Villas</h5>
        <p class="text-muted small mb-3">Text on /list-your-villas. The enquiry form fields stay fixed.</p>
        <div class="row g-3">
            <div class="col-md-4">
                <label class="form-label small fw-semibold">Gold label</label>
                <input type="text" name="list_eyebrow" class="form-control" value="{{ $settings['list_eyebrow'] ?? 'Owners · Goa' }}">
            </div>
            <div class="col-md-8">
                <label class="form-label small fw-semibold">Heading</label>
                <input type="text" name="list_heading" class="form-control" value="{{ $settings['list_heading'] ?? 'Let us look after your villa' }}">
            </div>
            <div class="col-12">
                <label class="form-label small fw-semibold">Intro</label>
                <textarea name="list_intro" rows="3" class="form-control">{{ $settings['list_intro'] ?? 'If you own a private villa in Goa and want it let to the right guests, send a few details. We will tell you honestly whether it fits the collection.' }}</textarea>
            </div>
            @for($i = 1; $i <= 4; $i++)
                @php
                    $statDefaults = [
                        1 => ['Visited in person', 'Every home'],
                        2 => ['North & South', 'Goa'],
                        3 => ['One desk', 'Enquiry to stay'],
                        4 => ['Your terms', 'Agreed up front'],
                    ];
                @endphp
                <div class="col-md-3">
                    <label class="form-label small fw-semibold">Stat {{ $i }} value</label>
                    <input type="text" name="list_stat_{{ $i }}_value" class="form-control" value="{{ $settings['list_stat_'.$i.'_value'] ?? $statDefaults[$i][0] }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label small fw-semibold">Stat {{ $i }} label</label>
                    <input type="text" name="list_stat_{{ $i }}_label" class="form-control" value="{{ $settings['list_stat_'.$i.'_label'] ?? $statDefaults[$i][1] }}">
                </div>
            @endfor
            @php
                $benefitDefaults = [
                    1 => ['A named contact', 'You are not passed between departments. One specialist knows the house.'],
                    2 => ['Guests who fit the house', 'We match group size and the way the villa is meant to be used.'],
                    3 => ['Care between stays', 'Housekeeping, checks, and upkeep are agreed before the first booking.'],
                    4 => ['Clear reporting', 'You see what was booked and what is still open.'],
                ];
            @endphp
            @for($i = 1; $i <= 4; $i++)
                <div class="col-md-4">
                    <label class="form-label small fw-semibold">Point {{ $i }} title</label>
                    <input type="text" name="list_benefit_{{ $i }}_title" class="form-control" value="{{ $settings['list_benefit_'.$i.'_title'] ?? $benefitDefaults[$i][0] }}">
                </div>
                <div class="col-md-8">
                    <label class="form-label small fw-semibold">Point {{ $i }} text</label>
                    <input type="text" name="list_benefit_{{ $i }}_text" class="form-control" value="{{ $settings['list_benefit_'.$i.'_text'] ?? $benefitDefaults[$i][1] }}">
                </div>
            @endfor
            <div class="col-md-6">
                <label class="form-label small fw-semibold">Form heading</label>
                <input type="text" name="list_form_heading" class="form-control" value="{{ $settings['list_form_heading'] ?? 'Send your villa details' }}">
            </div>
            <div class="col-12">
                <label class="form-label small fw-semibold">Meta Title</label>
                <input type="text" name="list_meta_title" class="form-control" value="{{ $settings['list_meta_title'] ?? 'List Your Villa in Goa | Fantastic Stays' }}">
            </div>
            <div class="col-12">
                <label class="form-label small fw-semibold">Meta Description</label>
                <textarea name="list_meta_description" rows="2" class="form-control">{{ $settings['list_meta_description'] ?? 'Own a private villa in Goa? Share a few details and Fantastic Stays will tell you whether it fits the collection.' }}</textarea>
            </div>
            <div class="col-12">
                <label class="form-label small fw-semibold">Meta Keywords</label>
                <input type="text" name="list_meta_keywords" class="form-control" value="{{ $settings['list_meta_keywords'] ?? 'list your villa goa, villa management goa, rent out villa goa, holiday home owners goa' }}">
            </div>
        </div>

        <button type="submit" class="btn btn-gold mt-4">Save Services Pages</button>
    </form>
</div>
