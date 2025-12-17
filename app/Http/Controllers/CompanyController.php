<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    public function index(Request $request)
    {
        $query = Company::query();
        if ($search = $request->query('q')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                  ->orWhere('email', 'like', "%$search%");
            });
        }
        $sortBy = $request->query('sort_by', 'id');
        $sortOrder = $request->query('sort_order', 'desc');
        $allowedSortFields = ['id', 'name', 'email', 'phone', 'city', 'created_at'];
        if (in_array($sortBy, $allowedSortFields)) {
            $query->orderBy($sortBy, $sortOrder);
        } else {
            $query->orderBy('id', 'desc');
        }
        $perPage = (int)($request->query('per_page', 100));
        return response()->json($query->withCount('contacts')->paginate($perPage));
    }

    public function store(Request $request)
    {
        $user = auth()->user();
        if (!$user || (!$user->is_admin && $user->role !== 'manager')) {
            return response()->json(['error' => 'Доступ запрещен'], 403);
        }
        
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:100',
            'website' => 'nullable|url|max:255',
            'industry' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:255',
            'address' => 'nullable|string',
        ]);
        $company = Company::create($data);
        return response()->json($company, 201);
    }

    public function show(Company $company)
    {
        return response()->json($company);
    }

    public function update(Request $request, Company $company)
    {
        $user = auth()->user();
        if (!$user || (!$user->is_admin && $user->role !== 'manager')) {
            return response()->json(['error' => 'Доступ запрещен'], 403);
        }
        
        $data = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:100',
            'website' => 'nullable|url|max:255',
            'industry' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:255',
            'address' => 'nullable|string',
        ]);
        $company->update($data);
        return response()->json($company);
    }

    public function destroy(Company $company)
    {
        $user = auth()->user();
        if (!$user || (!$user->is_admin && $user->role !== 'manager')) {
            return response()->json(['error' => 'Доступ запрещен'], 403);
        }
        
        $company->delete();
        return response()->json(['deleted' => true]);
    }
}
