<?php

const API_SUCCESS = 'success';
const API_ERROR = 'error';
const API_FAIL = 'fail';

function apiResponse(string $status, $data = null, $message = null, $error_code = null){
    return new App\Http\Responses\ApiResponse($status, $data, $message, $error_code);
}
function apiSuccess($data = null, $message = null){
    return new App\Http\Responses\ApiResponse(API_SUCCESS, $data, $message, null);
}

function apiError ($data = null, $message = null, $error_code = null) {
    return new \App\Http\Responses\ApiResponse(API_ERROR,$data,$message,$error_code);
}

function apiFail ($data = null, $message = null, $error_code = null) {
    return new \App\Http\Responses\ApiResponse(API_FAIL,$data,$message,$error_code);
}
