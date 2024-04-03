<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateAreasRequest;
use App\Http\Requests\UpdateAreasRequest;
use App\Http\Controllers\AppBaseController;
use App\Repositories\AreasRepository;
use Illuminate\Http\Request;
use Flash;

class AreasController extends AppBaseController
{
    /** @var AreasRepository $areasRepository*/
    private $areasRepository;

    public function __construct(AreasRepository $areasRepo)
    {
        $this->areasRepository = $areasRepo;
    }

    /**
     * Display a listing of the Areas.
     */
    public function index(Request $request)
    {
        $areas = $this->areasRepository->paginate(10);

        return view('areas.index')
            ->with('areas', $areas);
    }

    /**
     * Show the form for creating a new Areas.
     */
    public function create()
    {
        return view('areas.create');
    }

    /**
     * Store a newly created Areas in storage.
     */
    public function store(CreateAreasRequest $request)
    {
        $input = $request->all();

        $areas = $this->areasRepository->create($input);

        Flash::success('Areas saved successfully.');

        return redirect(route('areas.index'));
    }

    /**
     * Display the specified Areas.
     */
    public function show($id)
    {
        $areas = $this->areasRepository->find($id);

        if (empty($areas)) {
            Flash::error('Areas not found');

            return redirect(route('areas.index'));
        }

        return view('areas.show')->with('areas', $areas);
    }

    /**
     * Show the form for editing the specified Areas.
     */
    public function edit($id)
    {
        $areas = $this->areasRepository->find($id);

        if (empty($areas)) {
            Flash::error('Areas not found');

            return redirect(route('areas.index'));
        }

        return view('areas.edit')->with('areas', $areas);
    }

    /**
     * Update the specified Areas in storage.
     */
    public function update($id, UpdateAreasRequest $request)
    {
        $areas = $this->areasRepository->find($id);

        if (empty($areas)) {
            Flash::error('Areas not found');

            return redirect(route('areas.index'));
        }

        $areas = $this->areasRepository->update($request->all(), $id);

        Flash::success('Areas updated successfully.');

        return redirect(route('areas.index'));
    }

    /**
     * Remove the specified Areas from storage.
     *
     * @throws \Exception
     */
    public function destroy($id)
    {
        $areas = $this->areasRepository->find($id);

        if (empty($areas)) {
            Flash::error('Areas not found');

            return redirect(route('areas.index'));
        }

        $this->areasRepository->delete($id);

        Flash::success('Areas deleted successfully.');

        return redirect(route('areas.index'));
    }
}
