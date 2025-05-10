@props(['label', 'name'])
<div class="flex items-center gap-2">
    <input type="checkbox" id="{{ $name }}" wire:model="{{ $name }}"
        class="w-4 h-4 rounded-sm bg-gray-900 border border-gray-800" />
    <label for="{{ $name }}" class="text-sm text-gray-400">
        {{ $label }}
    </label>
</div>
