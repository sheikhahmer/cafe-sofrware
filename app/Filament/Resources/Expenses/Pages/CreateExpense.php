<?php

namespace App\Filament\Resources\Expenses\Pages;

use App\Filament\Resources\Expenses\ExpenseResource;
use App\Models\Expense;
use Filament\Resources\Pages\CreateRecord;

class CreateExpense extends CreateRecord
{
    protected static string $resource = ExpenseResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function handleRecordCreation(array $data): \Illuminate\Database\Eloquent\Model
    {
        return new Expense();
    }
    protected function afterCreate(): void
    {
        foreach ($this->form->getState()['items'] as $item) {
            Expense::create([
                'account_description' => 'DAILY EXPENSES',
                'product'             => $item['product'],
                'debit'               => $item['debit'],
            ]);
        }
    }
}
