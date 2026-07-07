<?php

namespace App\Support;

class ServiceResponse
{
    public static function success(
        string $message,
        array $data = []
    ): array {

        return array_merge([
            'status' => true,
            'message' => $message
        ], $data);
    }

    public static function error(
        string $message
    ): array {

        return [

            'status' => false,

            'message' => $message

        ];
    }
}
