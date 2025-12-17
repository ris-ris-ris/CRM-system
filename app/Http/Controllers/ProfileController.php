<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        $user = $request->user();
        
        // Статистика пользователя
        $wonStageId = \App\Models\Stage::where('name', 'Выиграно')->value('id');
        $lostStageId = \App\Models\Stage::where('name', 'Проиграно')->value('id');
        
        // Выигранные сделки
        $wonDeals = \App\Models\Deal::where('user_id', $user->id)
            ->where('stage_id', $wonStageId);
        
        // Активные сделки - не выигранные и не проигранные
        $activeDeals = \App\Models\Deal::where('user_id', $user->id)
            ->whereNotIn('stage_id', [$wonStageId, $lostStageId]);
        
        $stats = [
            'deals_count' => $activeDeals->count(), // Только активные (не закрытые) сделки
            'deals_amount' => $wonDeals->sum('amount') ?? 0, // Только выигранные сделки (прибыль)
            'won_deals' => $wonDeals->count(),
            'activities_count' => \App\Models\Activity::where('user_id', $user->id)->count(),
            'activities_today' => \App\Models\Activity::where('user_id', $user->id)
                ->whereDate('due_at', today())->count(),
        ];
        
        // График сделок по месяцам - только выигранные (прибыль)
        $dealsByMonth = \App\Models\Deal::where('user_id', $user->id)
            ->whereHas('stage', fn($q) => $q->where('name', 'Выиграно'))
            ->selectRaw('MONTH(created_at) as month, COUNT(*) as count, SUM(amount) as total')
            ->whereYear('created_at', date('Y'))
            ->groupBy('month')
            ->get()
            ->keyBy('month');
        
        $monthlyData = [];
        for ($i = 1; $i <= 12; $i++) {
            $monthlyData[] = [
                'month' => $i,
                'count' => $dealsByMonth->has($i) ? $dealsByMonth[$i]->count : 0,
                'total' => $dealsByMonth->has($i) ? $dealsByMonth[$i]->total : 0,
            ];
        }
        
        // График сделок по стадиям - все сделки пользователя
        $dealsByStage = \App\Models\Deal::where('user_id', $user->id)
            ->join('stages', 'deals.stage_id', '=', 'stages.id')
            ->selectRaw('stages.name, COUNT(*) as count')
            ->groupBy('stages.name')
            ->get();
        
        return view('profile.edit', [
            'user' => $user,
            'stats' => $stats,
            'monthlyData' => $monthlyData,
            'dealsByStage' => $dealsByStage,
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
