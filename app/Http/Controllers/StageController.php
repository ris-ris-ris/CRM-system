<?php

namespace App\Http\Controllers;

use App\Models\Stage;
use Illuminate\Http\Request;

class StageController extends Controller
{
    public function index(Request $request)
    {
        return response()->json(Stage::orderBy('order')->get());
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'order' => 'nullable|integer',
        ]);
        $stage = Stage::create($data);
        return response()->json($stage, 201);
    }

    public function show(Stage $stage)
    {
        return response()->json($stage);
    }

    public function update(Request $request, Stage $stage)
    {
        $data = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'order' => 'nullable|integer',
        ]);
        $stage->update($data);
        return response()->json($stage);
    }

    public function destroy(Stage $stage)
    {
        $stage->delete();
        return response()->json(['deleted' => true]);
    }
}
