<li class="nav-item{{ $item->hasCssClass() ? ' ' . $item->getCssClass() : '' }}">
    <a href="{{ $item->getLink() }}"
       @class([
            'nav-link d-flex justify-content-between align-items-center',
            'active' => $item->isActive() || $item->hasActiveChild($user)
        ])
    >
        <span>@if($item->hasIcon())<i class="{{ $item->getIcon($icon_prefix) }}"></i> @endif{{ $item->getLabel() }}</span>
        @if($count = $item->getAggregatedCount($user))
        <span class="ms-2 badge text-bg-primary rounded-pill">{{ $count }}</span>
        @endif
        @if($bage = $item->getBadge())
            @php $badge_class = $item->getBadgeClass() @endphp
            <span class="ms-2 badge {{ $badge_class ?: 'text-bg-primary' }} rounded-pill">{{ $bage }}</span>
        @endif
    </a>
    @if($children = $item->getVisibleChildren($user))
    <ul>
        @foreach($children as $child)
            @include('menu-builder::bootstrap-5.child-menu-item', ['item' => $child, 'parent' => $item])
        @endforeach
    </ul>
    @endif
</li>
