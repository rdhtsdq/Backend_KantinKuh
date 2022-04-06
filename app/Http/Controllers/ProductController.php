<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\DB;
class ProductController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['index', 'show','mostPopular']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = [];
        $product = Product::orderBy('created_at','DESC')->get();
        foreach ($product as $pr ) {
            // $url = '127.0.0.1:8000' . Storage::url($pr->gambar);
            $url = asset("storage/image/".$pr->gambar);
            $response = [
                "data" => [
                    "kode" => $pr->kode,
                    "nama" => $pr->nama,
                    "harga" => $pr->harga,
                    "gambar" => $pr->gambar,
                    "status" => $pr->status,
                    "kategori" => $pr->kategori,
                    "image_url" => $url
                ],
            ];
            array_push($data,$response);
        }



        return response()->json($data,Response::HTTP_OK);
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
            'gambar' => ['required','mimes:jpg,bmp,png'],
            'kategori' => ['required','in:food,drink,snack']
        ]);

        if($validator->fails()){
            return response()->json(
                $validator->errors(),
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }
        try {
            $folder = "image";
            $image = $request->file('gambar');
            $path = $image->store($folder,'public');
            $produk = product::create([
                'kode' => $request->kode,
                'nama' => $request->nama,
                'harga' => $request->harga,
                'status' => $request->status,
                'gambar' => basename($path),
                'kategori' => $request->kategori
            ]);
            $response = [
                'message' => 'Produk Berhasil Dibuat',
                'data' => $produk,
                'image_url' => '127.0.0.1:8000' . Storage::url($path)
            ];


            return response()->json($response, Response::HTTP_CREATED);
        } catch (QueryException $e) {
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
    public function show($kode)
    {
        $product = Product::findOrFail($kode);
        $image = $product->gambar;
        $url = Storage::url($image);
        $response = [
            "message" => "product by kode",
            "data" => $product,
            "image" => '127.0.0.1:8000' . $url
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
            $data = [
                'message' => "Failed " . $request,
                'error' => $e->errorInfo,
            ];
            return response()->json($data);
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
    public function mostPopular()
    {
        $data = DB::select('SELECT nama,jumlah FROM laporan ORDER BY jumlah DESC LIMIT 3');
        return response()->json([
            "message" => "data most popular",
            "data" => $data
        ],
        Response::HTTP_OK
    );
    }
}
