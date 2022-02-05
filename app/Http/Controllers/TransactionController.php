<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class TransactionController extends Controller
{
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		$transaction = Transaction::with('keranjang')->orderBy('created_at','DESC')->get();
		$response = [
			"message" => "data transaksi",
			"data" => $transaction
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
			'nama' => ['required'],
			'telepon' => ['required']
		]);

		if($validator->fails()){
			return response()->json(
				$validator->errors(),
				Response::HTTP_UNPROCESSABLE_ENTITY
			);
		}
		try {
			$transaction = Transaction::create(
				$request->all()
				// [
				// 'kode_keranjang' => $request->nama,
				// 'nama' => $request->harga,
				// 'telepon' => $request->status
				// ]
		);
			$response = [
				'message' => 'Produk Berhasil Dibuat',
				'data' => $transaction,
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
	public function show($kode_transaksi)
	{
		$transaction = Transaction::with('keranjang')->findOrFail($kode_transaksi);
		$response = [
			"message" => "transaksi by kode",
			"data" => $transaction
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
	public function update(Request $request, $kode_transaksi)
	{
		$transaction = Transaction::findOrFail($kode_transaksi);
		$validator = Validator::make($request->all(),[
			'kode_keranjang' => ['required'],
			'nama' => ['required'],
			'telepon' => ['required']
		]);

		if($validator->fails()){
			return response()->json(
				$validator->errors(),
				Response::HTTP_UNPROCESSABLE_ENTITY
			);
		}
		try {
			$transaction->update($request->all());
			$response = [
				'message' => 'Produk Berhasil Diubah',
				'data' => $transaction,
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
	public function destroy($kode_transaksi)
	{
		$transaction = Transaction::findOrFail($kode_transaksi);

		try {
			$transaction->delete();
			$response = [
				'message' => 'Data Transaksi Berhasil Dihapus',
			];
			return response()->json($response,Response::HTTP_OK);
		} catch (QueryException $e) {
			return response()->json([
				'message' => 'Gagal ' . $e->errorInfo
			]);
		}
	}
}
