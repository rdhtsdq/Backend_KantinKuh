<?php

namespace App\Http\Controllers;

use App\Models\Pengeluaran;
use Illuminate\Database\Events\QueryExecuted;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class PengeluaranController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }
    public function index()
    {
        $pengeluaran = Pengeluaran::all();
        return response()->json([
            "message" => "all",
            "data" => $pengeluaran
        ],Response::HTTP_OK);

    }
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            "id_pengeluaran" => ['required'],
            "jumlah" => ["required"],
            "keterangan" => ["required"]
        ]);

        if ($validator->fails()) {
            return response()->json(["gagal" => $validator->errors()],Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            $pengeluaran = Pengeluaran::create([
               "id_pengeluaran" => $request->id_pengeluaran,
               "jumlah" => $request->jumlah,
               "keterangan" => $request->keterangan 
            ]);

            return response()->json([
                "message" => "created",
                "data" => $pengeluaran
            ],Response::HTTP_CREATED);

        } catch (QueryException $e) {
            return response()->json([
                "message" => $e->errorInfo
            ],Response::HTTP_NOT_ACCEPTABLE);
        }
    }
    public function show(Request $request,$id_pengeluaran)
    {
        $pengeluaran = Pengeluaran::findOrFail($id_pengeluaran);

        return response()->json([
            "message" => "ok",
            "data" => $pengeluaran
        ],
        Response::HTTP_OK
    );
    }

    public function update(Request $request,$id_pengeluaran)
    {
        $pengeluaran = Pengeluaran::find($id_pengeluaran);
        if ($pengeluaran) {
            try {
                // $pengeluaran = new Pengeluaran();

                $pengeluaran->update([
                    "jumlah" => $request->jumlah,
                    "keterangan" => $request->keterangan
                ]);
    
                return response()->json([
                    "message" => "created",
                    "data" => $pengeluaran
                ],Response::HTTP_CREATED);
                
            } catch (QueryExecuted $e) {
                return response()->json([
                    "message" => "gagal",
                    "error" => $e->errorInfo
                ]);
            }    
        }else{
            return response()->json([
               "error" =>"not found" 
            ],
            Response::HTTP_NOT_FOUND  
        );
        }

        
    }
    public function destroy(Request $request,$id_pengeluaran)
    {
        $pengeluaran = Pengeluaran::findOrFail($id_pengeluaran);
        try {
            $pengeluaran->delete();
            return response()->json([
                "message" => "deleted"
            ],
            Response::HTTP_OK
        );
        } catch (QueryExecuted $e) {
            return response()->json([
                "message" => "fail",
                "error" => $e->errorInfo
            ],
            Response::HTTP_UNPROCESSABLE_ENTITY
        );
        }
    }
    public function perbulan($bulan)
    {
        $all = Pengeluaran::whereMonth("created_at","=",$bulan)->get();
        $jumlah = DB::select("SELECT SUM(jumlah) AS jumlah FROM pengeluarans where month(created_at) = $bulan");
        $data = [
            $all,$jumlah
        ];
        return response()->json($data,200);
    }

    public function pertahun($tahun)
    {
        $all = Pengeluaran::whereYear("created_at","=",$tahun)->get();

        $jumlah = DB::select("SELECT SUM(jumlah) AS jumlah FROM pengeluarans where year(created_at) = $tahun");
        $data = [$all,$jumlah];
        return response()->json($data,200);
    }
}