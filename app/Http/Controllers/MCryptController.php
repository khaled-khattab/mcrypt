<?php

namespace App\Http\Controllers;

use App\Traits\MCrypt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MCryptController extends Controller
{
    use MCrypt;

    public static function decrypt_encrypt(Request $request){
        try{
            $data = ['message'=> $request['message']];
            $data_encrypted = self::encrypt($data);

            //Log::info('Encryption: ', ['data'=> $data, 'data_decrypted'=> $data_encrypted]);

            return response()->json(['data'=> $data_encrypted], 200);
        } catch (\Exception $e){
            return response()->json([
                'message'=> $e->getMessage(),
                'file'=> $e->getFile(),
                'line'=> $e->getLine()
            ], 500);
        }
    }
}
