<?php

namespace App\Repositories;

use App\Models\UsersAddresses;
use App\Repositories\BaseRepository;

class UsersAddressesRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'area_id',
        'address',
        'flat',
        'landmark',
        'phone',
        'user_id'
    ];

    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model(): string
    {
        return UsersAddresses::class;
    }

    public function getByID($user_id, $id)
    {
        return $this->model()::where('user_id', $user_id)->find($id);
    }

    public function getByUser($user_id, $limit)
    {
        return $this->model()::where('user_id', $user_id)->paginate($limit);
    }
}
