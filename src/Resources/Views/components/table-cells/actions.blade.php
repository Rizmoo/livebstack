<div class="d-flex justify-content-end gap-2">
    {{-- Primary Actions (shown directly) --}}
    @foreach($column['actions'] ?? [] as $action)
        @if(!($action['dropdown'] ?? false))
            <button type="button"
                    class="{{ $action['class'] ?? 'btn btn-sm btn-light' }}"
                    wire:click="{{ $action['name'] === 'edit' ? 'openModal('.$item->id.')' : $action['name'].'('.$item->id.')' }}"
                    @if(isset($action['confirm']))
                        onclick="confirm('{{ $action['confirm'] }}') || event.stopImmediatePropagation()"
                @endif>
                @if(isset($action['icon']))
                    <i class="bi bi-{{ $action['icon'] }}"></i>
                @endif
                @if(isset($action['label']))
                    <span class="ms-1">{{ $action['label'] }}</span>
                @endif
            </button>
        @endif
    @endforeach

    {{-- Dropdown Actions --}}
    @if(collect($column['actions'] ?? [])->contains('dropdown', true))
        <div class="dropdown">
            <button class="btn btn-sm btn-light dropdown-toggle" type="button" data-bs-toggle="dropdown">
                More
            </button>
            <ul class="dropdown-menu dropdown-menu-end">
                @foreach($column['actions'] ?? [] as $action)
                    @if($action['dropdown'] ?? false)
                        <li>
                            <button class="dropdown-item d-flex align-items-center" type="button"
                                    wire:click="{{ $action['name'] === 'edit' ? 'openModal('.$item->id.')' : $action['name'].'('.$item->id.')' }}"
                                    @if(isset($action['confirm']))
                                        onclick="confirm('{{ $action['confirm'] }}') || event.stopImmediatePropagation()"
                                @endif>
                                @if(isset($action['icon']))
                                    <i class="bi bi-{{ $action['icon'] }} me-2"></i>
                                @endif
                                {{ $action['label'] }}
                            </button>
                        </li>
                    @endif
                @endforeach
            </ul>
        </div>
    @endif
</div>
