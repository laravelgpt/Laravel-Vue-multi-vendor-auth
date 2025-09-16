<?php

namespace App\Http\Controllers\Technician;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RepairController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        // Mock data for now - in real app, this would fetch from database
        $repairs = collect([
            [
                'id' => 1,
                'device' => 'iPhone 13 Pro',
                'issue' => 'Screen replacement',
                'customer_name' => 'John Doe',
                'customer_phone' => '+1234567890',
                'status' => 'in_progress',
                'priority' => 'high',
                'estimated_cost' => 299.99,
                'created_at' => now()->subHours(3),
            ],
            [
                'id' => 2,
                'device' => 'Samsung Galaxy S21',
                'issue' => 'Battery replacement',
                'customer_name' => 'Jane Smith',
                'customer_phone' => '+1234567891',
                'status' => 'pending',
                'priority' => 'medium',
                'estimated_cost' => 149.99,
                'created_at' => now()->subHours(6),
            ],
            [
                'id' => 3,
                'device' => 'iPad Air',
                'issue' => 'Charging port repair',
                'customer_name' => 'Bob Johnson',
                'customer_phone' => '+1234567892',
                'status' => 'completed',
                'priority' => 'low',
                'estimated_cost' => 199.99,
                'created_at' => now()->subDays(1),
            ],
        ]);

        // Apply filters
        if ($request->search) {
            $repairs = $repairs->filter(function ($repair) use ($request) {
                return stripos($repair['device'], $request->search) !== false ||
                       stripos($repair['customer_name'], $request->search) !== false;
            });
        }

        if ($request->status) {
            $repairs = $repairs->filter(function ($repair) use ($request) {
                return $repair['status'] === $request->status;
            });
        }

        if ($request->priority) {
            $repairs = $repairs->filter(function ($repair) use ($request) {
                return $repair['priority'] === $request->priority;
            });
        }

        return view('technician.repairs', [
            'repairs' => $repairs,
            'filters' => $request->only(['search', 'status', 'priority']),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('technician.repairs.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'device' => 'required|string|max:255',
            'issue' => 'required|string',
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'required|string|max:20',
            'priority' => 'required|in:low,medium,high',
            'estimated_cost' => 'required|numeric|min:0',
        ]);

        // In real app, this would save to database
        session()->flash('success', 'Repair order created successfully.');

        return redirect()->route('technician.repairs.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): View
    {
        // Mock repair data
        $repair = [
            'id' => $id,
            'device' => 'iPhone 13 Pro',
            'issue' => 'Screen replacement',
            'customer_name' => 'John Doe',
            'customer_phone' => '+1234567890',
            'customer_email' => 'john@example.com',
            'status' => 'in_progress',
            'priority' => 'high',
            'estimated_cost' => 299.99,
            'actual_cost' => null,
            'notes' => 'Screen is completely cracked, needs full replacement.',
            'created_at' => now()->subHours(3),
            'updated_at' => now()->subMinutes(30),
        ];

        return view('technician.repairs.show', compact('repair'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id): View
    {
        // Mock repair data
        $repair = [
            'id' => $id,
            'device' => 'iPhone 13 Pro',
            'issue' => 'Screen replacement',
            'customer_name' => 'John Doe',
            'customer_phone' => '+1234567890',
            'status' => 'in_progress',
            'priority' => 'high',
            'estimated_cost' => 299.99,
            'notes' => 'Screen is completely cracked, needs full replacement.',
        ];

        return view('technician.repairs.edit', compact('repair'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,in_progress,waiting_parts,completed,cancelled',
            'actual_cost' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        // In real app, this would update the database
        session()->flash('success', 'Repair order updated successfully.');

        return redirect()->route('technician.repairs.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // In real app, this would delete from database
        session()->flash('success', 'Repair order deleted successfully.');

        return redirect()->route('technician.repairs.index');
    }
}
