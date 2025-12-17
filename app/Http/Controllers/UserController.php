<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
        return response()->json($users);
    }
    
    public function show(User $user)
    {
        $wonStageName = 'Выиграно';

        $stats = [
            // Личная статистика пользователя
            'deals' => \App\Models\Deal::where('user_id', $user->id)->count(),
            'activities' => \App\Models\Activity::where('user_id', $user->id)->count(),
            'won_deals' => \App\Models\Deal::where('user_id', $user->id)
                ->whereHas('stage', fn($q) => $q->where('name', $wonStageName))
                ->count(),
            'won_amount' => (float) \App\Models\Deal::where('user_id', $user->id)
                ->whereHas('stage', fn($q) => $q->where('name', $wonStageName))
                ->sum('amount'),
        ];
        
        return response()->json([
            'user' => $user,
            'stats' => $stats
        ]);
    }
}


