<?php

namespace App\Repositories;

use App\Models\Areas;
use App\Repositories\BaseRepository;

class AreasRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'name_en',
        'name_ar',
        'status'
    ];

    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model(): string
    {
        return Areas::class;
    }
}
