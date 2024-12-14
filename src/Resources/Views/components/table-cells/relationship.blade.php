@php
    $relation = $column['relationship']['name'];
    $relationColumn = $column['relationship']['column'];
    $relatedModel = $item->{$relation};
@endphp

@if($relatedModel)
    @if(isset($column['format']))
        @php
            $formatted = $column['format']($relatedModel->{$relationColumn}, $relatedModel);
        @endphp
        <div>
            <div class="mb-1">{{ $formatted['title'] }}</div>
            @if(isset($formatted['subtitle']))
                <div class="text-muted small">{{ $formatted['subtitle'] }}</div>
            @endif
        </div>
    @else
        @if(isset($column['states']))
            <span class="{{ $column['states']['class'] ?? 'badge bg-light text-dark' }}">
                {{ $relatedModel->{$relationColumn} }}
            </span>
        @else
            {{ $relatedModel->{$relationColumn} }}
        @endif
    @endif
@endif
