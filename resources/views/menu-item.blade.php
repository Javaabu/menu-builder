<li
    @class([
        'navigation__active' => $isActive(),
        'navigation__sub' => $hasChildren(),
        'navigation__active' => $isActive(),
        'navigation__sub--active navigation__sub--toggled' => $hasActiveChild(),
    ])
>
    <a href="{{ $getLink() ?? '#' }}">
        @if ($hasIcon())
            <i style="font-size: 1.2rem;" class="{{ $getIcon() }}"></i>
        @endif

        {{ $getLabel() }}

        @if ($hasCount())
            <span class="ml-2 badge badge-pill">{{ $getCount() }}</span>
        @endif
    </a>
    @if ($hasChildren())
        {!! $renderChildren() !!}
    @endif
</li>
