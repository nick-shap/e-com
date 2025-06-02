<?php

namespace App\Jobs;

use App\Enums\OrderStatusEnum;
use App\Models\Order;
use App\Services\OrderService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CancelUnpaidOrder implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(
        private Order $order
    ) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $order = $this->order->fresh();

        if ($order->status === OrderStatusEnum::PENDING) {
            app(OrderService::class)->markAsCancelled($this->order);
        }

    }
}
