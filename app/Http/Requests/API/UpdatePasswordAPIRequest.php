<?php

namespace App\Http\Requests\API;

use App\Models\User;
use InfyOm\Generator\Request\APIRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdatePasswordAPIRequest extends APIRequest
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
        return [
            'current_password' => 'required|min:8',
            'password' => 'required|confirmed|min:8',
        ];
    }

	protected function failedValidation(Validator $validator)
    {
		$response = array('status' => 'false');
        $errorString = implode(", ",$validator->messages()->all());
        $response['error'] = $errorString;
        throw new HttpResponseException(response()->json($response, 500));
    }
}
