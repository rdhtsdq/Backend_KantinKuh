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
		// Validasi Data dari request
		$validator = Validator::make($request->all(), [
			'kode_keranjang' => ['required'],
			'kode' => ['required'],
			'jumlah' => ['required']
		]);
		if ($validator->fails()) {
			return response()->json(
				$validator->errors(),
				Response::HTTP_UNPROCESSABLE_ENTITY
			);
		}

		// Insert Data

		try {

			$jumlah = $request->jumlah;
			$product = $request->kode;

			Keranjang::create([
				'kode_keranjang' => $request->kode_keranjang,
				'keterangan' => $request->keterangan
			]);


			$kode = Keranjang::find($request->kode_keranjang);
			$sync_data = [];
			for ($i = 0; $i < count($product); $i++) {
				$sync_data[$product[$i]] = ['jumlah' => $jumlah[$i]];
				$kode->product()->sync($sync_data);
			}

			$hasil = Keranjang::findOrFail($request->kode_keranjang);
			$response = [
				"message" => "data berhasil dibuat",
				"data" => $hasil
			];

			return response()->json($response, Response::HTTP_CREATED);
		} 
		catch (QueryException $e) {
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
		$keranjang = Keranjang::with('product','transaction')->findOrFail($kode_keranjang);
		$response = [
			"message" => "data keranjang by id",
			"data" => $keranjang
		];

		return response()->json($response, Response::HTTP_OK);
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
		$product = $request->kode;
		$jumlah = $request->jumlah;
		$keranjang = Keranjang::findOrFail($kode_keranjang);
		$keterangan = $request->keterangan;

		if (!empty($request->get('kode'))) {
			$validator = Validator::make($request->all(), [
				'jumlah' => ['required'],
				'kode' => ['required']
			]);
			if ($validator->fails()) {
				return response()->json([
					$validator->errors(),
					Response::HTTP_UNPROCESSABLE_ENTITY
				]);
			}
			try {
				$sync_data = [];
			for ($i = 0; $i < count($product); $i++) {
				$sync_data[$product[$i]] = ['jumlah' => $jumlah[$i],'keterangan' => $keterangan[$i]];
				$keranjang->product()->sync($sync_data);
			}
				$response = [
					"message" => "data berhasil diperbarui",
					"data" => $keranjang
				];

				return response()->json($response,Response::HTTP_OK);
			} catch (QueryException $e ) {
				$response = [
					"message" => "gagal",
					"error" => $e->errorInfo
				];
				return response()->json($response);
			}
			
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
