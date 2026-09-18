@props(['href', 'icon', 'label', 'tone' => 'edit'])

<a href="{{ $href }}" class="icon-btn icon-btn-{{ $tone }}" title="{{ $label }}" aria-label="{{ $label }}">
    <x-icon :name="$icon" />
</a>
