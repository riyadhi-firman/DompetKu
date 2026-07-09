<?php

namespace App\Http\Controllers;

use App\Models\Budget;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Requests\StoreBudgetRequest;
use Illuminate\Support\Facades\Auth;

class BudgetController extends Controller
{
    public function index()
    {
        $budgets = Budget::where('user_id', Auth::id())
            ->with('category')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->get();
            
        // Hitung total pengeluaran untuk setiap budget
        foreach ($budgets as $budget) {
            $budget->total_expenses = \App\Models\Transaction::where('user_id', Auth::id())
                ->where('category_id', $budget->category_id)
                ->where('type', 'expense')
                ->whereMonth('transaction_date', $budget->month)
                ->whereYear('transaction_date', $budget->year)
                ->sum('amount');
        }
            
        return view('budgets.index', compact('budgets'));
    }

    public function create()
    {
        $categories = Category::where('user_id', Auth::id())->where('type', 'expense')->get();
        return view('budgets.create', compact('categories'));
    }

    public function store(StoreBudgetRequest $request)
    {
        $validated = $request->validated();
        $validated['user_id'] = Auth::id();

        // Check if budget for this category, month, year already exists
        $existing = Budget::where('user_id', Auth::id())
            ->where('category_id', $validated['category_id'])
            ->where('month', $validated['month'])
            ->where('year', $validated['year'])
            ->first();

        if ($existing) {
            $existing->update(['amount' => $validated['amount']]);
            return redirect()->route('budgets.index')->with('success', 'Budget berhasil diperbarui');
        }

        Budget::create($validated);

        return redirect()->route('budgets.index')->with('success', 'Budget berhasil ditambahkan');
    }

    public function edit(Budget $budget)
    {
        if ($budget->user_id !== Auth::id()) abort(403);
        $categories = Category::where('user_id', Auth::id())->where('type', 'expense')->get();
        return view('budgets.edit', compact('budget', 'categories'));
    }

    public function update(StoreBudgetRequest $request, Budget $budget)
    {
        if ($budget->user_id !== Auth::id()) abort(403);
        
        $budget->update($request->validated());

        return redirect()->route('budgets.index')->with('success', 'Budget berhasil diperbarui');
    }

    public function destroy(Budget $budget)
    {
        if ($budget->user_id !== Auth::id()) abort(403);
        
        $budget->delete();

        return redirect()->route('budgets.index')->with('success', 'Budget berhasil dihapus');
    }
}
