<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateCategoryAPIRequest;
use App\Http\Requests\API\UpdateCategoryAPIRequest;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use App\Repositories\CategoryRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;

/**
 * Class CategoryAPIController
 */
class CategoryAPIController extends AppBaseController
{
    private CategoryRepository $categoryRepository;

    public function __construct(CategoryRepository $categoryRepo)
    {
        $this->categoryRepository = $categoryRepo;
    }

    /**
     * Display a listing of the Categories.
     * GET|HEAD /categories
     */
    public function index(): JsonResponse
    {
        try {
            $categories = $this->categoryRepository->all();

            return $this->sendApiResponse(array('data' => CategoryResource::collection($categories)), 'Categories retrieved successfully');
        } catch (\Exception $e) {
            return $this->sendApiError(__('messages.something_went_wrong'), 500);
        }
    }



    /**
     * Store a newly created Category in storage.
     * POST /categories
     */
    public function store(CreateCategoryAPIRequest $request): JsonResponse
    {
        try {
            $input = $request->all();

            $category = $this->categoryRepository->create($input);

            return $this->sendApiResponse(array('data' => $category->toArray()), 'Category saved successfully');
        } catch (\Exception $e) {
            return $this->sendApiError(__('messages.something_went_wrong'), 500);
        }
    }

    /**
     * Display the specified Category.
     * GET|HEAD /categories/{id}
     */
    public function show($id): JsonResponse
    {
        try {
            /** @var Category $category */
            $category = $this->categoryRepository->find($id);

            if (empty($category)) {
                return $this->sendApiError('Category not found', 404);
            }

            return $this->sendApiResponse(array('data' => $category->toArray()), 'Category retrieved successfully');
        } catch (\Exception $e) {
            return $this->sendApiError(__('messages.something_went_wrong'), 500);
        }
    }

    /**
     * Update the specified Category in storage.
     * PUT/PATCH /categories/{id}
     */
    public function update($id, UpdateCategoryAPIRequest $request): JsonResponse
    {
        try {
            $input = $request->all();

            /** @var Category $category */
            $category = $this->categoryRepository->find($id);

            if (empty($category)) {
                return $this->sendApiError('Category not found', 404);
            }

            $category = $this->categoryRepository->update($input, $id);

            return $this->sendApiResponse(array('data' => $category->toArray()), 'Category updated successfully');
        } catch (\Exception $e) {
            return $this->sendApiError(__('messages.something_went_wrong'), 500);
        }
    }

    /**
     * Remove the specified Category from storage.
     * DELETE /categories/{id}
     *
     * @throws \Exception
     */
    public function destroy($id): JsonResponse
    {
        try {
            /** @var Category $category */
            $category = $this->categoryRepository->find($id);

            if (empty($category)) {
                return $this->sendApiError('Category not found', 404);
            }

            $category->delete();

            return $this->sendApiResponse(array(), 'Category deleted successfully');
        } catch (\Exception $e) {
            return $this->sendApiError(__('messages.something_went_wrong'), 500);
        }
    }
}
