<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateProductAPIRequest;
use App\Http\Requests\API\UpdateProductAPIRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Repositories\ProductRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use Illuminate\Support\Facades\Auth;

/**
 * Class ProductAPIController
 */
class ProductAPIController extends AppBaseController
{
    private ProductRepository $productRepository;

    public function __construct(ProductRepository $productRepo)
    {
        $this->productRepository = $productRepo;
    }

    /**
     * Display a listing of the Products.
     * GET|HEAD /products
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $limit = $request->limit ? $request->limit : 20;
            $products = $this->productRepository->getByUser($this->getUser()->id, $limit);

            return  $this->sendApiResponse(array('data' => ProductResource::collection($products)), 'Products retrieved successfully');
        } catch (\Exception $e) {
            return $this->sendApiError(__('messages.something_went_wrong'), 500);
        }
    }

    /**
     * Store a newly created Product in storage.
     * POST /products
     */
    public function store(CreateProductAPIRequest $request): JsonResponse
    {
        try {
            $input = $request->all();
            $input['user_id'] = auth()->id();

            $product = $this->productRepository->create($input);

            if($request['images'] && $request['images']->isValid()){
                $product->addMultipleMediaFromRequest(['images'])
                    ->each(function ($product) {
                        $product->toMediaCollection('products_images');
                    });
            }

            return $this->sendApiResponse(array('data' => new ProductResource($product)), 'Product saved successfully');
        } catch (\Exception $e) {
            return $this->sendApiError(__('messages.something_went_wrong'), 500);
        }
    }

    /**
     * Display the specified Product.
     * GET|HEAD /products/{id}
     */
    public function show($id): JsonResponse
    {
        try {
            /** @var Product $product */
            $product = $this->productRepository->getById($id);

            if (empty($product)) {
                return $this->sendApiError('Product not found', 404);
            }

            return $this->sendApiResponse(array('data' => new ProductResource($product)), 'Product retrieved successfully');
        } catch (\Exception $e) {
            return $this->sendApiError(__('messages.something_went_wrong'), 500);
        }
    }

    /**
     * Update the specified Product in storage.
     * PUT/PATCH /products/{id}
     */
    public function update($id, UpdateProductAPIRequest $request): JsonResponse
    {
        try {
            $request->merge(['user_id' =>auth()->id()]);
            $input = $request->all();

            /** @var Product $product */
            $product = $this->productRepository->find($id);

            if (empty($product)) {
                return $this->sendApiError('Product not found', 404);
            }

            $product = $this->productRepository->update($input, $id);

            if($request['images'] && $request['images']->isValid()){
                $product->addMultipleMediaFromRequest(['images'])
                    ->each(function ($product) {
                        $product->toMediaCollection('products_images');
                    });
            }

            return $this->sendApiResponse(array('data' => new ProductResource($product)), 'Product updated successfully');
        } catch (\Exception $e) {
            return $this->sendApiError(__('messages.something_went_wrong'), 500);
        }
    }

    /**
     * Remove the specified Product from storage.
     * DELETE /products/{id}
     *
     * @throws \Exception
     */
    public function destroy($id): JsonResponse
    {
        try {
            /** @var Product $product */
            $product = $this->productRepository->find($id);

            if (empty($product)) {
                return $this->sendApiError('Product not found', 404);
            }
            $product->delete();

            return $this->sendApiResponse(array(), 'Product deleted successfully');
        } catch (\Exception $e) {
            return $this->sendApiError(__('messages.something_went_wrong'), 500);
        }
    }
}
