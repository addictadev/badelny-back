<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\AppBaseController;
use App\Models\Category;
use App\Repositories\CategoryRepository;
use Illuminate\Http\Request;

class CategoryController extends AppBaseController
{
    /** @var CategoryRepository $categoryRepository*/
    private $categoryRepository;

    public function __construct(CategoryRepository $categoryRepo)
    {
        $this->categoryRepository = $categoryRepo;
    }

    /**
     * Display a listing of the Category.
     */
    public function index()
    {
        return view('dashboard.categories.index');
    }

    /**
     * Show the form for creating a new Category.
     */
    public function create()
    {
        $categories = Category::get();
        return view('dashboard.categories.add',compact('categories'));
    }

    /**
     * Store a newly created Category in storage.
     */
    public function store(Request $request)
    {
        $input = $request->all();

       $category = $this->categoryRepository->create($input);

        if($request['image'] && $request['image']->isValid()){
            $category->addMediaFromRequest('image')->toMediaCollection('images');
        }
        return redirect()->route('categories.index')->with('success',trans('dashboard.Added_Successfully'));
    }

    public function loadAjaxDatatable()
    {
        return $this->categoryRepository->loadAjax();
    }
    /**
     * Show the form for editing the specified Category.
     */
    public function edit($id)
    {
        $categories = Category::get();
        $category = $this->categoryRepository->find($id);

        return view('dashboard.categories.add',compact('category','categories'));
    }

    /**
     * Update the specified Category in storage.
     */
    public function update($id, Request $request)
    {
       $this->categoryRepository->find($id);

    $category =  $this->categoryRepository->update($request->all(), $id);

        if($request['image'] && $request['image']->isValid()){
            $category->clearMediaCollection('images');
            $category->addMediaFromRequest('image')->toMediaCollection('images');
        }

        return redirect()->route('categories.index')->with('success',trans('dashboard.Updated_Successfully'));
    }

    /**
     * Remove the specified Category from storage.
     *
     * @throws \Exception
     */
    public function destroy($id)
    {
        $this->categoryRepository->find($id);

        $this->categoryRepository->delete($id);

        return redirect()->route('categories.index')->with('success',trans('dashboard.Deleted_Successfully'));
    }
}
