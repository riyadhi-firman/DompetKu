<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\Budget;
use App\Models\Transaction;
use Illuminate\Database\Seeder;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Get all users in the system
        $users = User::all();

        if ($users->isEmpty()) {
            $this->command->info('Tidak ada user ditemukan. Silakan register terlebih dahulu.');
            return;
        }

        // Clean up old data to avoid duplicates
        Schema::disableForeignKeyConstraints();
        DB::table('transactions')->truncate();
        DB::table('budgets')->truncate();
        DB::table('categories')->truncate();
        DB::table('notifications')->truncate();
        Schema::enableForeignKeyConstraints();

        $now = Carbon::now();

        foreach ($users as $user) {
            // Reset balance
            $user->update(['balance' => 0]);

            // 1. Create Dummy Categories
            $categories = [
                ['name' => 'Gaji', 'type' => 'income', 'icon' => 'fas fa-money-bill-wave', 'color' => '#10b981'],
                ['name' => 'Freelance', 'type' => 'income', 'icon' => 'fas fa-laptop-code', 'color' => '#3b82f6'],
                ['name' => 'Makanan', 'type' => 'expense', 'icon' => 'fas fa-hamburger', 'color' => '#ef4444'],
                ['name' => 'Transportasi', 'type' => 'expense', 'icon' => 'fas fa-car', 'color' => '#f59e0b'],
                ['name' => 'Tagihan', 'type' => 'expense', 'icon' => 'fas fa-file-invoice-dollar', 'color' => '#8b5cf6'],
                ['name' => 'Hiburan', 'type' => 'expense', 'icon' => 'fas fa-gamepad', 'color' => '#ec4899'],
            ];

            $categoryModels = [];
            foreach ($categories as $cat) {
                $categoryModels[$cat['name']] = Category::create(array_merge($cat, ['user_id' => $user->id]));
            }

            // 2. Create Dummy Budgets
            // Set Makanan budget to a low amount intentionally to trigger the alert
            $makananBudget = Budget::create([
                'user_id' => $user->id,
                'category_id' => $categoryModels['Makanan']->id,
                'amount' => 500000, // Budget 500k
                'month' => date('m'),
                'year' => date('Y'),
            ]);

            Budget::create([
                'user_id' => $user->id,
                'category_id' => $categoryModels['Transportasi']->id,
                'amount' => 1000000,
                'month' => date('m'),
                'year' => date('Y'),
            ]);

            // 3. Create Dummy Transactions using Eloquent to trigger Events (Notifications & Balance Update)
            
            // Income - Gaji (Start of Month)
            Transaction::create([
                'user_id' => $user->id,
                'category_id' => $categoryModels['Gaji']->id,
                'type' => 'income',
                'amount' => 8000000,
                'description' => 'Gaji Bulan Ini',
                'transaction_date' => $now->copy()->startOfMonth(),
            ]);

            // Income - Freelance (Random date)
            Transaction::create([
                'user_id' => $user->id,
                'category_id' => $categoryModels['Freelance']->id,
                'type' => 'income',
                'amount' => 2500000,
                'description' => 'Proyek Website',
                'transaction_date' => $now->copy()->subDays(10),
            ]);

            // Expenses - Various (Random past days)
            $expenses = [
                ['cat' => 'Transportasi', 'amount' => 200000, 'desc' => 'Isi Bensin Motor', 'days_ago' => 3],
                ['cat' => 'Tagihan', 'amount' => 450000, 'desc' => 'Listrik & Air', 'days_ago' => 5],
                ['cat' => 'Tagihan', 'amount' => 300000, 'desc' => 'Internet / WiFi', 'days_ago' => 6],
                ['cat' => 'Hiburan', 'amount' => 120000, 'desc' => 'Langganan Netflix & Spotify', 'days_ago' => 8],
                
                // Makanan expenses that will exceed the 500k budget
                ['cat' => 'Makanan', 'amount' => 200000, 'desc' => 'Belanja Bulanan', 'days_ago' => 3], // Total 200k
                ['cat' => 'Makanan', 'amount' => 150000, 'desc' => 'Makan Malam Bareng Teman', 'days_ago' => 2], // Total 350k
                ['cat' => 'Makanan', 'amount' => 250000, 'desc' => 'Traktir Ulang Tahun', 'days_ago' => 1], // Total 600k (EXCEEDS 500K! Triggers alert)
            ];

            foreach ($expenses as $exp) {
                // Using create() triggers the 'created' event -> UpdateUserBalance listener -> Sends Notification!
                Transaction::create([
                    'user_id' => $user->id,
                    'category_id' => $categoryModels[$exp['cat']]->id,
                    'type' => 'expense',
                    'amount' => $exp['amount'],
                    'description' => $exp['desc'],
                    'transaction_date' => $now->copy()->subDays($exp['days_ago']),
                ]);
            }
        }
    }
}
