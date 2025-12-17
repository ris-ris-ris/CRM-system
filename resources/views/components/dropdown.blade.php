@props(['align' => 'right', 'width' => '48', 'contentClasses' => 'py-1 bg-white dark:bg-gray-700'])

@php
$alignmentClasses = match ($align) {
    'left' => 'ltr:origin-top-left rtl:origin-top-right start-0',
    'top' => 'origin-top',
    default => 'ltr:origin-top-right rtl:origin-top-left end-0',
};

$width = match ($width) {
    '48' => 'w-48',
    default => $width,
};
@endphp

@php
$isLeft = $align === 'left';
@endphp

<div class="relative" x-data="{ 
    open: false,
    position: { top: 0, left: 0, right: 0 },
    align: '{{ $align }}'
}" 
@click.outside="open = false" 
@close.stop="open = false" 
style="z-index: 99999; position: relative;"
x-init="
    $watch('open', value => {
        if(value) {
            if(typeof loadUserStats === 'function') setTimeout(loadUserStats, 100);
            setTimeout(() => {
                const triggerContainer = $el.querySelector('div:first-child');
                const trigger = triggerContainer ? (triggerContainer.querySelector('button') || triggerContainer) : $el.firstElementChild;
                if (trigger) {
                    const rect = trigger.getBoundingClientRect();
                    // Открываем справа от кнопки, выше чем сейчас
                    position.top = Math.max(rect.top - 100, 10); // Поднимаем на 100px или минимум 10px от верха
                    if (align === 'left') {
                        position.left = rect.right + 8;
                        position.right = 'auto';
                    } else {
                        position.left = rect.right + 8;
                        position.right = 'auto';
                    }
                }
            }, 50);
        }
    });
">
    <div @click="open = ! open">
        {{ $trigger }}
    </div>

    <div x-show="open"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-75"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95"
            class="fixed {{ $width }} rounded-md shadow-2xl bg-white dark:bg-gray-800"
            :style="'display: ' + (open ? 'block' : 'none') + '; z-index: 99999999 !important; top: ' + position.top + 'px; left: ' + position.left + 'px; right: auto;'"
            @click.stop>
        <div class="rounded-md ring-1 ring-black ring-opacity-5 {{ $contentClasses }}">
            {{ $content }}
        </div>
    </div>
</div>
