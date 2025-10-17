<?php

namespace App\Filament\Resources\Expenses\Pages;

use App\Filament\Resources\Expenses\ExpenseResource;
use Filament\Resources\Pages\Page;

class ManageExpenses extends Page
{
    protected static string $resource = ExpenseResource::class;

    protected string $view = 'filament.resources.expenses.pages.manage-expenses';
}
