<li
    @php
        $active = $item->isActive();
        $css_class = $item->getCssClass();
    @endphp
    @if($active || $css_class)
        @class([
            'navigation__active' => $active,
            $css_class => ! empty($css_class),
        ])
    @endif
>
    <a href="{{ $item->getLink() }}"
       @if($item->hasTarget())
           target="{{ $item->getTarget() }}"
       @endif
       class="d-flex justify-content-between align-items-center">
        <span>{{ $item->getLabel() }}</span>
        @if($count = $item->getVisibleCount($user))
        <span class="badge badge-pill bg-primary align-self-start">{{ $count }}</span>
        @endif
        @if($bage = $item->getBadge())
            @php $badge_class = $item->getBadgeClass() @endphp
            <span class="badge badge-pill align-self-start {{ $badge_class ?: 'bg-primary' }}">{{ $bage }}</span>
        @endif
    </a>
</li>
