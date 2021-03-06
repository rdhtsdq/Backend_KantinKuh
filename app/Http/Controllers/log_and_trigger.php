<?php

namespace App\Http\Controllers;

use App\Models\log_product;
use App\Models\log_user;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class log_and_trigger extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api');
    }

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
    public function ShowView()
    {
        $data = DB::select("SELECT jumlah , nama , total ,SUM(total) as semua_pemasukan from laporan");

        return response()->json($data,Response::HTTP_OK);
    }
    public function Perbulan($bulan)
    {
        $data = DB::table('laporan')->whereMonth("waktu","=",$bulan)->get();
        return response()->json([
            "message" => "data perbulan",
            "data" => $data
        ],
        Response::HTTP_OK
    );
    }
    public function JumlahPerbulan($bulan)
    {
        $data = DB::select("SELECT SUM('jumlah') as jumlah FROM laporan WHERE month('waktu') = $bulan ");
        return response()->json([
            "message" => "ok",
            "data" => $data
        ],
        Response::HTTP_OK
    );


    }

    public function Pertahun($tahun)
    {
        $data = DB::table('laporan')->whereYear("waktu",'=',$tahun)->get();
        return response()->json([
            "message" => "data pertahun",
            "data" => $data
        ],
        Response::HTTP_OK
    );
    }

    public function JumlahPertahun($tahun)
    {
        $data = DB::select("SELECT SUM('jumlah') as jumlah FROM laporan WHERE year('waktu') = $tahun");
        return response()->json([
            "message" => "ok",
            "data" => $data
        ],
        Response::HTTP_OK
    );
    }

}
