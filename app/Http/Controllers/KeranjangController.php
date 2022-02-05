<?php

namespace App\Http\Controllers;

use App\Models\Keranjang;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class KeranjangController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $keranjang = Keranjang::with('product','transaction')->orderBy('created_at','DESC')->get();
        $response = [
            "message" => "Data Keranjang dengan produk dan transaksi",
            "data" => $keranjang
        ];

        return response()->json($response,Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'kode_keranjang' => ['required'],
            'jumlah' => ['required']
        ]);
        if ($validator->fails()) {
            return response()->json(
                $validator->errors(),
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }
        try{
            $keranjang = Keranjang::create([
                'kode_keranjang' => $request->kode_keranjang,
                'jumlah' => $request->jumlah,
                'keterangan' => $request->keterangan
            ]);


            $kode = Keranjang::findOrFail($request->kode_keranjang);
            $kode->product()->attach($request->kode);

            $response = [
                "message" => "data berhasil dibuat",
                "data" => $keranjang
            ];

            return response()->json($response, Response::HTTP_CREATED);

        }catch(QueryException $e){
            $response = [
                "message" => "gagal",
                "error" => $e->errorInfo
            ];
            return response()->json($response);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($kode_keranjang)
    {
        $keranjang = Keranjang::with('product')->findOrFail($kode_keranjang);
        $response = [
            "message" => "data keranjang by id",
            "data" => $keranjang
        ];

        return response()->json($response,Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $kode_keranjang)
    {
        $keranjang = Keranjang::findOrFail($kode_keranjang);
        if (empty($request->get('kode'))) {
            $validator = Validator::make($request->all(),[
                'jumlah' => ['required']
            ]);
            if($validator->fails()){
                return response()->json([
                    $validator->errors(),
                    Response::HTTP_UNPROCESSABLE_ENTITY
                ]);
            }
            $keranjang->update($request->all());
            $response = [
                "message" => "data berhasil diperbarui",
                "data" => $keranjang
            ];

            return response()->json($response,Response::HTTP_OK);
        }else{
            $validator = Validator::make($request->all(),[
                'jumlah' => ['required']
            ]);
            if($validator->fails()){
                return response()->json([
                    $validator->errors(),
                    Response::HTTP_UNPROCESSABLE_ENTITY
                ]);
            }
            $keranjang->update($request->all());
            $keranjang->product()->attach($request->get('kode'));
            $response = [
                "message" => "data berhasil diperbarui",
                "data" => $keranjang
            ];
            return response()->json($response,Response::HTTP_OK);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($kode_keranjang)
    {
        $keranjang = Keranjang::findOrFail($kode_keranjang);
        try {
            $keranjang->delete();
            $response = [
                'message' => 'Data Produk Berhasil Dihapus',
            ];
            return response()->json($response,Response::HTTP_OK);
        } catch (QueryException $e) {
            $response = [
                "message" => "gagal",
                "error" => $e->errorInfo
            ];
            return response()->json($response);
        }
    }
}
