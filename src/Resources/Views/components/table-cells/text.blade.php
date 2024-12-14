@if(isset($column['format']))
    @php
        $formatted = $column['format']($item->{$column['name']}, $item);
    @endphp
    <div>
        <div class="mb-1">{{ $formatted['title'] }}</div>
        @if(isset($formatted['subtitle']))
            <div class="text-muted small">{{ $formatted['subtitle'] }}</div>
        @endif
    </div>
@else
    {{ $item->{$column['name']} }}
@endif
