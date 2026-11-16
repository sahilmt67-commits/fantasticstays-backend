@php
    $brochureUrl = old('brochure', isset($villa) ? ($villa->brochure ?? '') : '');
@endphp

<div class="card card-custom p-4 mb-4">
    <h5 class="fw-bold mb-3" style="color: var(--fs-emerald);">Villa Brochure</h5>
    <p class="text-muted small mb-3">Upload a PDF brochure. Guests can download it from the villa page. PDF only, up to 30 MB. Upload the file, then save the villa.</p>

    <input type="hidden" name="brochure" id="brochure_input" value="{{ $brochureUrl }}">

    <div class="d-flex flex-wrap gap-2 align-items-center mb-2">
        <label class="btn btn-outline-secondary btn-sm mb-0" style="cursor:pointer;">
            <i class="bi bi-file-earmark-pdf me-1"></i> Upload PDF
            <input type="file" id="brochure_file_input" accept="application/pdf,.pdf" class="d-none" onchange="uploadBrochure(this)">
        </label>
        <button type="button" class="btn btn-outline-danger btn-sm d-none" id="brochure_remove_btn" onclick="removeBrochure()">
            Remove
        </button>
    </div>

    <div id="brochure_upload_progress" class="d-none mb-2">
        <div class="progress" style="height:4px;">
            <div class="progress-bar progress-bar-striped progress-bar-animated bg-success w-100"></div>
        </div>
        <small class="text-muted">Uploading brochure&hellip;</small>
    </div>

    <div id="brochure_status" class="small text-muted">No brochure uploaded yet.</div>
</div>

<script>
function escapeBrochureHtml(value) {
    return String(value)
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;');
}

function brochureFileLabel(url) {
    if (!url) return 'brochure.pdf';
    const path = String(url).split('?')[0].split('#')[0];
    const name = path.substring(path.lastIndexOf('/') + 1);
    try {
        return decodeURIComponent(name) || 'brochure.pdf';
    } catch (error) {
        return name || 'brochure.pdf';
    }
}

function paintBrochure() {
    const input = document.getElementById('brochure_input');
    const status = document.getElementById('brochure_status');
    const removeBtn = document.getElementById('brochure_remove_btn');
    if (!input || !status || !removeBtn) return;

    const url = input.value.trim();
    if (!url) {
        status.className = 'small text-muted';
        status.textContent = 'No brochure uploaded yet.';
        removeBtn.classList.add('d-none');
        return;
    }

    removeBtn.classList.remove('d-none');
    status.className = 'small';
    status.innerHTML = '<a href="' + escapeBrochureHtml(url) + '" target="_blank" rel="noopener" class="fw-semibold">'
        + '<i class="bi bi-file-earmark-pdf me-1"></i>' + escapeBrochureHtml(brochureFileLabel(url)) + '</a>'
        + ' <span class="text-muted">— save the villa to publish this download.</span>';
}

function uploadBrochure(fileInput) {
    const file = fileInput.files && fileInput.files[0];
    if (!file) return;

    const isPdf = file.type === 'application/pdf' || /\.pdf$/i.test(file.name);
    if (!isPdf) {
        alert('Please choose a PDF file.');
        fileInput.value = '';
        return;
    }
    if (file.size > 30 * 1024 * 1024) {
        alert('This PDF is too large. You can upload brochures up to 30 MB.');
        fileInput.value = '';
        return;
    }

    const progress = document.getElementById('brochure_upload_progress');
    if (progress) progress.classList.remove('d-none');

    const formData = new FormData();
    formData.append('brochure', file);
    const csrf = document.querySelector('meta[name="csrf-token"]');
    formData.append('_token', csrf ? csrf.content : '');

    fetch(@json(route('admin.villas.upload-brochure')), {
        method: 'POST',
        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: formData
    }).then(function (response) {
        return response.json().catch(function () { return null; }).then(function (data) {
            if (!response.ok || !data || !data.url) {
                const message = (data && (data.message || (data.errors && data.errors.brochure && data.errors.brochure[0])))
                    || 'Could not upload the brochure.';
                alert(message);
                return;
            }
            document.getElementById('brochure_input').value = data.url;
            paintBrochure();
        });
    }).catch(function () {
        alert('Could not upload the brochure.');
    }).finally(function () {
        if (progress) progress.classList.add('d-none');
        fileInput.value = '';
    });
}

function removeBrochure() {
    const input = document.getElementById('brochure_input');
    if (input) input.value = '';
    paintBrochure();
}

paintBrochure();
</script>
