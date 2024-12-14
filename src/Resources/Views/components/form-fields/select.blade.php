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
        wire:model.defer="formData.{{ $field['name'] }}">

        <option value="">{{ $field['placeholder'] ?? 'Select an option' }}</option>

        @if(isset($field['relationship']))
            @foreach($this->getRelationshipOptions($field['relationship']['relation']) as $option)
                <option value="{{ $option['id'] }}">{{ $option['label'] }}</option>
            @endforeach
        @elseif(isset($field['options']))
            @foreach($field['options'] as $option)
                <option value="{{ $option['value'] }}">{{ $option['label'] }}</option>
            @endforeach
        @endif
    </select>

    @error('formData.' . $field['name'])
    <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>
