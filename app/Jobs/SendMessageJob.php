<?php

namespace App\Jobs;

use App\Services\MessageService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendMessageJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected array $data;
    protected int $senderId;

    public function __construct(array $data, int $senderId)
    {
        $this->data = $data;
        $this->senderId = $senderId;
    }

    public function handle(MessageService $messageService): void
    {
        $messageService->send($this->data, $this->senderId);
    }
}
