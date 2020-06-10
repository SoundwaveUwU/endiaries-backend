<{{ $tag }}
    @if ($tag == 'a')
        href="{{ $href }}"
    @else
        type="{{ $type }}"
    @endif
    {{ $attributes->merge(['class' => $classes]) }}>
    @if ($icon)
        <div class="flex items-center mr-1">
            <x-icon :icon="$icon" />
        </div>
    @endif
    <div class="w-full flex justify-center items-center">
        {{ $slot }}
    </div>
</{{ $tag }}>
