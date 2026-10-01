<?php

use App\Console\Commands\ExpireOutdatedTokens;
use Illuminate\Support\Facades\Schedule;

// Expire outdated survey tokens every day at midnight
Schedule::command(ExpireOutdatedTokens::class)->dailyAt('00:00');
