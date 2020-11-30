<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * 
 */
class KategoriController extends Controller
{
	
	public function index()
	{

		$data = DB::table('kategori')->get();

		if ($data) {
			
			$data = [
				'status'  => true,
				'message' => 'Data kategori berhasil di dapat',
				'data'    => $data
			];

		} else {

			$data = [
				'status'  => false,
				'message' => 'Data kategori gagal didapat',
				'data'    => []
			];

		}

		return response ($data);

	}

	public function tambah(Request $request)
	{

		$data = [
			'nama_kategori' => $request->nama_kategori,
			'created_at'    => date('Y-m-d')
		];

		$insert_get_id = DB::table('kategori')->insertGetId($data);

		$get = DB::table('kategori')->where(['id'=>$insert_get_id])->get();

		return response([
			'status'  => true,
			'message' => 'Berhasil tambah data',
			'data'    => $get
		]);

	}

	public function edit(Request $request)
	{

		$data = [
			'nama_kategori' => $request->nama_kategori,
			'updated_at'    => date('Y-m-d')
		];

		$cek = DB::table('kategori')->where('id', $request->id_kategori)->update($data);

		if ($cek) {
			
			$get_update = DB::table('kategori')->where('id', $request->id_kategori)->get();

			$hasil = [
				'status'  => true,
				'message' => 'Kategori berhasil di edit',
				'data'    => $get_update,
			];

		} else {

			$hasil = [
				'status'  => false,
				'message' => 'Kategori gagal di edit',
				'data'    => [],
			];

		}

		return response($hasil, 200);

	}

	public function hapus(Request $request)
	{

		$cek = DB::table('kategori')->where('id', $request->id_kategori)->delete();

		if ($cek) {

			$hasil = [
				'status'  => true,
				'message' => 'Kategori berhasil di hapus',
				'data'    => [],
			];

		} else {

			$get_update = DB::table('kategori')->where('id', $request->id_kategori)->get();

			$hasil = [
				'status'  => false,
				'message' => 'Kategori gagal di hapus',
				'data'    => $get_update,
			];

		}

		return response($hasil, 200);

	}
	
}