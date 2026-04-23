<?php

namespace App\Livewire;

use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Collection;
use Livewire\Component;

class NotificationBell extends Component
{
    public bool $open = false;

    /**
     * Get the latest unread notifications.
     */
    public function getNotificationsProperty(): Collection
    {
        if (! auth()->check()) {
            return collect();
        }

        return auth()->user()
            ->unreadNotifications()
            ->latest()
            ->take(5)
            ->get();
    }

    /**
     * Total unread count.
     */
    public function getUnreadCountProperty(): int
    {
        if (! auth()->check()) {
            return 0;
        }

        return auth()->user()->unreadNotifications()->count();
    }

    /**
     * Mark a single notification as read.
     */
    public function markAsRead(string $id): void
    {
        $notification = DatabaseNotification::find($id);

        if ($notification && $notification->notifiable_id === auth()->id()) {
            $notification->markAsRead();
        }
    }

    /**
     * Mark all notifications as read.
     */
    public function markAllRead(): void
    {
        auth()->user()->unreadNotifications()->update(['read_at' => now()]);
    }

    public function toggleOpen(): void
    {
        $this->open = ! $this->open;
    }

    public function render()
    {
        return view('livewire.notification-bell');
    }
}
