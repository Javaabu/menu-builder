

<li
    @class([
        'navigation__active' => $isActive(),
    ])
>
    <a href="{{ $getLink() ?? '#' }}">
        {{ $getLabel() }}
    </a>
</li>
