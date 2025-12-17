<?php

namespace App\Http\Controllers;

use App\Models\FileAttachment;
use Illuminate\Http\Request;

class FileAttachmentController extends Controller
{
    public function index(Request $request)
    {
        $q = FileAttachment::query();
        if ($type = $request->query('type')) $q->where('attachable_type', $type);
        if ($id = $request->query('id')) $q->where('attachable_id', $id);
        return response()->json($q->orderBy('id','desc')->paginate((int)$request->query('per_page', 15)));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'attachable_type' => 'required|string',
            'attachable_id' => 'required|integer',
            'path' => 'required|string',
            'original_name' => 'required|string',
            'size' => 'nullable|integer',
        ]);
        $file = FileAttachment::create($data);
        return response()->json($file, 201);
    }

    public function show(FileAttachment $file)
    {
        return response()->json($file);
    }

    public function update(Request $request, FileAttachment $file)
    {
        $data = $request->validate([
            'path' => 'sometimes|required|string',
            'original_name' => 'sometimes|required|string',
            'size' => 'nullable|integer',
        ]);
        $file->update($data);
        return response()->json($file);
    }

    public function destroy(FileAttachment $file)
    {
        $file->delete();
        return response()->json(['deleted' => true]);
    }
}
