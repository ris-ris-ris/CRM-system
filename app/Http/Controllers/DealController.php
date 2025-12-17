<?php

namespace App\Http\Controllers;

use App\Models\Deal;
use Illuminate\Http\Request;

class DealController extends Controller
{
    public function index(Request $request)
    {
        $q = Deal::query();
        if ($s = $request->query('q')) {
            $q->where('title', 'like', "%$s%");
        }

        // Filters
        if ($companyId = $request->query('company_id')) {
            $q->where('company_id', $companyId);
        }
        if ($contactId = $request->query('contact_id')) {
            $q->where('contact_id', $contactId);
        }
        if ($stageId = $request->query('stage_id')) {
            $q->where('stage_id', $stageId);
        }
        if ($userId = $request->query('user_id')) {
            $q->where('user_id', $userId);
        }
        if (($minAmount = $request->query('min_amount')) !== null && $minAmount !== '') {
            $q->where('amount', '>=', (float)$minAmount);
        }
        if (($maxAmount = $request->query('max_amount')) !== null && $maxAmount !== '') {
            $q->where('amount', '<=', (float)$maxAmount);
        }
        if ($from = $request->query('expected_close_from')) {
            $q->whereDate('expected_close_date', '>=', $from);
        }
        if ($to = $request->query('expected_close_to')) {
            $q->whereDate('expected_close_date', '<=', $to);
        }
        
        // Для отчетов - все видят общую статистику
        $forReports = $request->query('for_reports', false);
        
        // Обычные пользователи видят только свои сделки (кроме отчетов)
        $user = auth()->user();
        if (!$forReports && $user && !$user->is_admin && $user->role !== 'manager') {
            $q->where('user_id', $user->id);
        }
        // Менеджеры и админы видят все сделки (без фильтра)
        
        // Сортировка
        $sortBy = $request->query('sort_by', 'created_at');
        $sortOrder = $request->query('sort_order', 'desc');
        $allowedSortFields = ['id', 'title', 'amount', 'created_at', 'expected_close_date'];
        
        if (in_array($sortBy, $allowedSortFields)) {
            $q->orderBy($sortBy, $sortOrder);
        } elseif ($sortBy === 'company') {
            // Сортировка по названию компании
            $q->leftJoin('companies', 'deals.company_id', '=', 'companies.id')
              ->select('deals.*')
              ->orderBy('companies.name', $sortOrder);
        } elseif ($sortBy === 'stage') {
            // Сортировка по названию стадии
            $q->leftJoin('stages', 'deals.stage_id', '=', 'stages.id')
              ->select('deals.*')
              ->orderBy('stages.name', $sortOrder);
        } elseif ($sortBy === 'user') {
            // Сортировка по имени пользователя
            $q->leftJoin('users', 'deals.user_id', '=', 'users.id')
              ->select('deals.*')
              ->orderBy('users.name', $sortOrder);
        } else {
            $q->orderBy('created_at', 'desc');
        }
        
        $per = (int)($request->query('per_page', 100));
        $result = $q->with(['company', 'contact', 'stage', 'user'])->paginate($per);
        return response()->json($result);
    }

    public function store(Request $request)
    {
        $user = auth()->user();
        if (!$user) {
            return response()->json(['error' => 'Доступ запрещен'], 403);
        }
        
        $data = $request->validate([
            'company_id' => 'nullable|exists:companies,id',
            'contact_id' => 'nullable|exists:contacts,id',
            'stage_id' => 'nullable|exists:stages,id',
            'user_id' => 'nullable|exists:users,id',
            'title' => 'required|string|max:255',
            'amount' => 'nullable|numeric',
            'currency' => 'nullable|string|max:3',
            'expected_close_date' => 'nullable|date',
            'description' => 'nullable|string',
        ]);
        
        // Обычные пользователи могут создавать только "себе" и не могут назначать других.
        if (!$user->is_admin && $user->role !== 'manager') {
            $data['user_id'] = $user->id;
        } elseif (!isset($data['user_id'])) {
            $data['user_id'] = $user->id;
        }
        
        $deal = Deal::create($data);
        return response()->json($deal, 201);
    }

    public function show(Deal $deal)
    {
        return response()->json($deal);
    }

    public function update(Request $request, Deal $deal)
    {
        $user = auth()->user();
        if (!$user) {
            return response()->json(['error' => 'Доступ запрещен'], 403);
        }

        // Обычный пользователь может менять только свою сделку (в т.ч. перетаскивание по стадиям).
        if (!$user->is_admin && $user->role !== 'manager' && (int)$deal->user_id !== (int)$user->id) {
            return response()->json(['error' => 'Доступ запрещен'], 403);
        }
        
        $data = $request->validate([
            'company_id' => 'nullable|exists:companies,id',
            'contact_id' => 'nullable|exists:contacts,id',
            'stage_id' => 'nullable|exists:stages,id',
            'user_id' => 'nullable|exists:users,id',
            'title' => 'sometimes|required|string|max:255',
            'amount' => 'nullable|numeric',
            'currency' => 'nullable|string|max:3',
            'expected_close_date' => 'nullable|date',
            'description' => 'nullable|string',
        ]);
        
        // Обычный пользователь не может менять исполнителя.
        if (!$user->is_admin && $user->role !== 'manager') {
            unset($data['user_id']);
        }
        
        $deal->update($data);
        $deal->load(['company', 'contact', 'stage', 'user']);
        return response()->json($deal);
    }

    public function destroy(Deal $deal)
    {
        $user = auth()->user();
        if (!$user) {
            return response()->json(['error' => 'Доступ запрещен'], 403);
        }

        if (!$user->is_admin && $user->role !== 'manager' && (int)$deal->user_id !== (int)$user->id) {
            return response()->json(['error' => 'Доступ запрещен'], 403);
        }
        
        $deal->delete();
        return response()->json(['deleted' => true]);
    }
}
