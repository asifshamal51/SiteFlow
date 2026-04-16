<?php

namespace App\Http\Controllers;

use App\Models\Shareholder;
use Illuminate\Http\Request;

class ShareholderController extends Controller
{
    // GET ALL
    public function index()
    {
        return response()->json([
            'shareholders' => Shareholder::latest()->get()
        ]);
    }

    // CREATE
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string',
            'phone' => 'nullable|string',
            'email' => 'nullable|email',
            'address' => 'nullable|string',
            'national_id' => 'nullable|string',
            'code' => 'required|unique:shareholders,code',
            'status' => 'nullable|string',
        ]);

        $data['created_by'] = $request->user()->id;

        $shareholder = Shareholder::create($data);

        return response()->json([
            'message' => 'Shareholder created',
            'shareholder' => $shareholder
        ]);
    }

    // SHOW SINGLE
    public function show($id)
    {
        return response()->json([
            'shareholder' => Shareholder::findOrFail($id)
        ]);
    }

    // UPDATE
    public function update(Request $request, $id)
    {
        $shareholder = Shareholder::findOrFail($id);

        $data = $request->validate([
            'name' => 'sometimes|string',
            'phone' => 'nullable|string',
            'email' => 'nullable|email',
            'address' => 'nullable|string',
            'national_id' => 'nullable|string',
            'status' => 'nullable|string',
        ]);

        $shareholder->update($data);

        return response()->json([
            'message' => 'Shareholder updated',
            'shareholder' => $shareholder
        ]);
    }

    // DELETE
    public function destroy($id)
    {
        $shareholder = Shareholder::findOrFail($id);
        $shareholder->delete();

        return response()->json([
            'message' => 'Shareholder deleted'
        ]);
    }
}
