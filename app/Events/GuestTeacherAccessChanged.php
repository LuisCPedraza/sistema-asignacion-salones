<?php

namespace App\Events;

use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithBroadcasting;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class GuestTeacherAccessChanged
{
    use Dispatchable, InteractsWithBroadcasting, SerializesModels;

    /**
     * Tipos de cambios posibles
     */
    public const ACTION_CREATED = 'created';
    public const ACTION_UPDATED = 'updated';
    public const ACTION_REVOKED = 'revoked';

    /**
     * Create a new event instance.
     */
    public function __construct(
        public User $user,
        public string $action,
        public ?array $oldData = null,
        public ?array $newData = null,
        public ?User $performedBy = null,
    ) {
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('channel-name'),
        ];
    }
}
