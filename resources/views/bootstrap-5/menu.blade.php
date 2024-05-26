<ul class="nav flex-column"
    @if($id)
        id="{{ $id }}"
    @endif
>
    @foreach($items as $item)
        @include('menu-builder::bootstrap-5.root-menu-item')
    @endforeach
</ul>
