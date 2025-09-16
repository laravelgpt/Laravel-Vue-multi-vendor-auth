<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        // Mock data for now - in real app, this would fetch from database
        $orders = collect([
            [
                'id' => 1,
                'vendor_name' => 'TechStore Pro',
                'total' => 599.99,
                'status' => 'processing',
                'items_count' => 1,
                'created_at' => now()->subDays(2),
            ],
            [
                'id' => 2,
                'vendor_name' => 'Audio Solutions',
                'total' => 199.99,
                'status' => 'shipped',
                'items_count' => 1,
                'created_at' => now()->subDays(5),
            ],
            [
                'id' => 3,
                'vendor_name' => 'Wearable Tech',
                'total' => 299.99,
                'status' => 'completed',
                'items_count' => 1,
                'created_at' => now()->subDays(10),
            ],
        ]);

        // Apply filters
        if ($request->search) {
            $orders = $orders->filter(function ($order) use ($request) {
                return stripos($order['vendor_name'], $request->search) !== false;
            });
        }

        if ($request->status) {
            $orders = $orders->filter(function ($order) use ($request) {
                return $order['status'] === $request->status;
            });
        }

        return view('customer.orders', [
            'orders' => $orders,
            'filters' => $request->only(['search', 'status']),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('customer.orders.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'vendor_id' => 'required|integer|exists:vendors,id',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|integer',
            'items.*.quantity' => 'required|integer|min:1',
            'shipping_address' => 'required|string',
            'payment_method' => 'required|string',
        ]);

        // In real app, this would save to database
        session()->flash('success', 'Order placed successfully.');

        return redirect()->route('customer.orders.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): View
    {
        // Mock order data
        $order = [
            'id' => $id,
            'vendor_name' => 'TechStore Pro',
            'vendor_email' => 'support@techstore.com',
            'total' => 599.99,
            'status' => 'processing',
            'shipping_address' => '123 Main St, City, State 12345',
            'payment_method' => 'Credit Card ending in 1234',
            'items' => [
                [
                    'product_name' => 'Premium Smartphone',
                    'quantity' => 1,
                    'price' => 599.99,
                    'image' => '/images/placeholder-product.jpg',
                ],
            ],
            'created_at' => now()->subDays(2),
            'updated_at' => now()->subHours(5),
            'tracking_number' => 'TRK123456789',
            'estimated_delivery' => now()->addDays(3),
        ];

        return view('customer.orders.show', compact('order'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id): View
    {
        // Mock order data
        $order = [
            'id' => $id,
            'vendor_name' => 'TechStore Pro',
            'total' => 599.99,
            'status' => 'processing',
            'shipping_address' => '123 Main St, City, State 12345',
            'items' => [
                [
                    'product_name' => 'Premium Smartphone',
                    'quantity' => 1,
                    'price' => 599.99,
                ],
            ],
        ];

        return view('customer.orders.edit', compact('order'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'shipping_address' => 'required|string',
        ]);

        // In real app, this would update the database
        session()->flash('success', 'Order updated successfully.');

        return redirect()->route('customer.orders.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // In real app, this would cancel the order
        session()->flash('success', 'Order cancelled successfully.');

        return redirect()->route('customer.orders.index');
    }
}
