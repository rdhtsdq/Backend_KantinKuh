<?php

namespace App\Http\Controllers;

use App\Models\log_product;
use App\Models\log_user;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class log_and_trigger extends Controller
{
    public function log_product()
    {
        $log = log_product::all();

        return response()->json($log,Response::HTTP_OK);
    }

    public function log_user()
    {
        $log = log_user::all();
        return response()->json($log,Response::HTTP_OK);
    }

    public function SelectView()
    {
        $data = DB::select("SELECT * FROM laporan");

        return response()->json($data,Response::HTTP_OK);
    }
}
