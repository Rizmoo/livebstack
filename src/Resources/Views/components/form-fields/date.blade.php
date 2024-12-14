<div class="form-group">
    <label for="{{ $field['name'] }}" class="form-label">
        {{ $field['label'] }}
        @if(in_array('required', explode('|', $field['rules'] ?? '')))
            <span class="text-danger">*</span>
        @endif
    </label>
    <input type="date"
           class="form-control @error('formData.' . $field['name']) is-invalid @enderror"
           id="{{ $field['name'] }}"
           wire:model.defer="formData.{{ $field['name'] }}">
    @error('formData.' . $field['name'])
    <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>
