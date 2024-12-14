<div class="form-group">
    <label for="{{ $field['name'] }}" class="form-label">
        {{ $field['label'] }}
        @if(in_array('required', explode('|', $field['rules'] ?? '')))
            <span class="text-danger">*</span>
        @endif
    </label>
    <div class="input-group">
        @if(isset($field['prefix']))
            <span class="input-group-text">{{ $field['prefix'] }}</span>
        @endif
        <input type="number"
               class="form-control @error('formData.' . $field['name']) is-invalid @enderror"
               id="{{ $field['name'] }}"
               wire:model.defer="formData.{{ $field['name'] }}"
               step="{{ $field['step'] ?? '1' }}"
               placeholder="{{ $field['placeholder'] ?? '' }}">
        @if(isset($field['suffix']))
            <span class="input-group-text">{{ $field['suffix'] }}</span>
        @endif
        @error('formData.' . $field['name'])
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>
