<div>
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0">{{ $title }}</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                    <li class="breadcrumb-item active">{{ $title }}</li>
                </ol>
            </nav>
        </div>
        <div>
            <button type="button" class="btn btn-primary" wire:click="openModal">
                <i class="bi bi-plus-circle me-1"></i>
                {{ $createButtonText }}
            </button>
        </div>
    </div>

    <!-- Custom Content - Before Stats -->
    @foreach($customSections['before-stats'] ?? [] as $section)
        @livewire($section['component'], $section['params'], key('before-stats-' . $loop->index))
    @endforeach

    <!-- Stats Cards (Optional) -->
    @if(count($stats_cards) > 0)
        <div class="row g-4 mb-4">
            @foreach($stats_cards as $card)
                <div class="col-12 col-sm-6 col-lg-{{ 12 / count($stats_cards) }}">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0 rounded-3 p-3 bg-{{ $card['color'] }} bg-opacity-10">
                                    <i class="bi bi-{{ $card['icon'] }} text-{{ $card['color'] }} fs-4"></i>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <div class="text-muted small">{{ $card['title'] }}</div>
                                    <div class="h3 mb-0">{{ $card['value'] }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    <!-- Custom Content - After Stats -->
    @foreach($customSections['after-stats'] ?? [] as $section)
        @livewire($section['component'], $section['params'], key('after-stats-' . $loop->index))
    @endforeach

    <!-- Custom Content - Before Table -->
    @foreach($customSections['before-table'] ?? [] as $section)
        @livewire($section['component'], $section['params'], key('before-table-' . $loop->index))
    @endforeach

    <!-- Table Component -->
    @include('livewire.components.table', [
        'table' => $this->table(),
        'items' => $items
    ])

    <!-- Custom Content - After Table -->
    @foreach($customSections['after-table'] ?? [] as $section)
        @livewire($section['component'], $section['params'], key('after-table-' . $loop->index))
    @endforeach

    <!-- Modal Form -->
    @include('livewire.components.modal-form')
</div>
