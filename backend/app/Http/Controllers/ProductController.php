<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index() {
        return response()->json(Product::orderBy('order')->get());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:255',
            'price' => 'required|numeric',
            'tax' => 'required|numeric',
            'tagName' => 'nullable|string|max:255',
            'tagColor' => 'nullable|string|max:255',
        ]);

        // Get the highest order value and add 1 for the new product
        $maxOrder = Product::max('order') ?? -1;
        $validated['order'] = $maxOrder + 1;

        $product = Product::create($validated);
        return response()->json($product, 201);
    }

    public function show(Product $product) {
        return response()->json($product);
    }

    public function update(Request $request, Product $product) {
        $product->update($request->all());
        return response()->json($product);
    }

    public function destroy(Product $product) {
        $product->delete();
        return response()->json(null, 204);
    }

    public function updateOrder(Request $request)
    {
        try {
            $request->validate([
                'order' => 'required|array',
                'order.*' => 'required|integer|exists:products,id'
            ]);

            foreach ($request->order as $position => $id) {
                Product::where('id', $id)->update(['order' => $position]);
            }

            return response()->json(['message' => 'Order updated successfully']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}

