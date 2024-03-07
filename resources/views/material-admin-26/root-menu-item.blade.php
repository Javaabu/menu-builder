<li
    @php
        $active = $item->isActive() || $item->hasActiveChild($user);
    @endphp
    @if($active || $item->hasVisibleChild($user))
        @class([
            'navigation__active' => $active,
            'navigation__sub' => $item->hasVisibleChild($user)
        ])
    @endif
    >
    <a href="{{ $item->getLink() }}">
        <span>@if($item->hasIcon())<i class="{{ $item->getIcon($icon_prefix) }}"></i> @endif{{ $item->getLabel() }}</span>
        @if($count = $item->getAggregatedCount($user))
        <span class="ml-2 badge badge-pill">{{ $count }}</span>
        @endif
    </a>
    @if($children = $item->getVisibleChildren($user))
    <ul>
        @foreach($children as $child)
            @include('menu-builder::material-admin-26.child-menu-item', ['item' => $child, 'parent' => $item])
        @endforeach
    </ul>
    @endif
</li>
