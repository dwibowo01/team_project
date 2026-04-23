<div class="relative" x-data="{ open: @entangle('open') }">
    {{-- Bell Button --}}
    <button @click="$wire.toggleOpen()"
            class="relative p-2 rounded-full text-gray-500 hover:text-gray-700 hover:bg-gray-100 dark:text-gray-400 dark:hover:text-gray-200 dark:hover:bg-gray-700 transition focus:outline-none focus:ring-2 focus:ring-indigo-500">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6 6 0 00-5-5.917V4a1 1 0 10-2 0v1.083A6 6 0 006 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
        </svg>
        @if($this->unreadCount > 0)
            <span class="absolute top-1 right-1 inline-flex items-center justify-center w-4 h-4 text-xs font-bold text-white bg-red-500 rounded-full">
                {{ $this->unreadCount > 9 ? '9+' : $this->unreadCount }}
            </span>
        @endif
    </button>

    {{-- Dropdown panel --}}
    <div x-show="open"
         x-transition:enter="transition ease-out duration-100"
         x-transition:enter-start="transform opacity-0 scale-95"
         x-transition:enter-end="transform opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-75"
         x-transition:leave-start="transform opacity-100 scale-100"
         x-transition:leave-end="transform opacity-0 scale-95"
         @click.outside="open = false"
         class="absolute right-0 mt-2 w-80 bg-white dark:bg-gray-800 rounded-lg shadow-lg ring-1 ring-black ring-opacity-5 z-50"
         style="display: none;">
        <div class="p-3 flex items-center justify-between border-b border-gray-100 dark:border-gray-700">
            <span class="text-sm font-semibold text-gray-700 dark:text-gray-200">Notifications</span>
            @if($this->unreadCount > 0)
                <button wire:click="markAllRead"
                        class="text-xs text-indigo-600 hover:text-indigo-800 dark:text-indigo-400 dark:hover:text-indigo-200">
                    Mark all read
                </button>
            @endif
        </div>

        <ul class="max-h-72 overflow-y-auto divide-y divide-gray-100 dark:divide-gray-700">
            @forelse($this->notifications as $notification)
                <li class="flex items-start gap-3 px-4 py-3 hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                    <div class="mt-0.5 flex-shrink-0 w-2 h-2 rounded-full bg-indigo-500"></div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm text-gray-800 dark:text-gray-200 truncate">
                            {{ $notification->data['message'] ?? 'New notification' }}
                        </p>
                        <p class="text-xs text-gray-400 mt-0.5">{{ $notification->created_at->diffForHumans() }}</p>
                    </div>
                    <button wire:click="markAsRead('{{ $notification->id }}')"
                            class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 flex-shrink-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                    </button>
                </li>
            @empty
                <li class="px-4 py-6 text-center text-sm text-gray-400 dark:text-gray-500">
                    No new notifications
                </li>
            @endforelse
        </ul>

        <div class="p-3 border-t border-gray-100 dark:border-gray-700 text-center">
            <a href="#" class="text-xs text-indigo-600 hover:text-indigo-800 dark:text-indigo-400">View all notifications</a>
        </div>
    </div>
</div>
