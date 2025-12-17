<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Contact;
use App\Models\Deal;
use App\Models\Stage;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $activitiesQuery = \App\Models\Activity::whereDate('due_at', today())->where('done', false);
        
        // Обычные пользователи видят только свои задачи
        if ($user && !$user->is_admin && $user->role !== 'manager') {
            $activitiesQuery->where('user_id', $user->id);
        }
        
        $wonStage = \App\Models\Stage::where('name', 'Выиграно')->first();
        $wonDealsQuery = Deal::whereHas('stage', fn($q) => $q->where('name', 'Выиграно'));
        
        // Прибыль по месяцам (только выигранные сделки)
        $monthlyProfit = [];
        for ($i = 1; $i <= 12; $i++) {
            $monthlyProfit[$i] = Deal::whereHas('stage', fn($q) => $q->where('name', 'Выиграно'))
                ->whereMonth('created_at', $i)
                ->whereYear('created_at', now()->year)
                ->sum('amount') ?? 0;
        }
        
        // Конверсия по месяцам
        $monthlyConversion = [];
        for ($i = 1; $i <= 12; $i++) {
            $monthDeals = Deal::whereMonth('created_at', $i)
                ->whereYear('created_at', now()->year)
                ->count();
            $monthWon = Deal::whereHas('stage', fn($q) => $q->where('name', 'Выиграно'))
                ->whereMonth('created_at', $i)
                ->whereYear('created_at', now()->year)
                ->count();
            $monthlyConversion[$i] = $monthDeals > 0 ? round(($monthWon / $monthDeals) * 100, 1) : 0;
        }
        
        $stats = [
            'companies' => Company::count(),
            'contacts' => Contact::count(),
            'deals' => Deal::count(),
            'total_amount' => Deal::sum('amount') ?? 0, // Общий оборот (все сделки)
            'net_profit' => $wonDealsQuery->sum('amount') ?? 0, // Чистая прибыль (только выигранные)
            'won_deals' => $wonDealsQuery->count(),
            'recent_deals' => Deal::with(['company', 'contact', 'stage'])->latest()->take(10)->get(),
            'activities_today' => $activitiesQuery->count(),
            'activities_today_list' => $activitiesQuery->with('user')->get(),
            'deals_this_month' => Deal::whereMonth('created_at', now()->month)->count(),
            'monthly_profit' => $monthlyProfit,
            'monthly_conversion' => $monthlyConversion,
        ];
        
        return view('dashboard', compact('stats'));
    }
}

