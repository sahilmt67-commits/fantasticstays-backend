@php
    $savedRules = isset($villa) && is_array($villa->house_rules) ? $villa->house_rules : [];
    if (session()->hasOldInput()) {
        $cancellationPolicy = old('cancellation_policy', '');
        $houseRulesContent = old('house_rules_content', '');
    } else {
        $cancellationPolicy = $savedRules['cancellation'] ?? '';
        $houseRulesContent = $savedRules['content'] ?? '';
        if (trim(strip_tags(str_replace('&nbsp;', ' ', (string) $houseRulesContent))) === '') {
            $labels = [
                'deposit' => 'Security deposit',
                'pets' => 'Pet policy',
                'parties' => 'Parties & events',
                'smoking' => 'Smoking',
                'quiet_hours' => 'Quiet hours',
                'child_policy' => 'Child policy',
                'identification' => 'Identification',
            ];
            $parts = [];
            foreach ($labels as $key => $label) {
                $value = trim((string) ($savedRules[$key] ?? ''));
                if ($value === '') {
                    continue;
                }
                $parts[] = '<p><strong>'.$label.'</strong><br>'.$value.'</p>';
            }
            $houseRulesContent = implode('', $parts);
        }
    }
@endphp

<div class="card card-custom p-4 mb-4">
    <h5 class="fw-bold mb-3" style="color: var(--fs-emerald);">House rules</h5>
    <label class="form-label fw-semibold small d-flex align-items-center gap-2">
        Rules
        <span class="badge text-bg-primary" style="font-size:0.65rem;">Rich Text Editor</span>
    </label>
    <textarea name="house_rules_content" id="ck_house_rules" class="form-control">{{ $houseRulesContent }}</textarea>
</div>

<div class="card card-custom p-4 mb-4">
    <h5 class="fw-bold mb-3" style="color: var(--fs-emerald);">Cancellation policy</h5>
    <label class="form-label fw-semibold small d-flex align-items-center gap-2">
        Policy
        <span class="badge text-bg-primary" style="font-size:0.65rem;">Rich Text Editor</span>
    </label>
    <textarea name="cancellation_policy" id="ck_cancellation" class="form-control">{{ $cancellationPolicy }}</textarea>
</div>
