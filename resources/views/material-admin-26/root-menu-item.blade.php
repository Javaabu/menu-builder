<li
    @php
        $active = $item->isActive() || $item->hasActiveChild($user);
    @endphp
    @if($active || $item->hasVisibleChild($user))
        @class([
            'navigation__active' => $active,
            'navigation__sub' => $item->hasVisibleChild($user),
            'navigation__sub--toggled' => $active,
        ])
    @endif
    >
    <a href="{{ $item->getLink() }}">@if($item->hasIcon())<i class="{{ $item->getIcon($icon_prefix) }}"></i> @endif{{ $item->getLabel() }}@if($count = $item->getAggregatedCount($user))<span class="ml-2 badge badge-pill">{{ $count }}</span>@endif</a>
    @if($children = $item->getVisibleChildren($user))
    <ul
        style="display: {{ $active ? 'block' : 'none' }};"
    >
        @foreach($children as $child)
            @include('menu-builder::material-admin-26.child-menu-item', ['item' => $child, 'parent' => $item])
        @endforeach
    </ul>
    @endif
</li>
