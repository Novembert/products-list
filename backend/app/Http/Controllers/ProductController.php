<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Http\Resources\ProductCollection;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Models\Tag;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return new ProductCollection(Product::with('tag')->get());        
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        return new ProductResource($product->loadMissing('tag'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductRequest $request)
    {
        $tag = $this->findOrCreateTag($request);
        $productData = $request->except('tag');
        if ($tag) {
            $productData['tag_id'] = $tag->id;
        }

        $product = Product::create($productData);

        return new ProductResource($product->loadMissing('tag'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductRequest $request, Product $product)
    {
        $tag = $this->findOrCreateTag($request);
        $productData = $request->except('tag');
        if ($tag) {
            $productData['tag_id'] = $tag->id;
        }

        $product->update($productData);
        $product = $product->fresh();

        return new ProductResource($product->loadMissing('tag'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        //
    }

    /**
     * Find or create a tag based on the request data.
     */
    private function findOrCreateTag(Request $request)
    {
        if ($request->filled('tag.name') && $request->filled('tag.color')) {
            $tag = Tag::where('name', $request->input('tag.name'))
                ->where('color', $request->input('tag.color'))
                ->first();

            if (!$tag) {
                $tag = Tag::create([
                    'name' => $request->input('tag.name'),
                    'color' => $request->input('tag.color'),
                ]);
            }

            return $tag;
        }

        return null;
    }
}
