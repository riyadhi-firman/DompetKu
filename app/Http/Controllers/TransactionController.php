<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Requests\StoreTransactionRequest;
use App\Http\Requests\UpdateTransactionRequest;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $query = Transaction::where('user_id', Auth::id())->with('category');

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('month') && $request->filled('year')) {
            $query->whereMonth('transaction_date', $request->month)
                  ->whereYear('transaction_date', $request->year);
        }

        $transactions = $query->orderBy('transaction_date', 'desc')->paginate(10)->withQueryString();
        $categories = Category::where('user_id', Auth::id())->get();
            
        $currentMonth = date('m');
        $currentYear = date('Y');
        
        $budgets = \App\Models\Budget::where('user_id', Auth::id())
            ->where('month', $currentMonth)
            ->where('year', $currentYear)
            ->get();
            
        $overBudgetCategories = [];
        
        foreach ($budgets as $budget) {
            $totalExpenses = Transaction::where('user_id', Auth::id())
                ->where('category_id', $budget->category_id)
                ->where('type', 'expense')
                ->whereMonth('transaction_date', $currentMonth)
                ->whereYear('transaction_date', $currentYear)
                ->sum('amount');
                
            if ($totalExpenses > $budget->amount) {
                $overBudgetCategories[] = $budget->category_id;
            }
        }
            
        return view('transactions.index', compact('transactions', 'overBudgetCategories', 'categories'));
    }

    public function create()
    {
        $categories = Category::where('user_id', Auth::id())->get();
        
        $currentMonth = date('m');
        $currentYear = date('Y');
        
        $budgets = \App\Models\Budget::where('user_id', Auth::id())
            ->where('month', $currentMonth)
            ->where('year', $currentYear)
            ->get()->keyBy('category_id');
            
        $categoryUsage = [];
        foreach ($categories as $category) {
            if ($category->type == 'expense' && isset($budgets[$category->id])) {
                $used = \App\Models\Transaction::where('user_id', Auth::id())
                    ->where('category_id', $category->id)
                    ->whereMonth('transaction_date', $currentMonth)
                    ->whereYear('transaction_date', $currentYear)
                    ->sum('amount');
                $categoryUsage[$category->id] = [
                    'limit' => $budgets[$category->id]->amount,
                    'used' => $used,
                ];
            }
        }
        $budgetData = json_encode($categoryUsage);
        
        return view('transactions.create', compact('categories', 'budgetData'));
    }

    public function store(StoreTransactionRequest $request)
    {
        $validated = $request->validated();
        $validated['user_id'] = Auth::id();

        Transaction::create($validated);
        // Event TransactionCreated is automatically dispatched via model

        return redirect()->route('transactions.index')->with('success', 'Transaksi berhasil ditambahkan');
    }

    public function edit(Transaction $transaction)
    {
        if ($transaction->user_id !== Auth::id()) abort(403);
        $categories = Category::where('user_id', Auth::id())->get();
        return view('transactions.edit', compact('transaction', 'categories'));
    }

    public function update(UpdateTransactionRequest $request, Transaction $transaction)
    {
        if ($transaction->user_id !== Auth::id()) abort(403);
        
        $oldType = $transaction->type;
        $oldAmount = $transaction->amount;
        
        $transaction->update($request->validated());
        
        // Manual balance adjustment for update
        $user = Auth::user();
        
        // Revert old
        if ($oldType === 'income') {
            $user->balance -= $oldAmount;
        } else {
            $user->balance += $oldAmount;
        }
        
        // Apply new
        if ($transaction->type === 'income') {
            $user->balance += $transaction->amount;
        } else {
            $user->balance -= $transaction->amount;
        }
        $user->save();

        return redirect()->route('transactions.index')->with('success', 'Transaksi berhasil diperbarui');
    }

    public function destroy(Transaction $transaction)
    {
        if ($transaction->user_id !== Auth::id()) abort(403);
        
        // Event TransactionDeleted is automatically dispatched
        $transaction->delete();

        return redirect()->route('transactions.index')->with('success', 'Transaksi berhasil dihapus');
    }

    public function export(Request $request)
    {
        $query = Transaction::where('user_id', Auth::id())->with('category');

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('month') && $request->filled('year')) {
            $query->whereMonth('transaction_date', $request->month)
                  ->whereYear('transaction_date', $request->year);
        }

        $transactions = $query->orderBy('transaction_date', 'desc')->get();

        $currentMonth = date('m');
        $currentYear = date('Y');
        
        $budgets = \App\Models\Budget::where('user_id', Auth::id())
            ->where('month', $currentMonth)
            ->where('year', $currentYear)
            ->get();
            
        $overBudgetCategories = [];
        
        foreach ($budgets as $budget) {
            $totalExpenses = Transaction::where('user_id', Auth::id())
                ->where('category_id', $budget->category_id)
                ->where('type', 'expense')
                ->whereMonth('transaction_date', $currentMonth)
                ->whereYear('transaction_date', $currentYear)
                ->sum('amount');
                
            if ($totalExpenses > $budget->amount) {
                $overBudgetCategories[] = $budget->category_id;
            }
        }

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('transactions.pdf', compact('transactions', 'overBudgetCategories'));
        
        return $pdf->stream("transaksi_" . date('Y-m-d') . ".pdf");
    }
}
