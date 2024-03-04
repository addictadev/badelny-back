<?php

namespace App\Http\Controllers;

use InfyOm\Generator\Utils\ResponseUtil;
use Response;

/**
 * @OA\Server(url="/api")
 * @OA\Info(
 *   title="InfyOm Laravel Generator APIs",
 *   version="1.0.0"
 * )
 * This class should be parent class for other API controllers
 * Class AppBaseController
 */
class AppBaseController extends Controller
{
    public function sendResponse($result, $message)
    {
        return response()->json(ResponseUtil::makeResponse($message, $result));
    }

    public function sendError($error, $code = 404)
    {
        return response()->json(ResponseUtil::makeError($error), $code);
    }

    public function sendSuccess($message)
    {
        return response()->json([
            'success' => true,
            'message' => $message
        ], 200);
    }

    public function sendApiResponse($result, $message)
    {

        $result["message"] = $message;
        $result["success"] = true;
        return Response::json($result);
    }

    public function sendApiResponsePaginate($data, $message)
    {
        $result['data'] = $data->items();
        $result['total'] = $data->total();
        $result['next_page'] = $data->lastPage() > $data->currentPage() ? $data->currentPage() + 1 : -1;

        $result["message"] = $message;
        $result["success"] = true;
        return Response::json($result);
    }

    public function sendApiError($error, $code = 404, $erroCode = 0)
    {
        if($erroCode == 1){
            return Response::json(array("error" => $error, "status" => false, 'error_code' => 1), $code);
        }else{
            return Response::json(array("error" => $error, "status" => false), $code);
        }
    }

    public function getUser()
    {
        return auth('api')->user();
    }
}
