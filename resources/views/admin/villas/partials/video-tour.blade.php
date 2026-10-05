@php
    $savedVideo = (isset($villa) && is_array($villa->videos) && isset($villa->videos[0]) && is_array($villa->videos[0]))
        ? $villa->videos[0]
        : [];
    $videoSource = old('video_source', $savedVideo['source'] ?? '');
    $videoUrl = old('video_url', $savedVideo['embed'] ?? '');
    $videoThumb = old('video_thumb', $savedVideo['thumb'] ?? '');
@endphp

<div class="card card-custom p-4 mb-4">
    <h5 class="fw-bold mb-1" style="color: var(--fs-emerald);">Video Tour</h5>
    <p class="text-muted small mb-3">Choose YouTube or Instagram. Leave this as None to hide the Video Tour button.</p>

    <div class="mb-3">
        <label class="form-label fw-semibold small" for="video_source">Video source</label>
        <select name="video_source" id="video_source" class="form-select" onchange="toggleVideoSource()">
            <option value="" {{ $videoSource === '' ? 'selected' : '' }}>None</option>
            <option value="youtube" {{ $videoSource === 'youtube' ? 'selected' : '' }}>YouTube</option>
            <option value="instagram" {{ $videoSource === 'instagram' ? 'selected' : '' }}>Instagram</option>
        </select>
    </div>

    <div id="video-youtube" class="{{ $videoSource === 'youtube' ? '' : 'd-none' }}">
        <label class="form-label fw-semibold small" for="video_url_youtube">YouTube URL</label>
        <input type="url" name="video_url" id="video_url_youtube" class="form-control font-monospace small"
               placeholder="https://www.youtube.com/watch?v=..."
               value="{{ $videoSource === 'youtube' ? $videoUrl : '' }}"
               {{ $videoSource === 'youtube' ? '' : 'disabled' }}>
        <small class="text-muted d-block mt-1">Paste a YouTube link. Video Tour opens it in the popup, the same way it does now.</small>
    </div>

    <div id="video-instagram" class="{{ $videoSource === 'instagram' ? '' : 'd-none' }}">
        <div class="mb-3">
            <label class="form-label fw-semibold small">Cover image</label>
            <div class="d-flex gap-2 align-items-center mb-2">
                <input type="text" name="video_thumb" id="video_thumb_input"
                       class="form-control font-monospace small"
                       value="{{ $videoThumb }}"
                       placeholder="/storage/villas/cover.webp"
                       oninput="previewVideoThumb(this.value)"
                       {{ $videoSource === 'instagram' ? '' : 'disabled' }}>
                <label class="btn btn-outline-secondary btn-sm mb-0 text-nowrap" style="cursor:pointer;">
                    <i class="bi bi-folder2-open me-1"></i> Browse
                    <input type="file" accept="image/*" class="d-none" onchange="uploadVideoThumb(this)">
                </label>
            </div>
            <div id="video_thumb_progress" class="d-none mb-2">
                <div class="progress" style="height:4px;">
                    <div class="progress-bar progress-bar-striped progress-bar-animated bg-success w-100"></div>
                </div>
                <small class="text-muted">Uploading&hellip;</small>
            </div>
            <img id="video_thumb_preview" src="{{ $videoThumb }}" alt="Instagram cover"
                 class="rounded-3 shadow-sm border object-fit-cover {{ $videoThumb ? '' : 'd-none' }}"
                 style="max-height: 160px; width: auto; max-width: 100%;">
            <small class="text-muted d-block mt-1">This image opens first when a guest clicks Video Tour.</small>
        </div>
        <label class="form-label fw-semibold small" for="video_url_instagram">Instagram URL</label>
        <input type="url" name="video_url" id="video_url_instagram" class="form-control font-monospace small"
               placeholder="https://www.instagram.com/reel/..."
               value="{{ $videoSource === 'instagram' ? $videoUrl : '' }}"
               {{ $videoSource === 'instagram' ? '' : 'disabled' }}>
        <small class="text-muted d-block mt-1">Clicking the cover image opens this Instagram page or video.</small>
    </div>
</div>

<script>
function toggleVideoSource() {
    const source = document.getElementById('video_source').value;
    const youtube = document.getElementById('video-youtube');
    const instagram = document.getElementById('video-instagram');
    const youtubeUrl = document.getElementById('video_url_youtube');
    const instagramUrl = document.getElementById('video_url_instagram');
    const thumb = document.getElementById('video_thumb_input');

    youtube.classList.toggle('d-none', source !== 'youtube');
    instagram.classList.toggle('d-none', source !== 'instagram');
    youtubeUrl.disabled = source !== 'youtube';
    instagramUrl.disabled = source !== 'instagram';
    thumb.disabled = source !== 'instagram';
}

function previewVideoThumb(url) {
    const preview = document.getElementById('video_thumb_preview');
    if (!preview) return;
    if (!url) {
        preview.classList.add('d-none');
        preview.removeAttribute('src');
        return;
    }
    preview.src = url;
    preview.classList.remove('d-none');
}

function uploadVideoThumb(fileInput) {
    const file = fileInput.files[0];
    if (!file) return;
    const progress = document.getElementById('video_thumb_progress');
    progress.classList.remove('d-none');
    const formData = new FormData();
    formData.append('image', file);
    formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);
    fetch('{{ route("admin.villas.upload-image") }}', { method: 'POST', body: formData })
        .then(r => r.json())
        .then(data => {
            if (data.url) {
                document.getElementById('video_thumb_input').value = data.url;
                previewVideoThumb(data.url);
            } else {
                alert('Upload failed: ' + (data.message || 'Unknown error'));
            }
        })
        .catch(e => alert('Upload error: ' + e))
        .finally(() => progress.classList.add('d-none'));
}
</script>
