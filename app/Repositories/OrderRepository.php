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
        if (request()->has('offer_id')){
            return Order::class;
        }
        return Request::class;
    }

    public function getRequests($user,$limit)
    {
        return Request::where('status',0)->where('to',$user)->paginate($limit);
    }

    public function getRequestById($id)
    {
        return Request::find($id);
    }

    public function getOrders($user,$limit,$status)
    {
        if (!is_null($status)){
            return Order::where('from',$user)->where('status',$status)->paginate($limit);
        }
        return Order::where('from',$user)->paginate($limit);
    }



}
