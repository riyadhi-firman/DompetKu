<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function index()
    {
        return view('reports.index');
    }

    public function data(Request $request)
    {
        $year = $request->input('year', Carbon::now()->year);
        $user_id = Auth::id();

        $transactions = Transaction::where('user_id', $user_id)
            ->whereYear('transaction_date', $year)
            ->get();

        $monthlyData = [];
        for ($i = 1; $i <= 12; $i++) {
            $monthlyData[$i] = ['income' => 0, 'expense' => 0];
        }

        foreach ($transactions as $t) {
            $month = Carbon::parse($t->transaction_date)->month;
            if ($t->type === 'income') {
                $monthlyData[$month]['income'] += $t->amount;
            } else {
                $monthlyData[$month]['expense'] += $t->amount;
            }
        }

        $labels = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
        $incomeData = [];
        $expenseData = [];

        for ($i = 1; $i <= 12; $i++) {
            $incomeData[] = $monthlyData[$i]['income'];
            $expenseData[] = $monthlyData[$i]['expense'];
        }

        return response()->json([
            'labels' => $labels,
            'income' => $incomeData,
            'expense' => $expenseData,
            'year' => $year
        ]);
    }
}
