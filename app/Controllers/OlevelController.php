<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;

class OlevelController extends Controller
{
    public function index(\Illuminate\Http\Request $request)
    {
        // Return a simple view
        return view('olevel.index');
    }

    // Store O-level result
    public function store(Request $request)
    {
        // Basic validation
        $validated = $request->validate([
            'subject' => 'required',
            'grade' => 'required'
        ]);

        // Return success response
        return response()->json([
            'message' => 'O-level result saved successfully'
        ], 200);
    }
}
