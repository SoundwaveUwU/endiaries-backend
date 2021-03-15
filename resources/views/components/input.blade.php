<div class="w-full my-2">
    <label class="w-full">
        <span>{{ $slot }}</span>
        <input {{ $attributes->merge(['class' => 'px-3 py-2 text-lg border shadow-sm border-gray-200 w-full rounded bg-gray-100 focus:outline-none']) }}>
        @error($attributes['name'])
            <div class="text-red-600 mt-1 text-sm">{{ $message }}</div>
        @enderror
    </label>
</div>
