<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

trait ApiResponser
{
    private function succesResponse($data, $code)
    {
        return response()->json($data, $code);
    }
    protected function errorResponse($message, $code)
    {
        return response()->json([
            'statusCode' => $code,
            'message' => $message,
        ], $code
        );
    }
    protected function showAll(Collection $collection, $code = 200)
    {
        return $this->succesResponse(['data' => $collection], $code);
    }
    protected function showOne(Model $instance, $code = 200)
    {
        return $this->succesResponse(['data' => $instance], $code);
    }
}
