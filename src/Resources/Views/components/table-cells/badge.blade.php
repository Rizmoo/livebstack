@php
    $value = $item->{$column['name']};
    $class = $column['states'][$value] ?? 'badge bg-secondary';
@endphp
<span class="{{ $class }}">
    {{ ucfirst($value) }}
</span>
