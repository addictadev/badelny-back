<?php

namespace App\Repositories;

use App\Models\Order;
use App\Models\Request;
use App\Models\RequestOffer;
use App\Repositories\BaseRepository;

class OrderRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'from',
        'request_id',
        'bayer_product_id',
        'points'
    ];

    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model(): string
    {
        if(request()->is_offer){
            return RequestOffer::class;
        }
        return Request::class;
    }
}
