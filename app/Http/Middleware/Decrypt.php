<?php

namespace App\Http\Middleware;

use App\Traits\MCrypt;
use Closure;
use Illuminate\Support\Facades\Log;

class Decrypt
{
    use MCrypt;
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(!isset($request['data']))
            return response(['message'=> "data is required"], 400);

        $data_encrypted = $request['data'];
        $data_decrypted = (array)self::decrypt($data_encrypted);

        //Log::info('Decryption: ', ['data_encrypted'=> $data_encrypted, 'data_decrypted'=> $data_decrypted]);

        if(!isset($data_decrypted['code']))
            return response(['message'=> "sent data is not correct"], 400);

        if($data_decrypted['code'] == 0)
            $request['message'] = "success";
        elseif ($data_decrypted['code'] == 1)
            $request['message'] = "failure";
        else
            return response(['message'=> "sent data is not correct"], 400);

        return $next($request);
    }
}
