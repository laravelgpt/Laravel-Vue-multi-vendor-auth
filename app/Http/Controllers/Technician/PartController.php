<?php

namespace App\Http\Controllers\Technician;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PartController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        // Mock data for now - in real app, this would fetch from database
        $parts = collect([
            [
                'id' => 1,
                'name' => 'iPhone 13 Pro Screen',
                'part_number' => 'IP13P-SCR-001',
                'category' => 'Screen',
                'stock' => 15,
                'min_stock' => 5,
                'cost' => 199.99,
                'status' => 'available',
                'last_ordered' => now()->subDays(7),
            ],
            [
                'id' => 2,
                'name' => 'Samsung Galaxy S21 Battery',
                'part_number' => 'SGS21-BAT-001',
                'category' => 'Battery',
                'stock' => 8,
                'min_stock' => 3,
                'cost' => 89.99,
                'status' => 'available',
                'last_ordered' => now()->subDays(14),
            ],
            [
                'id' => 3,
                'name' => 'iPad Air Charging Port',
                'part_number' => 'IPA-CHG-001',
                'category' => 'Charging',
                'stock' => 2,
                'min_stock' => 5,
                'cost' => 49.99,
                'status' => 'low_stock',
                'last_ordered' => now()->subDays(30),
            ],
        ]);

        // Apply filters
        if ($request->search) {
            $parts = $parts->filter(function ($part) use ($request) {
                return stripos($part['name'], $request->search) !== false ||
                       stripos($part['part_number'], $request->search) !== false;
            });
        }

        if ($request->category) {
            $parts = $parts->filter(function ($part) use ($request) {
                return $part['category'] === $request->category;
            });
        }

        if ($request->status) {
            $parts = $parts->filter(function ($part) use ($request) {
                return $part['status'] === $request->status;
            });
        }

        return view('technician.parts', [
            'parts' => $parts,
            'filters' => $request->only(['search', 'category', 'status']),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('technician.parts.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'part_number' => 'required|string|max:100|unique:parts,part_number',
            'category' => 'required|string|max:100',
            'stock' => 'required|integer|min:0',
            'min_stock' => 'required|integer|min:0',
            'cost' => 'required|numeric|min:0',
        ]);

        // In real app, this would save to database
        session()->flash('success', 'Part added successfully.');

        return redirect()->route('technician.parts.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): View
    {
        // Mock part data
        $part = [
            'id' => $id,
            'name' => 'iPhone 13 Pro Screen',
            'part_number' => 'IP13P-SCR-001',
            'category' => 'Screen',
            'stock' => 15,
            'min_stock' => 5,
            'cost' => 199.99,
            'status' => 'available',
            'description' => 'Original iPhone 13 Pro screen assembly with LCD and digitizer.',
            'supplier' => 'Apple Parts Direct',
            'last_ordered' => now()->subDays(7),
            'usage_history' => [
                [
                    'date' => now()->subDays(2),
                    'quantity_used' => 2,
                    'repair_id' => 1,
                ],
                [
                    'date' => now()->subDays(5),
                    'quantity_used' => 1,
                    'repair_id' => 3,
                ],
            ],
        ];

        return view('technician.parts.show', compact('part'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id): View
    {
        // Mock part data
        $part = [
            'id' => $id,
            'name' => 'iPhone 13 Pro Screen',
            'part_number' => 'IP13P-SCR-001',
            'category' => 'Screen',
            'stock' => 15,
            'min_stock' => 5,
            'cost' => 199.99,
            'description' => 'Original iPhone 13 Pro screen assembly with LCD and digitizer.',
        ];

        return view('technician.parts.edit', compact('part'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'part_number' => 'required|string|max:100',
            'category' => 'required|string|max:100',
            'stock' => 'required|integer|min:0',
            'min_stock' => 'required|integer|min:0',
            'cost' => 'required|numeric|min:0',
            'description' => 'nullable|string',
        ]);

        // In real app, this would update the database
        session()->flash('success', 'Part updated successfully.');

        return redirect()->route('technician.parts.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // In real app, this would delete from database
        session()->flash('success', 'Part deleted successfully.');

        return redirect()->route('technician.parts.index');
    }
}
