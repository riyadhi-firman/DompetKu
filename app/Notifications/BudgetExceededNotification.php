<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class BudgetExceededNotification extends Notification
{
    use Queueable;

    public $budget;
    public $totalExpenses;

    public function __construct($budget, $totalExpenses)
    {
        $this->budget = $budget;
        $this->totalExpenses = $totalExpenses;
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'budget_id' => $this->budget->id,
            'category_id' => $this->budget->category_id,
            'category_name' => $this->budget->category->name,
            'budget_amount' => $this->budget->amount,
            'total_expenses' => $this->totalExpenses,
            'message' => 'Pengeluaran Anda untuk kategori ' . $this->budget->category->name . ' telah melebihi budget (Budget: ' . number_format($this->budget->amount, 0, ',', '.') . ', Total: ' . number_format($this->totalExpenses, 0, ',', '.') . ').',
        ];
    }
}
