<?php

namespace App\Http\Controllers;

use App\Models\Complaint;
use Illuminate\Http\Request;

class ComplaintController extends Controller
{
    public function create()
    {
        return view('complaints.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'text' => 'required|string|max:1000',
        ]);

        Complaint::create([
            'text' => $request->text,
        ]);

        return back()->with('success', 'Complaint submitted successfully.');
    }

    public function index()
    {
        $complaints = Complaint::latest()->get();
        return view('complaints.index', compact('complaints'));
    }
}
