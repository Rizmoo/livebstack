{{-- resources/views/livewire/components/table.blade.php --}}
@php
    $tableSchema = $table->getSchema();
@endphp

<div class="card border-0 shadow-sm">
    {{-- Bulk Actions & Export Buttons --}}
    @if($tableSchema['bulkActionsEnabled'] || !empty($tableSchema['exports']))
        <div class="card-header  py-3">
            <div class="row align-items-center">
                <div class="col">
                    @if($tableSchema['bulkActionsEnabled'])
                        <div class="btn-group me-2">
                            <button type="button" class="btn btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false" @if(empty($selected)) disabled @endif>
                                <i class="bi bi-gear me-1"></i>
                                Bulk Actions
                            </button>
                            <ul class="dropdown-menu">
                                @foreach($tableSchema['bulkActions'] as $action)
                                    <li>
                                        <button type="button"
                                                class="dropdown-item d-flex align-items-center"
                                                wire:click="executeBulkAction('{{ $action['name'] }}')">
                                            @if($action['icon'])
                                                <i class="bi bi-{{ $action['icon'] }} me-2"></i>
                                            @endif
                                            {{ $action['label'] }}
                                        </button>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @if(!empty($tableSchema['exports']))
                        <div class="btn-group">
                            <button type="button" class="btn btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi bi-download me-1"></i>
                                Export
                            </button>
                            <ul class="dropdown-menu">
                                @foreach($tableSchema['exports'] as $export)
                                    <li>
                                        <button type="button"
                                                class="dropdown-item d-flex align-items-center"
                                                wire:click="export('{{ $export['format'] }}')">
                                            @if($export['icon'])
                                                <i class="bi bi-{{ $export['icon'] }} me-2"></i>
                                            @endif
                                            {{ $export['label'] }}
                                        </button>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>
                @if($tableSchema['bulkActionsEnabled'] && count($selected) > 0)
                    <div class="col-auto">
                        <div class="d-flex align-items-center">
                            <span class="text-muted me-3">{{ count($selected) }} selected</span>
                            <button type="button" class="btn btn-link text-danger p-0" wire:click="$set('selected', [])">
                                Clear
                            </button>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    @endif

    {{-- Filters Section --}}
    @if($tableSchema['searchable'] || !empty($tableSchema['filters']))
        <div class="card-body border-bottom">
            <div class="row g-3 align-items-center">
                @if($tableSchema['searchable'])
                    <div class="col-12 col-md-4">
                        <div class="input-group">
                            <span class="input-group-text  border-end-0">
                                <i class="bi bi-search"></i>
                            </span>
                            <input type="search"
                                   class="form-control border-start-0"
                                   placeholder="Search..."
                                   wire:model.live.debounce.300ms="search">
                        </div>
                    </div>
                @endif

                @foreach($tableSchema['filters'] as $name => $filter)
                    <div class="col-12 col-md-3">
                        <select class="form-select" wire:model.live="{{ $name }}">
                            <option value="">{{ $filter['label'] }}</option>
                            @foreach(is_callable($filter['options']) ? $filter['options']() : $filter['options'] as $value => $label)
                                <option value="{{ $value }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                @endforeach

                @if($search || count(array_filter($filters ?? [])))
                    <div class="col-12 col-md-2">
                        <button type="button" class="btn btn-outline-secondary w-100" wire:click="resetFilters">
                            <i class="bi bi-x-circle me-1"></i>
                            Clear
                        </button>
                    </div>
                @endif
            </div>
        </div>
    @endif

    {{-- Table --}}
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="bg-light">
            <tr>
                @if($tableSchema['bulkActionsEnabled'])
                    <th class="border-0" style="width: 40px;">
                        <div class="form-check">
                            <input type="checkbox"
                                   class="form-check-input"
                                   wire:model.live="selectPage">
                        </div>
                    </th>
                @endif

                @foreach($tableSchema['columns'] as $column)
                    <th @if($column['width'] ?? false) style="width: {{ $column['width'] }}" @endif
                    class="border-0 {{ isset($column['alignment']) ? 'text-'.$column['alignment'] : '' }}">
                        @if($column['sortable'] ?? false)
                            <a href="#" wire:click.prevent="sort('{{ $column['name'] }}')" class="text-decoration-none text-inherit">
                                {{ $column['label'] }}
                                @if($this->sortField === $column['name'])
                                    <i class="bi bi-arrow-{{ $this->sortDirection === 'asc' ? 'up' : 'down' }} ms-1"></i>
                                @endif
                            </a>
                        @else
                            {{ $column['label'] }}
                        @endif
                    </th>
                @endforeach
            </tr>
            </thead>
            <tbody>
            @forelse($items as $item)
                <tr wire:key="row-{{ $item->id }}">
                    @if($tableSchema['bulkActionsEnabled'])
                        <td>
                            <div class="form-check">
                                <input type="checkbox"
                                       class="form-check-input"
                                       value="{{ $item->id }}"
                                       wire:model.live="selected">
                            </div>
                        </td>
                    @endif

                    @foreach($tableSchema['columns'] as $column)
                        <td class="{{ isset($column['alignment']) ? 'text-'.$column['alignment'] : '' }}">
                            @include('livewire.components.table-cells.' . $column['type'], [
                                'item' => $item,
                                'column' => $column
                            ])
                        </td>
                    @endforeach
                </tr>
            @empty
                <tr>
                    <td colspan="{{ count($tableSchema['columns']) + ($tableSchema['bulkActionsEnabled'] ? 1 : 0) }}" class="text-center py-5">
                        <div class="text-muted">
                            <i class="bi bi-inbox display-6"></i>
                            <p class="mt-2">No records found</p>
                        </div>
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

    {{-- Footer with Pagination and Per Page Selector --}}
    <div class="card-footer border-0">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
            <div class="d-flex align-items-center">
                <span class="me-2 text-muted">Show:</span>
                <select class="form-select form-select-sm w-auto" wire:model.live="perPage">
                    @foreach($this->getPerPageOptions() as $value)
                        <option value="{{ $value }}">{{ $value }}</option>
                    @endforeach
                </select>
            </div>

            <div class="d-flex align-items-center">
                @if($items->total() > 0)
                    <div class="text-muted me-4">
                        Showing {{ $items->firstItem() }} to {{ $items->lastItem() }}
                        of {{ $items->total() }} results
                    </div>
                @endif

                {{ $items->onEachSide(1)->links() }}
            </div>
        </div>
    </div>
</div>
