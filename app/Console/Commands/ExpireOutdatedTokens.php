<?php

namespace App\Console\Commands;

use App\Models\ExitSurvey;
use Illuminate\Console\Command;

class ExpireOutdatedTokens extends Command
{
    protected $signature = 'exit-survey:expire-tokens';

    protected $description = 'Mark all pending exit survey tokens that have passed their expiry date as expired';

    public function handle(): int
    {
        $expired = ExitSurvey::where('status', 'pending')
            ->where('expires_at', '<', now())
            ->update(['status' => 'expired']);

        $this->info("Expired {$expired} outdated token(s).");

        return self::SUCCESS;
    }
}
