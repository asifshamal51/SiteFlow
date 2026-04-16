<?php

namespace App\Http\Controllers;

use App\Models\Currency;
use Illuminate\Http\Request;

class CurrencyController extends Controller
{
    // GET ALL
    public function index()
    {
        return response()->json([
            'currencies' => Currency::all()
        ]);
    }

    // CREATE
    public function store(Request $request)
    {
        $data = $request->validate([
            'code' => 'required|unique:currencies,code',
            'name' => 'required|string',
            'symbol' => 'nullable|string',
            'is_base' => 'boolean'
        ]);

        $currency = Currency::create($data);

        return response()->json([
            'message' => 'Currency created',
            'currency' => $currency
        ]);
    }

    // SHOW
    public function show($id)
    {
        return response()->json([
            'currency' => Currency::findOrFail($id)
        ]);
    }

    // UPDATE
    public function update(Request $request, $id)
    {
        $currency = Currency::findOrFail($id);

        $data = $request->validate([
            'name' => 'sometimes|string',
            'symbol' => 'nullable|string',
            'is_base' => 'boolean'
        ]);

        $currency->update($data);

        return response()->json([
            'message' => 'Currency updated',
            'currency' => $currency
        ]);
    }

    // DELETE
    public function destroy($id)
    {
        Currency::findOrFail($id)->delete();

        return response()->json([
            'message' => 'Currency deleted'
        ]);
    }
}
