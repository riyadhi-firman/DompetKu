<?php

namespace App\Listeners;

use App\Events\TransactionDeleted;

class RevertUserBalance
{
    public function handle(TransactionDeleted $event): void
    {
        $transaction = $event->transaction;
        $user = $transaction->user;

        if ($transaction->type === 'income') {
            $user->balance -= $transaction->amount;
        } else {
            $user->balance += $transaction->amount;
        }
        
        $user->save();
    }
}
