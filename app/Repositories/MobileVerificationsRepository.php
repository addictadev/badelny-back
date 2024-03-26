<?php

namespace App\Repositories;

use App\Models\MobileVerifications;
use App\Repositories\BaseRepository;
use Carbon\Carbon;

class MobileVerificationsRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'user_id',
        'calling_code',
        'phone',
        'code',
        'expired',
        'is_verification',
        'expired_at'
    ];

    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model(): string
    {
        return MobileVerifications::class;
    }

    public function findByMobileNumber($calling_code, $mobileNumber)
    {
        return $this->model()::Where('phone', '=', $mobileNumber)->where('expired_at', '>=', Carbon::now())->get();
    }

    public function validateByCode($code)
    {
        return $this->model()::Where('code', '=', $code)->where('expired', 0)->where('expired_at', '>=', Carbon::now())->first();
    }
}
