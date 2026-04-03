<?php

namespace App\Helpers;

trait ResponseHelper
{
    public function apiResponseHandler($code, $status, $message, $data = []) {
        $dataFormatter = [
            'code'      => $code,
            'status'    => $status,
            'message'   => $message,
            'data'      => $data

        ];
        return response()->json($dataFormatter, $code);
    }
}
