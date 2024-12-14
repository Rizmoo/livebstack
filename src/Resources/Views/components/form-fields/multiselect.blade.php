<div class="form-group">
    <label for="{{ $field['name'] }}" class="form-label">
        {{ $field['label'] }}
        @if(in_array('required', explode('|', $field['rules'] ?? '')))
            <span class="text-danger">*</span>
        @endif
    </label>
    <select
        class="form-select @error('formData.' . $field['name']) is-invalid @enderror"
        id="{{ $field['name'] }}"
        wire:model.defer="formData.{{ $field['name'] }}"
        multiple>
        @foreach($this->getRelationshipOptions($field['options']['relationship']) as $option)
            <option value="{{ $option['id'] }}">{{ $option['label'] }}</option>
        @endforeach
    </select>
    @error('formData.' . $field['name'])
    <div class="invalid-feedback">{{ $message }}</div>
    @enderror
    <div class="form-text">Hold Ctrl/Cmd to select multiple options</div>
</div>
