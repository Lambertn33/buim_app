<?php

namespace App\Jobs;

use App\Services\MessagesServices;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class TokenGenerated implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $screener;
    private $generatedToken;
    private $validityDays;

    /**
     * Create a new job instance.
     */
    public function __construct($screener, $generatedToken, $validityDays)
    {
        $this->screener = $screener;
        $this->generatedToken = $generatedToken;
        $this->validityDays = $validityDays;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $message = 'Dear '. $this->screener->prospect_names .' you have been given a token valid for '.$this->validityDays.'days. The token is '. $this->generatedToken.'';
        (new MessagesServices)->sendMessage($this->screener->prospect_telephone, $message); 
    }
}
