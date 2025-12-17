<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Company;
use App\Models\Contact;
use App\Models\Deal;
use App\Models\Activity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function __construct()
    {
        // Middleware будет применен через роуты
    }
    
    public function index()
    {
        $stats = [
            'users' => User::count(),
            'companies' => Company::count(),
            'contacts' => Contact::count(),
            'deals' => Deal::count(),
            'activities' => Activity::count(),
            'total_deal_amount' => Deal::sum('amount'),
            'won_deals' => Deal::whereHas('stage', fn($q) => $q->where('name', 'Выиграно'))->count(),
        ];
        
        $recentUsers = User::latest()->take(10)->get();
        $recentDeals = Deal::with(['company', 'stage'])->latest()->take(10)->get();
        
        return view('admin.dashboard', compact('stats', 'recentUsers', 'recentDeals'));
    }
    
    public function users()
    {
        $users = User::latest()->paginate(20);
        return view('admin.users', compact('users'));
    }
    
    public function updateUser(Request $request, User $user)
    {
        // Админ не может менять себе роль
        if ($user->id === auth()->id()) {
            return back()->withErrors(['error' => 'Нельзя изменить свою роль']);
        }
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role' => 'required|in:employee,manager,admin',
            'is_admin' => 'boolean',
        ]);
        
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'is_admin' => $request->has('is_admin') || $request->role === 'admin',
        ]);
        
        return back()->with('success', 'Пользователь обновлен');
    }
    
    public function createUser(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8',
            'role' => 'required|in:employee,manager,admin',
        ]);
        
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'is_admin' => $request->role === 'admin',
        ]);
        
        return back()->with('success', 'Пользователь создан');
    }
    
    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->withErrors(['error' => 'Нельзя удалить самого себя']);
        }
        $user->delete();
        return back()->with('success', 'Пользователь удален');
    }
    
    public function settings()
    {
        return view('admin.settings');
    }
    
    public function updateSettings(Request $request)
    {
        // Здесь можно добавить настройки системы
        return back()->with('success', 'Настройки обновлены');
    }
    
    public function clearData()
    {
        \App\Models\Deal::truncate();
        \App\Models\Contact::truncate();
        \App\Models\Company::truncate();
        \App\Models\Activity::truncate();
        \App\Models\Note::truncate();
        
        return back()->with('success', 'Данные очищены');
    }
    
    public function database()
    {
        $tables = \DB::select('SHOW TABLES');
        $tableNames = array_map(function($table) {
            return array_values((array)$table)[0];
        }, $tables);
        
        return view('admin.database', compact('tableNames'));
    }
    
    public function executeQuery(Request $request)
    {
        $request->validate([
            'query' => 'required|string',
        ]);
        
        try {
            $query = $request->input('query');
            
            // Безопасность: запрещаем опасные операции
            $dangerous = ['DROP', 'TRUNCATE', 'DELETE FROM', 'ALTER TABLE', 'CREATE TABLE', 'CREATE DATABASE', 'DROP DATABASE'];
            $upperQuery = strtoupper(trim($query));
            foreach ($dangerous as $danger) {
                if (strpos($upperQuery, $danger) === 0) {
                    return back()->withErrors(['error' => 'Эта операция запрещена из соображений безопасности']);
                }
            }
            
            $results = \DB::select($query);
            $affected = \DB::affectingStatement($query);
            
            return back()->with([
                'success' => 'Запрос выполнен успешно',
                'results' => $results,
                'affected' => $affected,
            ]);
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Ошибка: ' . $e->getMessage()]);
        }
    }
    
    public function getTableData(Request $request)
    {
        $table = $request->input('table');
        if (!$table) {
            return response()->json(['error' => 'Таблица не указана'], 400);
        }
        
        try {
            $data = \DB::table($table)->limit(100)->get();
            $columns = \DB::getSchemaBuilder()->getColumnListing($table);
            
            return response()->json([
                'data' => $data,
                'columns' => $columns,
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
