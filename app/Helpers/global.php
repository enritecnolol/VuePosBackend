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

function getMonthName ($month, $short = false) {
    $map = [
        1 => 'Enero',
        2 => 'Febrero',
        3 => 'Marzo',
        4 => 'Abril',
        5 => 'Mayo',
        6 => 'Junio',
        7 => 'Julio',
        8 => 'Agosto',
        9 => 'Septiembre',
        10 => 'Octubre',
        11 => 'Noviembre',
        12 => 'Diciembre',
    ];
    $shortmap = [
        1 => 'Ene.',
        2 => 'Feb.',
        3 => 'Mar.',
        4 => 'Abr.',
        5 => 'May.',
        6 => 'Jun.',
        7 => 'Jul.',
        8 => 'Ago.',
        9 => 'Sep.',
        10 => 'Oct.',
        11 => 'Nov.',
        12 => 'Dic.',
    ];
    return $short ? $shortmap[$month] : $map[$month];
}
