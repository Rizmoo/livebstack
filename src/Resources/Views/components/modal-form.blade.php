<div>

    <!-- Modal -->
    <div wire:ignore.self class="modal fade" id="formModal" tabindex="-1" data-bs-backdrop="static" aria-labelledby="formModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="formModalLabel">
                        {{ $model ? 'Edit ' . class_basename($this->getModelClass()) : 'Create New ' . class_basename($this->getModelClass()) }}
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <form wire:submit.prevent="save">
                    <div class="modal-body">
                        <!-- Loading State -->
{{--                        <div wire:loading wire:target="save, openModal"--}}
{{--                             class="position-absolute w-100 h-100 top-0 start-0 bg-white bg-opacity-75 d-flex justify-content-center align-items-center">--}}
{{--                            <div class="spinner-border text-primary" role="status">--}}
{{--                                <span class="visually-hidden">Loading...</span>--}}
{{--                            </div>--}}
{{--                        </div>--}}

                        <div class="row g-3">
                            @foreach($fields as $field)
                                <div class="{{ $field['columnClass'] ?? 'col-12' }}">
                                    @include('livewire.components.form-fields.' . $field['type'], ['field' => $field])
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="bi bi-x-circle me-1"></i>
                            Cancel
                        </button>
                        <button type="submit" class="btn btn-primary" wire:loading.attr="disabled">
                            <i class="bi bi-save me-1"></i>
                            Save Changes
                            <span wire:loading wire:target="save" class="spinner-border spinner-border-sm ms-1" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Success/Error Alert -->
    <div class="position-fixed top-0 end-0 p-3" style="z-index: 1060">
        @if (session()->has('message'))
            <div class="toast show align-items-center text-white bg-{{ session('type', 'success') }} border-0" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body">
                        {{ session('message') }}
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            </div>
        @endif
    </div>
</div>
