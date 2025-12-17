<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Contact;
use App\Models\Deal;
use App\Models\Note;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class NoteController extends Controller
{
    private function normalizeNotableType(?string $type): ?string
    {
        if ($type === null) return null;
        // Поддержка ошибочных строк вида "App\\Models\\Company"
        return str_replace('\\\\', '\\', $type);
    }

    public function index(Request $request)
    {
        $q = Note::query();
        if ($type = $request->query('type')) {
            $type = $this->normalizeNotableType($type);
            $legacy = str_replace('\\', '\\\\', $type);
            $q->whereIn('notable_type', [$type, $legacy]);
        }
        if ($id = $request->query('id')) $q->where('notable_id', $id);
        return response()->json($q->orderBy('id','desc')->paginate((int)$request->query('per_page', 15)));
    }

    public function store(Request $request)
    {
        $user = auth()->user();
        if (!$user || (!$user->is_admin && $user->role !== 'manager')) {
            return response()->json(['error' => 'Доступ запрещен'], 403);
        }
        
        $allowed = [Company::class, Contact::class, Deal::class];
        $allowedLegacy = array_map(fn ($c) => str_replace('\\', '\\\\', $c), $allowed);
        $allowedAll = array_values(array_unique(array_merge($allowed, $allowedLegacy)));

        $data = $request->validate([
            'notable_type' => ['required', 'string', Rule::in($allowedAll)],
            'notable_id' => 'required|integer',
            'body' => 'required|string',
        ]);
        $data['notable_type'] = $this->normalizeNotableType($data['notable_type']);
        $note = Note::create($data);
        return response()->json($note, 201);
    }

    public function show(Note $note)
    {
        return response()->json($note);
    }

    public function update(Request $request, Note $note)
    {
        $user = auth()->user();
        if (!$user || (!$user->is_admin && $user->role !== 'manager')) {
            return response()->json(['error' => 'Доступ запрещен'], 403);
        }
        
        $data = $request->validate([
            'body' => 'sometimes|required|string',
        ]);
        $note->update($data);
        return response()->json($note);
    }

    public function destroy(Note $note)
    {
        $user = auth()->user();
        if (!$user || (!$user->is_admin && $user->role !== 'manager')) {
            return response()->json(['error' => 'Доступ запрещен'], 403);
        }
        
        $note->delete();
        return response()->json(['deleted' => true]);
    }
}
