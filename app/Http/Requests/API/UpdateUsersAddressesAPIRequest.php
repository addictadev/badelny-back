<?php

namespace App\Http\Requests\API;

use App\Models\UsersAddresses;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use InfyOm\Generator\Request\APIRequest;

class UpdateUsersAddressesAPIRequest extends APIRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = UsersAddresses::$rules;

        return $rules;
    }

    protected function failedValidation(Validator $validator)
    {
        $response = array('success' => 'false');
        $errorString = implode(", ",$validator->messages()->all());
        $response['error'] = $errorString;
        throw new HttpResponseException(response()->json($response, JsonResponse::HTTP_UNPROCESSABLE_ENTITY));
    }
}
