<label for="{{ $field['input_id'] }}" class="{{ $hasLegend ? '' : 'bold' }}">
    <span>{{ $field['label'] }}</span>
    <input
        type="{{ $field['input_type'] }}"
        name="{{ $field['input_name'] }}"
        id="{{ $field['input_id'] }}"
        value="{{ $field['value'] }}"
        {{ $field['placeholder'] ? 'placeholder='.$field['placeholder'] : '' }}
        {{ isset($field['onclick']) ? sprintf('onclick=%s', $field['onclick']) : '' }}
        {{ $field['required'] ? 'required' : '' }}
        data-input-id="{{ $field['input_id'] }}"
        data-label="{{ $field['label'] }}"
        data-validation-type="{{ $field['validation_type'] }}"
        data-required="{{ $field['required'] ? 'true' : 'false' }}"
    />
</label>
