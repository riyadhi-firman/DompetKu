<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        if (!$user) {
            return redirect('/login');
        }

        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;

        $totalIncome = Transaction::where('user_id', $user->id)
            ->where('type', 'income')
            ->whereMonth('transaction_date', $currentMonth)
            ->whereYear('transaction_date', $currentYear)
            ->sum('amount');

        $totalExpense = Transaction::where('user_id', $user->id)
            ->where('type', 'expense')
            ->whereMonth('transaction_date', $currentMonth)
            ->whereYear('transaction_date', $currentYear)
            ->sum('amount');

        $balance = $user->balance;

        // Get recent transactions
        $recentTransactions = Transaction::where('user_id', $user->id)
            ->with('category')
            ->orderBy('transaction_date', 'desc')
            ->take(5)
            ->get();
            
        $budgets = \App\Models\Budget::where('user_id', $user->id)
            ->where('month', $currentMonth)
            ->where('year', $currentYear)
            ->get();
            
        $overBudgetCategories = [];
        foreach ($budgets as $budget) {
            $catExpenses = Transaction::where('user_id', $user->id)
                ->where('category_id', $budget->category_id)
                ->where('type', 'expense')
                ->whereMonth('transaction_date', $currentMonth)
                ->whereYear('transaction_date', $currentYear)
                ->sum('amount');
                
            if ($catExpenses > $budget->amount) {
                $overBudgetCategories[] = $budget->category_id;
            }
        }

        return view('dashboard', compact('balance', 'totalIncome', 'totalExpense', 'recentTransactions', 'overBudgetCategories'));
    }

    public function markNotificationAsRead($id)
    {
        $notification = Auth::user()->notifications()->find($id);
        if ($notification) {
            $notification->markAsRead();
        }
        return back();
    }
}
