<div {{
    $attributes->merge(['class' => 'shadow rounded border border-gray-200 overflow-hidden ' . $background . ' ' . $text . ' ' . $border])
 }}>
    {{ $slot }}
</div>
