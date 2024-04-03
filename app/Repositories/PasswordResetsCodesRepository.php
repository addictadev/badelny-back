<?php

namespace App\Repositories;

use App\Models\PasswordResetsCodes;
use App\Models\User;
use App\Repositories\BaseRepository;
use Carbon\Carbon;

/**
 * Class PasswordResetsCodesRepository
 * @package App\Repositories
 * @version April 18, 2023, 11:59 pm UTC
*/

class PasswordResetsCodesRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'mobile',
        'code',
        'email',
        'expired',
        'expired_at'
    ];

    /**
     * Return searchable fields
     *
     * @return array
     */
    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    /**
     * Configure the Model
     **/
    public function model(): string
    {
        return PasswordResetsCodes::class;
    }

    public function findUserByFullMobileNumber($mobileNumber)
    {
        return User::Where('full_mobile_number', '=', $mobileNumber)->first();
    }

    public function findUserByMobileNumber($mobileNumber)
    {
        return User::Where('full_mobile_number', '=', $mobileNumber)->first();
    }

    public function findByMobileNumber($mobileNumber)
    {
        return $this->model()::Where('mobile', '=', $mobileNumber)
            ->where('expired', '=', '0')->where('expired_at', '>=', Carbon::now())->get();
    }

    public function findByCode($code)
    {
        return $this->model()::Where('code', '=', $code)
            ->where('expired', '=', '0')->where('expired_at', '>=', Carbon::now())->first();
    }
}
