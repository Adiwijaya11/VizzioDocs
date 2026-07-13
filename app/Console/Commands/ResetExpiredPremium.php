<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class ResetExpiredPremium extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'premium:reset-expired';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reset premium status for users whose premium subscription has expired.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $expiredUsers = User::where('plan', 'premium')
                            ->whereNotNull('premium_expires_at')
                            ->where('premium_expires_at', '<', Carbon::now())
                            ->get();

        foreach ($expiredUsers as $user) {
            $user->plan = 'free'; // Atau plan default lainnya
            $user->daily_quota = 20; // Kuota harian default
            $user->premium_expires_at = null; // Opsional: hapus tanggal kedaluwarsa
            $user->save();
            $this->info("Premium status for user ID {$user->id} ({$user->email}) has been reset.");
        }

        $this->info('Expired premium subscriptions checked and reset successfully.');
    }
}
