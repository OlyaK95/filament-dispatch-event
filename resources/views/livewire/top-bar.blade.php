@php
    $user = auth()->user();
@endphp

<div
    class="pointer-events-none absolute right-0 mr-8 flex h-9 w-9 cursor-pointer items-center justify-center rounded-full bg-gradient-to-r from-sky-400 to-blue-500">
    <span
        class="leading-0 text-white">{{ Str::substr($user->name, 0, 1) }}{{ Str::substr($user->name, 0, 1) }}</span>
</div>
