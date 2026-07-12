<?php
namespace App\Traits;
use Illuminate\Http\JsonResponse;

trait ApiResponse
{
    protected function success($data = null, ?string $message = null, $code = 200): JsonResponse
    {
        $response = [
            'success' => true,
            'data' => $data
        ];
        if($data === null){
            unset($response['data']);
        }
        if($message){
            $response['message'] = $message;
        }
        return response()->json($response, $code, [], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }

    protected function error($message = 'Error', $code = 400, $errors = null): JsonResponse
    {
        $response = [
            'success' => false,
            'message' => $message
        ];
        $code = intval($code);
        if(! $code){
            $code = 500;
        }
        if($code > 599){
            $code = 500;
        }
        if($code < 200){
            $code = 500;
        }

        if ($errors) {
            $response['errors'] = $errors;
        }

        return response()->json($response, $code, [], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }
}
