<li
    @php
        $hasActiveChild = $item->hasActiveChild($user);
        $active = $item->isActive() || $hasActiveChild;
        $css_class = $item->getCssClass();
    @endphp
    @if($active || $item->hasVisibleChild($user) || $css_class)
        @class([
            'navigation__active' => $active && (! $hasActiveChild),
            'navigation__sub' => $item->hasVisibleChild($user),
            'navigation__sub--toggled' => $hasActiveChild,
            'navigation__sub--active' => $hasActiveChild,
            $css_class => ! empty($css_class),
        ])
    @endif
    >
    <a href="{{ $item->getLink() }}"
        @if($item->hasTarget())
            target="{{ $item->getTarget() }}"
        @endif
    >
        @if($item->hasIcon())<i class="{{ $item->getIcon($icon_prefix) }}"></i> @endif{{ $item->getLabel() }}
        @if($count = $item->getAggregatedCount($user))
            <span class="ml-2 badge badge-pill">{{ $count }}</span>
        @endif
        @if($bage = $item->getBadge())
            @php $badge_class = $item->getBadgeClass() @endphp
            <span class="ml-2 badge badge-pill {{ $badge_class ?: 'bg-primary' }}">{{ $bage }}</span>
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
