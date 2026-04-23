@props(['href', 'active' => false])

<a href="{{ $href }}"
   class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition-all duration-150 group
          {{ $active
               ? 'bg-indigo-600 text-white'
               : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
    {{-- Icon slot --}}
    <span class="flex-shrink-0 {{ $active ? 'text-white' : 'text-gray-400 group-hover:text-white' }}">
        {{ $icon }}
    </span>
    {{-- Label hidden when sidebar is collapsed --}}
    <span x-show="!collapsed" x-transition.opacity class="truncate">
        {{ $slot }}
    </span>
</a>
