<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateAreasAPIRequest;
use App\Http\Requests\API\UpdateAreasAPIRequest;
use App\Models\Areas;
use App\Repositories\AreasRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;

/**
 * Class AreasAPIController
 */
class AreasAPIController extends AppBaseController
{
    private AreasRepository $areasRepository;

    public function __construct(AreasRepository $areasRepo)
    {
        $this->areasRepository = $areasRepo;
    }

    /**
     * Display a listing of the Areas.
     * GET|HEAD /areas
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $areas = $this->areasRepository->all(
                $request->except(['skip', 'limit']),
                $request->get('skip'),
                $request->get('limit')
            );
            return  $this->sendApiResponse(array('data' => $areas), 'Areas retrieved successfully');
        } catch (\Exception $e) {
            return $this->sendApiError(__('messages.something_went_wrong'), 500);
        }
    }

    /**
     * Store a newly created Areas in storage.
     * POST /areas
     */
    public function store(CreateAreasAPIRequest $request): JsonResponse
    {
        $input = $request->all();

        $areas = $this->areasRepository->create($input);

        return $this->sendResponse($areas->toArray(), 'Areas saved successfully');
    }

    /**
     * Display the specified Areas.
     * GET|HEAD /areas/{id}
     */
    public function show($id): JsonResponse
    {
        /** @var Areas $areas */
        $areas = $this->areasRepository->find($id);

        if (empty($areas)) {
            return $this->sendError('Areas not found');
        }

        return $this->sendResponse($areas->toArray(), 'Areas retrieved successfully');
    }

    /**
     * Update the specified Areas in storage.
     * PUT/PATCH /areas/{id}
     */
    public function update($id, UpdateAreasAPIRequest $request): JsonResponse
    {
        $input = $request->all();

        /** @var Areas $areas */
        $areas = $this->areasRepository->find($id);

        if (empty($areas)) {
            return $this->sendError('Areas not found');
        }

        $areas = $this->areasRepository->update($input, $id);

        return $this->sendResponse($areas->toArray(), 'Areas updated successfully');
    }

    /**
     * Remove the specified Areas from storage.
     * DELETE /areas/{id}
     *
     * @throws \Exception
     */
    public function destroy($id): JsonResponse
    {
        /** @var Areas $areas */
        $areas = $this->areasRepository->find($id);

        if (empty($areas)) {
            return $this->sendError('Areas not found');
        }

        $areas->delete();

        return $this->sendSuccess('Areas deleted successfully');
    }
}
