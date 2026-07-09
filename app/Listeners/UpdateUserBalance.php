<?php

namespace App\Listeners;

use App\Events\TransactionCreated;
use App\Models\Budget;
use App\Notifications\BudgetExceededNotification;

class UpdateUserBalance
{
    public function handle(TransactionCreated $event): void
    {
        $transaction = $event->transaction;
        $user = $transaction->user;

        if ($transaction->type === 'income') {
            $user->balance += $transaction->amount;
        } else {
            $user->balance -= $transaction->amount;
            
            // Check budget
            $month = \Carbon\Carbon::parse($transaction->transaction_date)->month;
            $year = \Carbon\Carbon::parse($transaction->transaction_date)->year;
            
            $budget = Budget::where('user_id', $user->id)
                ->where('category_id', $transaction->category_id)
                ->where('month', $month)
                ->where('year', $year)
                ->first();
                
            if ($budget) {
                // Get total expenses for this category in this month
                $totalExpenses = \App\Models\Transaction::where('user_id', $user->id)
                    ->where('category_id', $transaction->category_id)
                    ->where('type', 'expense')
                    ->whereMonth('transaction_date', $month)
                    ->whereYear('transaction_date', $year)
                    ->sum('amount');
                    
                if ($totalExpenses > $budget->amount) {
                    $user->notify(new BudgetExceededNotification($budget, $totalExpenses));
                }
            }
        }
        
        $user->save();
    }
}
