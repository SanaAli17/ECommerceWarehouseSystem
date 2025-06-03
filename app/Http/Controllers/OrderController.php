<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Product;
use App\Models\Order;

class OrderController extends Controller
{
    const DELIVERY_CHARGE = 240;

    public function createTakeaway()
    {
        $products = Product::all();
        return view('orders.create_takeaway', compact('products'));
    }

    public function storeTakeaway(Request $request)
    {
        $request->validate([
            'customer_name' => 'required',
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $product = Product::findOrFail($request->product_id);
        $total = $product->price * $request->quantity;

        $order = Order::create([
            'order_id' => strtoupper(Str::random(8)),
            'customer_name' => $request->customer_name,
            'product_id' => $product->id,
            'quantity' => $request->quantity,
            'order_type' => 'takeaway',
            'total_amount' => $total,
        ]);

        return redirect()->route('orders.takeaway.confirmation', $order->id);
    }

    public function createDelivery()
    {
        $products = Product::all();
        return view('orders.create_delivery', compact('products'));
    }

    public function storeDelivery(Request $request)
    {
        $request->validate([
            'customer_name' => 'required',
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'address' => 'required|string',
            'distance_km' => 'required|numeric|min:0',
        ]);

        $product = Product::findOrFail($request->product_id);
        $total = ($product->price * $request->quantity) + self::DELIVERY_CHARGE;

        $order = Order::create([
            'order_id' => strtoupper(Str::random(8)),
            'customer_name' => $request->customer_name,
            'product_id' => $product->id,
            'quantity' => $request->quantity,
            'order_type' => 'delivery',
            'address' => $request->address,
            'distance_km' => $request->distance_km,
            'total_amount' => $total,
        ]);

        return redirect()->route('orders.delivery.confirmation', $order->id);
    }

    public function displayTakeaway()
    {
        $orders = Order::where('order_type', 'takeaway')->latest()->get();
        return view('orders.takeaway_list', compact('orders'));
    }

    public function displayDelivery()
    {
        $orders = Order::where('order_type', 'delivery')->latest()->get();
        return view('orders.delivery_list', compact('orders'));
    }

    public function collectTakeaway()
    {
        return view('orders.collect');
    }

    public function processTakeaway(Request $request)
    {
        $request->validate(['order_id' => 'required']);

        $order = Order::where('order_id', $request->order_id)->where('order_type', 'takeaway')->first();

        if (!$order) {
            return back()->with('error', 'Takeaway order not found.');
        }

        return back()->with('success', 'Order is ready. Please collect it.');
    }

    public function processDeliveries()
    {
        $deliveryOrders = Order::where('order_type', 'delivery')
            ->with('product') // eager load the product relation
            ->orderBy('distance_km')
            ->get();

        return view('orders.process_deliveries', compact('deliveryOrders'));
    }

    public function recentOrders()
    {
        $orders = Order::with('product')
            ->orderBy('id', 'desc')
            ->get();

        return view('orders.recent_orders', compact('orders'));
    }
}
