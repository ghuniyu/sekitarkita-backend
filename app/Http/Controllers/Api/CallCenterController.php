<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CallCenter;
use App\Models\Hospital;
use Illuminate\Support\Facades\DB;


class CallCenterController extends Controller
{
    public function getCallCenters(){
        $callCenters = DB::table('call_centers')->get();
        
        if($callCenters){
            return response()->json([
                'success' => true,
                'callCenters' => $callCenters
            ]);
        } else {
            return response()->json([
                'success' => false,
                'messages' => 'Error'
            ]);
        }
    }

    public function getHospitals(){
        $hospitals = DB::table('hospitals')->get();

        if($hospitals){
            return response()->json([
                'success' => true,
                'hospitals' => $hospitals
            ]);
        } else {
            return response()->json([
                'success' => false,
                'messages' => 'Error'
            ]);
        }
    }
}
