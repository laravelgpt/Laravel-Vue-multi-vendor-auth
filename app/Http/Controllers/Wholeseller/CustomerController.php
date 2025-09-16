<?php

namespace App\Http\Controllers\Wholeseller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        // Mock data for now - in real app, this would fetch from database
        $customers = collect([
            [
                'id' => 1,
                'name' => 'John Doe',
                'email' => 'john@example.com',
                'phone' => '+1234567890',
                'total_orders' => 5,
                'total_spent' => 1299.95,
                'last_order' => now()->subDays(2),
                'status' => 'active',
            ],
            [
                'id' => 2,
                'name' => 'Jane Smith',
                'email' => 'jane@example.com',
                'phone' => '+1234567891',
                'total_orders' => 3,
                'total_spent' => 599.97,
                'last_order' => now()->subDays(5),
                'status' => 'active',
            ],
            [
                'id' => 3,
                'name' => 'Bob Johnson',
                'email' => 'bob@example.com',
                'phone' => '+1234567892',
                'total_orders' => 1,
                'total_spent' => 299.99,
                'last_order' => now()->subDays(10),
                'status' => 'inactive',
            ],
        ]);

        // Apply filters
        if ($request->search) {
            $customers = $customers->filter(function ($customer) use ($request) {
                return stripos($customer['name'], $request->search) !== false ||
                       stripos($customer['email'], $request->search) !== false;
            });
        }

        if ($request->status) {
            $customers = $customers->filter(function ($customer) use ($request) {
                return $customer['status'] === $request->status;
            });
        }

        return view('wholeseller.customers', [
            'customers' => $customers,
            'filters' => $request->only(['search', 'status']),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('wholeseller.customers.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'nullable|string|max:20',
        ]);

        // In real app, this would save to database
        session()->flash('success', 'Customer created successfully.');

        return redirect()->route('wholeseller.customers.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): View
    {
        // Mock customer data
        $customer = [
            'id' => $id,
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'phone' => '+1234567890',
            'total_orders' => 5,
            'total_spent' => 1299.95,
            'last_order' => now()->subDays(2),
            'status' => 'active',
            'orders' => [
                [
                    'id' => 1,
                    'total' => 599.99,
                    'status' => 'completed',
                    'created_at' => now()->subDays(2),
                ],
                [
                    'id' => 2,
                    'total' => 299.99,
                    'status' => 'completed',
                    'created_at' => now()->subDays(5),
                ],
            ],
        ];

        return view('wholeseller.customers.show', compact('customer'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id): View
    {
        // Mock customer data
        $customer = [
            'id' => $id,
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'phone' => '+1234567890',
            'status' => 'active',
        ];

        return view('wholeseller.customers.edit', compact('customer'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'nullable|string|max:20',
            'status' => 'required|in:active,inactive',
        ]);

        // In real app, this would update the database
        session()->flash('success', 'Customer updated successfully.');

        return redirect()->route('wholeseller.customers.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // In real app, this would delete from database
        session()->flash('success', 'Customer deleted successfully.');

        return redirect()->route('wholeseller.customers.index');
    }
}
