@php
    $savedRules = isset($villa) && is_array($villa->house_rules) ? $villa->house_rules : [];
    $ruleFields = [
        'rule_cancellation' => ['Cancellation policy', 'cancellation', 'Strict: 50% refund up to 7 days before check-in'],
        'rule_deposit' => ['Security deposit', 'deposit', '₹10,000 security deposit'],
        'rule_pets' => ['Pet policy', 'pets', 'Pets are not allowed'],
        'rule_parties' => ['Parties & events', 'parties', 'Events only with prior approval'],
        'rule_smoking' => ['Smoking', 'smoking', 'Smoking is allowed only outdoors'],
        'rule_quiet_hours' => ['Quiet hours', 'quiet_hours', '10:00 PM to 8:00 AM'],
        'rule_child_policy' => ['Child policy', 'child_policy', 'Children are welcome'],
        'rule_identification' => ['Identification', 'identification', 'A government ID is required at check-in'],
    ];
@endphp

<p class="text-muted small mb-3">Check-in, check-out and guest count already show on the villa page. Fill only the rules you want to display.</p>
<div class="row g-2">
    @foreach($ruleFields as $name => [$label, $key, $placeholder])
        <div class="col-md-6">
            <label class="form-label fw-semibold small">{{ $label }}</label>
            <input type="text" name="{{ $name }}" class="form-control" value="{{ old($name, $savedRules[$key] ?? '') }}" placeholder="{{ $placeholder }}">
        </div>
    @endforeach
</div>
