<ul class="navigation"
    @if($id)
        id="{{ $id }}"
    @endif
>
    @foreach($items as $item)
        @include('menu-builder::material-admin-26.root-menu-item')
    @endforeach
</ul>
