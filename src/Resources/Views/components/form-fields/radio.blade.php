<div class="form-group">
    <label class="form-label d-block">
        {{ $field['label'] }}
        @if(in_array('required', explode('|', $field['rules'] ?? '')))
            <span class="text-danger">*</span>
        @endif
    </label>
    <div class="btn-group w-100" role="group">
        @foreach($field['options']['values'] as $option)
            <input type="radio"
                   class="btn-check @error('formData.' . $field['name']) is-invalid @enderror"
                   id="{{ $field['name'] }}_{{ $option['value'] }}"
                   wire:model.defer="formData.{{ $field['name'] }}"
                   value="{{ $option['value'] }}"
                   autocomplete="off">
            <label class="btn btn-outline-primary" for="{{ $field['name'] }}_{{ $option['value'] }}">
                {{ $option['label'] }}
            </label>
        @endforeach
    </div>
    @error('formData.' . $field['name'])
    <div class="invalid-feedback d-block">{{ $message }}</div>
    @enderror
</div>
