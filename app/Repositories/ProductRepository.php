<?php

namespace App\Repositories;

use App\Models\Favourite;
use App\Models\Product;
use App\Repositories\BaseRepository;
use Yajra\DataTables\DataTables;

class ProductRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'name',
        'category_id',
        'sub_category_id',
        'wight',
        'condition',
        'color',
        'exchange_options',
        'price',
        'points',
        'description'
    ];

    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model(): string
    {
        return Product::class;
    }

    public function getHomeProducts($limit,$category,$search)
    {
        return $this->model()::where('is_approve', 1)->Category($category)->Search($search)->paginate($limit);
    }

    public function getByUser($user_id, $limit,$category,$search)
    {
        return $this->model()::where('user_id', $user_id)->Category($category)->Search($search)->paginate($limit);
    }

    public function getById($id)
    {
        return $this->model()::where('id', $id)->first();
    }

    public function productFavourite($id,$user_id)
    {
        // check the product exit in favourite or not
        $favourite = Favourite::where('user_id',$user_id)->where('product_id',$id)->first();

        if ($favourite){
            $favourite->delete();
        }else{
            // add new fav product
       $favourite = new Favourite();

       $favourite->product_id = $id;
       $favourite->user_id = $user_id;

       $favourite->save();
        }
       return $favourite;
    }

    public function getFavouriteProducts($user)
    {
        // get the favourite Products
        $favourite = Favourite::where('user_id',$user)->pluck('product_id');

        return Product::whereIn('id',$favourite)->get();

    }

    public function loadAjax()
    {
        $products = Product::get();
        return Datatables::of($products)
            ->rawColumns(['action', 'select', 'status'])
            ->editColumn('#', function ($model) {
            })
            ->editColumn('name', function ($model) {
                return  $model->name;
            })
            ->editColumn('image', function ($model) {
                return '<img src="'.$model->getFirstMediaUrl('images', 'thumb').'" / width="120px">';
            })
            ->editColumn('price', function ($model) {
                return $model->price;
            })
            ->editColumn('approve', function ($model) {

                if ($model->is_approve == 1){
                   return '<i class="fa fa-check-circle fa-2x" aria-hidden="true" style="color: green"></i>';
                }else{
                   return '<i class="fa fa-times fa-2x" aria-hidden="true" style="color: red"></i>';
                }

            })
            ->editColumn('status', function ($model) {

                if ($model->status == 1){
                    return '<i class="fa fa-check-circle fa-2x" aria-hidden="true" style="color: green"></i>';
                }else{
                    return '<i class="fa fa-times fa-2x" aria-hidden="true" style="color: red"></i>';
                }

            })
            ->editColumn('category', function ($model) {
                if(isset($model->category)){
                    return app()->getLocale() == 'en' ? $model->category->name_en :  $model->category->name_ar;
                }
            })

            ->addColumn('action', function($model){

                $btn = '<a href="/products/'.$model->id.'/edit" class="edit btn btn-primary btn-lg">'.trans('dashboard.edit').'</a>';
                //
                $btn = $btn.'
                      <button class="error btn btn-danger btn-lg" data-toggle="modal" data-target="#deleteApplyLeaveModal'.$model->id.'">'.trans('dashboard.delete').'</button>'.

                    '<div class="modal bg-dark fade" data-backdrop="false" id="deleteApplyLeaveModal'. $model->id .'">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content">
                                                        <div class="modal-header">

                                                            <button type="button" class="close" data-dismiss="modal">
                                                                &times;
                                                            </button>
                                                        </div>

                                                        <div class="modal-body">
                                                            <div class="text-center">
                                                                <h4>'. trans('dashboard.are_you_sure_to_delete'). '</h4>
                                                            </div>

                            <form action="'. route('products.destroy', $model->id) .'" method="post">
                                                                         <input type="hidden" name="_method" value="delete" />
                                                                             <input type="hidden" name="_token" value="'. csrf_token() .'">
                                                                            <input type="hidden" name="id" value="'. $model->id .'">
                                                                            <button class="edit btn btn-primary btn-lg" type="submit">'.trans('dashboard.delete').'</button>
                                                                         </form>
                                                                         </div>
                                                                         </div>
                                                                        ';

                return $btn;

            })
            ->addIndexColumn()
            ->escapeColumns([])
            ->make(true);
    }
}
