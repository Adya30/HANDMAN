<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class RealtimeLaporanEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $action;
    public $title;
    public $message;
    public $userId;
    public $laporanId;

    public function __construct($action, $title, $message, $userId = null, $laporanId = null)
    {
        $this->action = $action;
        $this->title = $title;
        $this->message = $message;
        $this->userId = $userId;
        $this->laporanId = $laporanId;
    }

    public function broadcastOn()
    {
        return new Channel('laporan');
    }

    public function broadcastAs()
    {
        return 'RealtimeLaporanEvent';
    }
}
