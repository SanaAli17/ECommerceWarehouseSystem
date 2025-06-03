<?php

use App\Http\Controllers\ComplaintController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;
use Illuminate\Support\Facades\Route;
use App\Models\Order;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('products', ProductController::class);

    Route::get('/orders/takeaway', [OrderController::class, 'createTakeaway'])->name('orders.takeaway');
    Route::post('/orders/takeaway', [OrderController::class, 'storeTakeaway'])->name('orders.storeTakeaway');
    Route::get('/orders/takeaway/confirmation/{id}', function ($id) {
        $order = Order::findOrFail($id);
        return view('orders.takeaway_confirmation', compact('order'));
    })->name('orders.takeaway.confirmation');

    Route::get('/orders/delivery', [OrderController::class, 'createDelivery'])->name('orders.delivery');
    Route::post('/orders/delivery', [OrderController::class, 'storeDelivery'])->name('orders.storeDelivery');
    Route::get('/orders/delivery/confirmation/{id}', function ($id) {
        $order = Order::findOrFail($id);
        return view('orders.delivery_confirmation', compact('order'));
    })->name('orders.delivery.confirmation');

    Route::get('/orders/takeaway/list', [OrderController::class, 'displayTakeaway'])->name('orders.displayTakeaway');
    Route::get('/orders/delivery/list', [OrderController::class, 'displayDelivery'])->name('orders.displayDelivery');

    Route::get('/orders/takeaway/collect', [OrderController::class, 'collectTakeaway'])->name('orders.takeaway.collect');
    Route::post('/orders/takeaway/collect', [OrderController::class, 'processTakeaway'])->name('orders.takeaway.collect.process');

    Route::get('/orders/deliveries/process', [OrderController::class, 'processDeliveries'])->name('orders.deliveries.process');

    Route::get('/orders/recent', [OrderController::class, 'recentOrders'])->name('orders.recent');

    Route::get('/feedback', [FeedbackController::class, 'create'])->name('feedback.create');
    Route::post('/feedback', [FeedbackController::class, 'store'])->name('feedback.store');
    Route::get('/feedbacks', [FeedbackController::class, 'index'])->name('feedback.index');

    Route::get('/complaints', [ComplaintController::class, 'create'])->name('complaints.create');
    Route::post('/complaints', [ComplaintController::class, 'store'])->name('complaints.store');
    Route::get('/all-complaints', [ComplaintController::class, 'index'])->name('complaints.index');
});

require __DIR__.'/auth.php';
