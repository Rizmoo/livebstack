<div>
    <div class="form-check">
        <input type="checkbox"
               class="form-check-input @error('formData.' . $field['name']) is-invalid @enderror"
               id="{{ $field['name'] }}"
               wire:model.defer="formData.{{ $field['name'] }}">

        <label class="form-check-label ms-1" for="{{ $field['name'] }}">
            {{ $field['label'] }}
            @if(is_string($field['rules'] ?? '') && str_contains($field['rules'], 'required'))
                <span class="text-danger">*</span>
            @endif
        </label>

        @error('formData.' . $field['name'])
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    @if(isset($field['help']))
        <div class="form-text">{{ $field['help'] }}</div>
    @endif
</div>
