@props(['active', 'href'])

@php
$baseClasses = 'flex items-center gap-3 px-4 py-2 rounded-lg font-medium transition';
$activeClasses = 'text-white bg-slate-800';
$inactiveClasses = 'hover:bg-slate-800';
@endphp

<a href="{{ $href }}" {{ $attributes->merge(['class' => $baseClasses . ' ' . ($active ?? false ? $activeClasses : $inactiveClasses)]) }} @if(!($active ?? false)) style="background: linear-gradient(135deg, rgba(255, 255, 255, 0.95) 0%, rgba(255, 255, 255, 0.85) 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;" @endif>
    {!! preg_replace('/stroke="currentColor"/', 'stroke="url(#orangeGradientSidebar)"', $slot) !!}
</a>

