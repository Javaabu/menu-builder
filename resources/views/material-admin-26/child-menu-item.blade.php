<li @if($item->isActive())class="navigation__active"@endif>
    <a href="{{ $item->getLink() }}" class="d-flex justify-content-between align-items-center">
        <span>{{ $item->getLabel() }}</span>
        @if($count = $item->getVisibleCount($user))
        <span class="badge badge-pill bg-primary align-self-start">{{ $count }}</span>
        @endif
    </a>
</li>
