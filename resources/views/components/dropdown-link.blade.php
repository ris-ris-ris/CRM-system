@php
    $slotContent = (string) $slot;
    $isLogout = str_contains($slotContent, 'Выйти');
    $classes = 'block w-full px-4 py-2 text-start text-sm leading-5 transition duration-150 ease-in-out';
    if ($isLogout) {
        $classes .= ' text-red-600 hover:text-red-800 font-semibold hover:bg-red-50 dark:hover:bg-red-900/20 focus:outline-none focus:bg-red-50 dark:focus:bg-red-900/20';
    } else {
        $classes .= ' text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-800';
    }
@endphp
<a {{ $attributes->merge(['class' => $classes]) }}>{{ $slot }}</a>
