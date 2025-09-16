<?php

namespace App\Http\Controllers\Wholeseller;

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
                'customer_name' => 'John Doe',
                'customer_email' => 'john@example.com',
                'total' => 599.99,
                'status' => 'pending',
                'created_at' => now()->subHours(2),
            ],
            [
                'id' => 2,
                'customer_name' => 'Jane Smith',
                'customer_email' => 'jane@example.com',
                'total' => 199.99,
                'status' => 'processing',
                'created_at' => now()->subHours(5),
            ],
            [
                'id' => 3,
                'customer_name' => 'Bob Johnson',
                'customer_email' => 'bob@example.com',
                'total' => 299.99,
                'status' => 'completed',
                'created_at' => now()->subDays(1),
            ],
        ]);

        // Apply filters
        if ($request->search) {
            $orders = $orders->filter(function ($order) use ($request) {
                return stripos($order['customer_name'], $request->search) !== false ||
                       stripos($order['customer_email'], $request->search) !== false;
            });
        }

        if ($request->status) {
            $orders = $orders->filter(function ($order) use ($request) {
                return $order['status'] === $request->status;
            });
        }

        return view('wholeseller.orders', [
            'orders' => $orders,
            'filters' => $request->only(['search', 'status']),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('wholeseller.orders.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|integer',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        // In real app, this would save to database
        session()->flash('success', 'Order created successfully.');

        return redirect()->route('wholeseller.orders.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): View
    {
        // Mock order data
        $order = [
            'id' => $id,
            'customer_name' => 'John Doe',
            'customer_email' => 'john@example.com',
            'total' => 599.99,
            'status' => 'pending',
            'items' => [
                [
                    'product_name' => 'Premium Smartphone',
                    'quantity' => 1,
                    'price' => 599.99,
                ],
            ],
            'created_at' => now()->subHours(2),
        ];

        return view('wholeseller.orders.show', compact('order'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id): View
    {
        // Mock order data
        $order = [
            'id' => $id,
            'customer_name' => 'John Doe',
            'customer_email' => 'john@example.com',
            'total' => 599.99,
            'status' => 'pending',
            'items' => [
                [
                    'product_name' => 'Premium Smartphone',
                    'quantity' => 1,
                    'price' => 599.99,
                ],
            ],
        ];

        return view('wholeseller.orders.edit', compact('order'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,processing,shipped,completed,cancelled',
            'notes' => 'nullable|string',
        ]);

        // In real app, this would update the database
        session()->flash('success', 'Order updated successfully.');

        return redirect()->route('wholeseller.orders.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // In real app, this would delete from database
        session()->flash('success', 'Order deleted successfully.');

        return redirect()->route('wholeseller.orders.index');
    }
}
