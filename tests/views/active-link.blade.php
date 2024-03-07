<a href="{{ $active_item->getLink() }}" @class(['active' => $active_item->isActive()])>
    {{ $active_item->getLabel() }}
</a>

<a href="{{ $inactive_item->getLink() }}" @class(['active' => $inactive_item->isActive()])>
    {{ $inactive_item->getLabel() }}
</a>
