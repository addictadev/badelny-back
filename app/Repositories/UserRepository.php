<?php

namespace App\Repositories;

use App\Models\User;
use App\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\DataTables;

class UserRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'name',
        'email',
        'calling_code',
        'phone',
        'full_mobile_number',
        'gender',
        'password',
        'date_of_birth',
    ];

    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model(): string
    {
        return User::class;
    }

    public function loadAjax()
    {
        $users = $this->user->get();
        return Datatables::of($users)
            ->rawColumns(['action', 'select', 'status'])
            ->editColumn('#', function ($model) {
            })
            ->editColumn('name', function ($model) {
                return app()->getLocale() == 'en' ? $model->name_en :  $model->name_ar;
            })
            ->editColumn('email', function ($model) {

                return $model->email;
            })
            ->editColumn('phone', function ($model) {

                return $model->phone;
            })

            ->editColumn('change_password', function ($model) {
                return   $btn = '<a href="/users/change-password/'.$model->id.'" class="edit btn btn-secondary btn-lg"><i class="fa fa-edit"></i>'.trans('dashboard.change_password').'</a>';
            })
            ->addColumn('action', function($model){

                $btn = '<a href="/users/'.$model->id.'/edit" class="edit btn btn-primary btn-lg">'.trans('dashboard.edit').'</a>';
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

                        <form action="'. route('users.destroy', $model->id) .'" method="post">
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
