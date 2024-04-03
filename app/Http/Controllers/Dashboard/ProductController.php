<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Requests\CreateProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Http\Controllers\AppBaseController;
use App\Repositories\ProductRepository;
use Illuminate\Http\Request;
use Flash;

class ProductController extends AppBaseController
{
    /** @var ProductRepository $productRepository*/
    private $productRepository;

    public function __construct(ProductRepository $productRepo)
    {
        $this->productRepository = $productRepo;
    }

    /**
     * Display a listing of the Product.
     */
    public function index(Request $request)
    {
        return view('dashboard.products.index');
    }

    /**
     * Store a newly created Product in storage.
     */
    public function store(CreateProductRequest $request)
    {
        $input = $request->all();

        $product = $this->productRepository->create($input);

        if($request->hasFile('image')){
            $product->addMediaFromRequest('image')->toMediaCollection('images');
        }
        return redirect()->route('products.index')->with('success',trans('dashboard.Added_Successfully'));
    }

    public function loadAjaxDatatable()
    {
        return $this->productRepository->loadAjax();
    }

    /**
     * Display the specified Product.
     */

    /**
     * Show the form for editing the specified Product.
     */
    public function edit($id)
    {
        $product = $this->productRepository->find($id);


        return view('dashboard.products.add')->with('product', $product);
    }

    /**
     * Update the specified Product in storage.
     */
    public function update($id, UpdateProductRequest $request)
    {
         $this->productRepository->find($id);

        $product = $this->productRepository->update($request->all(), $id);

        if($request->hasFile('image')){
            $product->clearMediaCollection('images');
            $product->addMediaFromRequest('image')->toMediaCollection('images');
        }

        return redirect()->route('products.index')->with('success',trans('dashboard.Updated_Successfully'));

    }

    /**
     * Remove the specified Product from storage.
     *
     * @throws \Exception
     */
    public function destroy($id)
    {
        $this->productRepository->find($id);

        $this->productRepository->delete($id);

        return redirect()->route('products.index')->with('success',trans('dashboard.Deleted_Successfully'));

    }
}
