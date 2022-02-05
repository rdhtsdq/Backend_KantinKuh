<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $product = Product::orderBy('created_at','DESC')->get();
        $reponse = [
            "message" => "data product",
            "data" => $product
        ];

        return response()->json($reponse,Response::HTTP_OK);
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
            'kode' => ['required'],
            'nama' => ['required'],
            'harga' => ['required','numeric'],
            'status' => ['required','in:ada,habis'],
            'gambar' => ['required'],
            'kategori' => ['required','in:food,drink,snack']
        ]);

        if($validator->fails()){
            return response()->json(
                $validator->errors(),
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }
        try {
            $produk = product::create([
                'kode' => $request->kode,
                'nama' => $request->nama,
                'harga' => $request->harga,
                'status' => $request->status,
                'gambar' => $request->gambar,
                'kategori' => $request->kategori
            ]);
            $response = [
                'message' => 'Produk Berhasil Dibuat',
                'data' => $produk,
            ];


            return response()->json($response, Response::HTTP_CREATED);
        } catch (QueryException $e) {
            return response()->json([
                'message' => "Failed " . $e->errorInfo . $request,
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($kode)
    {
        $product = Product::findOrFail($kode);
        $response = [
            "message" => "product by kode",
            "data" => $product
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
    public function update(Request $request, $kode)
    {
        $product = Product::findOrFail($kode);
        $validator = Validator::make($request->all(), [
            'nama' => ['required'],
            'harga' => ['required','numeric'],
            'status' => ['required','in:ada,habis'],
            'gambar' => ['required'],
            'kategori' => ['required','in:food,drink,snack']
        ]);

        if ($validator->fails()) {
            return response()->json(
                $validator->errors(),
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        try {
            $product->update([
                'kode' => $request->kode,
                'nama' => $request->nama,
                'harga' => $request->harga,
                'status' => $request->status,
                'gambar' => $request->gambar,
                'kategori' => $request->kategori
            ]);
            $response = [
                'message' => 'Produk Berhasil Diubah',
                'data' => $product,
            ];


            return response()->json($response, Response::HTTP_CREATED);
        } catch (QueryException $e) {
            return response()->json([
                'message' => "Failed " . $e->errorInfo . $request,
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($kode)
    {
        // $product = product::find($kode);

        try {
            Product::destroy($kode);
            $response = [
                'message' => 'Data Produk Berhasil Dihapus',
            ];
            return response()->json($response,Response::HTTP_OK);
        } catch (QueryException $e) {
            $response = [
                "message" => "gagal ",
                "error" => $e->errorInfo
            ];
            return response()->json($response);
        }
    }
}
