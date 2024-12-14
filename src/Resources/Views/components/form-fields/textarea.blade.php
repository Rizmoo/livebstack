<div>
    <label for="{{ $field['name'] }}" class="form-label">
        {{ $field['label'] }}
        @if(is_string($field['rules'] ?? '') && str_contains($field['rules'], 'required'))
            <span class="text-danger">*</span>
        @endif
    </label>
    <textarea
        class="form-control @error('formData.' . $field['name']) is-invalid @enderror"
        id="{{ $field['name'] }}"
        wire:model.defer="formData.{{ $field['name'] }}"
        rows="{{ $field['rows'] ?? 3 }}"
        placeholder="{{ $field['placeholder'] ?? '' }}"></textarea>

    @error('formData.' . $field['name'])
    <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>
