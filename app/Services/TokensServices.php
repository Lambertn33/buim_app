<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class TokensServices
{

    public function generateToken($data)
    {
        $response =  Http::post('' . env("TOKENS_GENERATOR_URL") . '', [
            "command" => $data['command'],
            "data" => $data['data'],
            "count" => $data['count'],
            "key" => $data['key']
        ]);
        return json_decode($response, TRUE);
    }
}
