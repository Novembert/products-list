<?php

namespace App\Modules\Product\Controllers;

use App\Modules\Product\Requests\StoreProductRequest;
use App\Modules\Product\Requests\UpdateProductRequest;
use App\Modules\Product\Resources\ProductCollection;
use App\Modules\Product\Resources\ProductResource;
use App\Modules\Product\DTOs\CreateProduct\CreateProductDTO;
use App\Modules\Product\DTOs\UpdateProduct\TagDTO as UpdateProductTagDTO;
use App\Modules\Product\DTOs\CreateProduct\TagDTO as CreateProductTagDTO;
use App\Modules\Product\DTOs\UpdateProduct\UpdateProductDTO;
use App\Modules\Product\Services\ProductService;
use App\Modules\Product\Services\TagService;
use App\Modules\Shared\Response\ErrorJsonResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use App\Modules\Product\Exceptions\ProductNotFoundException;
use App\Modules\Shared\Controllers\Controller;

class ProductController extends Controller
{
    /**
     * The controller constructor.
     */
    public function __construct(protected ProductService $productService, protected TagService $tagService) {}

    public function getAllProducts()
    {
        return new ProductCollection($this->productService->getAllProducts());
    }

    public function getProduct(int $productId)
    {
        try {
            return new ProductResource($this->productService->getProduct($productId));
        } catch (ProductNotFoundException $e) {
            return new ErrorJsonResponse(
                message: 'Product not found',
                statusCode: Response::HTTP_NOT_FOUND,
            );
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function createProduct(StoreProductRequest $request)
    {
        $productDTO = new CreateProductDTO(
            name: $request->name,
            description: $request->description,
            price: $request->price,
            vatRate: $request->vat_rate,
            tag: $request->tag ? (new CreateProductTagDTO(
                name: $request->tag['name'],
                color: $request->tag['color'],
            )) : null,
        );

        return new ProductResource(
            $this->productService->createProduct($productDTO)->loadMissing('tag')
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function updateProduct(UpdateProductRequest $request, int $productId)
    {
        try {
            $productDTO = new UpdateProductDTO(
                name: $request->name,
                description: $request->description,
                price: $request->price,
                vatRate: $request->vat_rate,
                tag: $request->tag ? (new UpdateProductTagDTO(
                    name: $request->tag['name'],
                    color: $request->tag['color'],
                )) : null,
            );
    
            return new ProductResource(
                $this->productService->updateProduct($productId, $productDTO)->loadMissing('tag')
            );
        } catch (ProductNotFoundException $e) {
            return new ErrorJsonResponse(
                message: 'Product not found',
                statusCode: Response::HTTP_NOT_FOUND,
            );
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function deleteProduct(int $productId)
    {
        try {
            $this->productService->deleteProduct($productId);
            return new JsonResponse(null, Response::HTTP_NO_CONTENT);
        } catch (ProductNotFoundException $e) {
            return new ErrorJsonResponse(
                message: 'Product not found',
                statusCode: Response::HTTP_NOT_FOUND,
            );
        }
    }
}
