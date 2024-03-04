<?php

namespace App\Repositories;

use App\Models\Category;
use Yajra\DataTables\DataTables;

class CategoryRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'name_en',
        'name_ar',
        'has_parent',
        'parent_id'
    ];

    protected $category;

    public function __construct()
    {
        $this->category = new Category();
    }

    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model(): string
    {
        return Category::class;
    }

     public function create(array $input)
    {
        $model = $this->category->create($input);
        if($input['image'] && $input['image']->isValid()){
            $model->addMediaFromRequest('image')->toMediaCollection('images');
        }
         return $model;
    }

    public function find(int $id, array $columns = ['*'])
    {
        return $this->category->find($id, $columns);
    }

    public function update(array $input, int $id)
    {
        $model = $this->category->findOrFail($id);
        $model->fill($input);
        if($input['image'] && $input['image']->isValid()){
            $model->clearMediaCollection('images');
            $model->addMediaFromRequest('image')->toMediaCollection('images');
        }
        $model->save();

        return $model;
    }

    public function loadAjax()
    {
        $categories = $this->category->get();
        return Datatables::of($categories)
            ->rawColumns(['action', 'select', 'status'])
            ->editColumn('#', function ($model) {
            })
            ->editColumn('name', function ($model) {
                return app()->getLocale() == 'en' ? $model->name_en :  $model->name_ar;
            })
            ->editColumn('image', function ($model) {
                return '<img src="'.$model->getFirstMediaUrl('images', 'thumb').'" / width="120px">';
            })
            ->editColumn('parent', function ($model) {
                  if(isset($model->category)){
                return app()->getLocale() == 'en' ? $model->category->name_en :  $model->category->name_ar;
                  }
            })

            ->addColumn('action', function($model){

                $btn = '<a href="/categories/'.$model->id.'/edit" class="edit btn btn-primary btn-lg">'.trans('dashboard.edit').'</a>';
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

                            <form action="'. route('categories.destroy', $model->id) .'" method="post">
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
    public function delete(int $id)
    {

        $model = $this->category->findOrFail($id);

        return $model->delete();
    }
}
