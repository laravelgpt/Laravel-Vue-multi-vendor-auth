<?php

namespace App\Http\Controllers\Wholeseller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        // Mock data for now - in real app, this would fetch from database
        $products = collect([
            [
                'id' => 1,
                'name' => 'Premium Smartphone',
                'price' => 599.99,
                'stock' => 50,
                'status' => 'active',
                'created_at' => now()->subDays(5),
            ],
            [
                'id' => 2,
                'name' => 'Wireless Headphones',
                'price' => 199.99,
                'stock' => 25,
                'status' => 'active',
                'created_at' => now()->subDays(3),
            ],
            [
                'id' => 3,
                'name' => 'Smart Watch',
                'price' => 299.99,
                'stock' => 0,
                'status' => 'inactive',
                'created_at' => now()->subDays(1),
            ],
        ]);

        // Apply filters
        if ($request->search) {
            $products = $products->filter(function ($product) use ($request) {
                return stripos($product['name'], $request->search) !== false;
            });
        }

        if ($request->status) {
            $products = $products->filter(function ($product) use ($request) {
                return $product['status'] === $request->status;
            });
        }

        return view('wholeseller.products', [
            'products' => $products,
            'filters' => $request->only(['search', 'status']),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('wholeseller.products.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'description' => 'nullable|string',
        ]);

        // In real app, this would save to database
        session()->flash('success', 'Product created successfully.');

        return redirect()->route('wholeseller.products.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): View
    {
        // Mock product data
        $product = [
            'id' => $id,
            'name' => 'Premium Smartphone',
            'price' => 599.99,
            'stock' => 50,
            'status' => 'active',
            'description' => 'High-quality smartphone with advanced features.',
            'created_at' => now()->subDays(5),
        ];

        return view('wholeseller.products.show', compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id): View
    {
        // Mock product data
        $product = [
            'id' => $id,
            'name' => 'Premium Smartphone',
            'price' => 599.99,
            'stock' => 50,
            'status' => 'active',
            'description' => 'High-quality smartphone with advanced features.',
        ];

        return view('wholeseller.products.edit', compact('product'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'description' => 'nullable|string',
        ]);

        // In real app, this would update the database
        session()->flash('success', 'Product updated successfully.');

        return redirect()->route('wholeseller.products.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // In real app, this would delete from database
        session()->flash('success', 'Product deleted successfully.');

        return redirect()->route('wholeseller.products.index');
    }
}
