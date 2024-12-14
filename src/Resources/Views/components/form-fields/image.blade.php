<div class="form-group">
    <label for="{{ $field['name'] }}" class="form-label">
        {{ $field['label'] }}
        @if(in_array('required', explode('|', $field['rules'] ?? '')))
            <span class="text-danger">*</span>
        @endif
    </label>
    <div class="input-group">
        <input type="file"
               class="form-control @error('tempImages.' . $field['name']) is-invalid @enderror"
               id="{{ $field['name'] }}"
               wire:model="tempImages.{{ $field['name'] }}"
               accept="{{ implode(',', $field['acceptedFileTypes'] ?? ['image/*']) }}">
        @if(isset($tempImages[$field['name']]))
            <button class="btn btn-outline-danger" type="button" wire:click="removeImage('{{ $field['name'] }}')">
                <i class="bi bi-trash"></i>
                Remove
            </button>
        @endif
    </div>
    @error('tempImages.' . $field['name'])
    <div class="invalid-feedback d-block">{{ $message }}</div>
    @enderror

    @if($field['preview'] ?? false)
        <div class="mt-2">
            @if(isset($tempImages[$field['name']]))
                <img src="{{ $tempImages[$field['name']]->temporaryUrl() }}"
                     class="img-thumbnail"
                     style="max-height: 200px">
            @elseif(isset($formData[$field['name']]))
                <img src="{{ Storage::url($formData[$field['name']]) }}"
                     class="img-thumbnail"
                     style="max-height: 200px">
            @endif
        </div>
    @endif
</div>
