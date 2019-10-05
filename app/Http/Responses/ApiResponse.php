<?php

namespace App\Http\Responses;

use Illuminate\Http\JsonResponse;

class ApiResponse extends JsonResponse
{
    public function __construct(string $status, $data = null, $message = null, $error_code = null)
    {
        $response_status = [
            API_SUCCESS => 200,
            API_FAIL => 400,
            API_ERROR => 500,
        ];

        if (!isset($response_status[$status])) {
            throw new \Exception("Invalid Response Status");
        }

        $response = [
            'status' => $status,
        ];

        if ($data) {
            $response['data'] = $data;
        }

        if ($message) {
            $response['message'] = $message;
        }

        if ($error_code) {
            $response['code'] = $error_code;
        }

        parent::__construct($response, $response_status[$status], [],0);
    }

}
