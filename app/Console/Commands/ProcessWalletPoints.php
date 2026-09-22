<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ProcessWalletPoints extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'wallet:process-points';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $records = \App\Models\Wallet::where('is_processed', 0)->get();

        foreach ($records as $item) {

            if ($item->is_processed == 1) {
                continue;
            }

            $user = \App\Models\User::find($item->user_id);

            if ($user) {

                // 100 points = Rs. 1
                $walletAmount = round($item->points / 100, 2);
                $user->wallet_points += $walletAmount;
                $user->save();

                $item->is_processed = 1;
                $item->type = 'debit';
                $item->description = "Processed & added Rs. {$walletAmount} to wallet";
                $item->save();

                \Log::info('Processed wallet ID: ' . $item->id);
            }
        }

        \Log::info('Wallet points processed');
    }
}
