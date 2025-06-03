<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Feedback;
use Illuminate\Http\Request;

class FeedbackController extends Controller
{
    public function create()
    {
        return view('feedback.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,order_id',
            'feedback' => 'required|string|max:1000',
        ]);

        $order = Order::where('order_id', $request->order_id)->first();

        Feedback::create([
            'order_id' => $order->id,
            'feedback' => $request->feedback,
        ]);

        return back()->with('success', 'Feedback submitted successfully.');
    }

    public function index()
    {
        $feedbacks = Feedback::with('order.product')->latest()->get();
        return view('feedback.index', compact('feedbacks'));
    }
}
