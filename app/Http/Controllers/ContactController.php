<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function index(Request $request)
    {
        $query = Contact::query();
        if ($s = $request->query('q')) {
            $query->where(function ($q) use ($s) {
                $q->where('first_name', 'like', "%$s%")
                  ->orWhere('last_name', 'like', "%$s%")
                  ->orWhere('email', 'like', "%$s%");
            });
        }
        if ($companyId = $request->query('company_id')) {
            $query->where('company_id', $companyId);
        }
        $sortBy = $request->query('sort_by', 'id');
        $sortOrder = $request->query('sort_order', 'desc');
        $allowedSortFields = ['id', 'first_name', 'last_name', 'email', 'phone', 'created_at'];
        if (in_array($sortBy, $allowedSortFields)) {
            $query->orderBy($sortBy, $sortOrder);
        } elseif ($sortBy === 'company') {
            $query->leftJoin('companies', 'contacts.company_id', '=', 'companies.id')
                ->select('contacts.*')
                ->orderBy('companies.name', $sortOrder);
        } else {
            $query->orderBy('id', 'desc');
        }
        $per = (int)($request->query('per_page', 100));
        return response()->json($query->with('company')->paginate($per));
    }

    public function store(Request $request)
    {
        $user = auth()->user();
        if (!$user || (!$user->is_admin && $user->role !== 'manager')) {
            return response()->json(['error' => 'Доступ запрещен'], 403);
        }
        
        $data = $request->validate([
            'company_id' => 'nullable|exists:companies,id',
            'first_name' => 'required|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:100',
            'position' => 'nullable|string|max:255',
        ]);
        $contact = Contact::create($data);
        return response()->json($contact, 201);
    }

    public function show(Contact $contact)
    {
        return response()->json($contact);
    }

    public function update(Request $request, Contact $contact)
    {
        $user = auth()->user();
        if (!$user || (!$user->is_admin && $user->role !== 'manager')) {
            return response()->json(['error' => 'Доступ запрещен'], 403);
        }
        
        $data = $request->validate([
            'company_id' => 'nullable|exists:companies,id',
            'first_name' => 'sometimes|required|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:100',
            'position' => 'nullable|string|max:255',
        ]);
        $contact->update($data);
        return response()->json($contact);
    }

    public function destroy(Contact $contact)
    {
        $user = auth()->user();
        if (!$user || (!$user->is_admin && $user->role !== 'manager')) {
            return response()->json(['error' => 'Доступ запрещен'], 403);
        }
        
        $contact->delete();
        return response()->json(['deleted' => true]);
    }
}
