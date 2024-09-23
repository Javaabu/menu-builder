<li class="nav-item">
    <a href="{{ $item->getLink() }}"
       @class([
            'nav-link d-flex justify-content-between align-items-center',
            'active' => $item->isActive()
        ])
    >
        <span>@if($item->hasIcon())<i class="{{ $item->getIcon($icon_prefix) }}"></i> @endif{{ $item->getLabel() }}</span>
        @if($count = $item->getVisibleCount($user))
        <span class="ms-2 badge text-bg-primary rounded-pill">{{ $count }}</span>
        @endif
        @if($bage = $item->getBadge())
            @php $badge_class = $item->getBadgeClass() @endphp
            <span class="ms-2 badge {{ $badge_class ?: 'text-bg-primary' }} rounded-pill">{{ $bage }}</span>
        @endif
    </a>
</li>
