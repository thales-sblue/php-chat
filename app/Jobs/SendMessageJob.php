<?php

namespace App\Jobs;

use App\Services\MessageService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SendMessageJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected int $messageId;

    public function __construct(int $messageId)
    {
        $this->messageId = $messageId;
    }

    public function handle(MessageService $messageService): void
    {
        try {
            Log::info("Executando job para mensagem {$this->messageId}");
            $message = $messageService->markAsSent($this->messageId);

            if (!$message) {
                Log::warning("Mensagem {$this->messageId} não encontrada.");
            }
        } catch (\Throwable $e) {
            Log::error("Erro ao processar mensagem {$this->messageId}: " . $e->getMessage());
        }
    }
}
