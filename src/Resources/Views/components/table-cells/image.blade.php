@if($item->{$column['name']})
    <img src="{{ Storage::url($item->{$column['name']}) }}"
         class="rounded-3"
         width="48"
         height="48"
         style="object-fit: cover;">
@else
    <div class="bg-light rounded-3 d-flex align-items-center justify-content-center"
         style="width: 48px; height: 48px;">
        <i class="bi bi-image text-muted"></i>
    </div>
@endif
