<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BlogController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    
    public function index(Request $request)
    {


        if (!empty($request->id_kategori))
            if ($request->urutan == 'terbaru')
                $data = DB::table('posting')
                            ->join('kategori', 'posting.id_kategori', '=', 'kategori.id')
                            ->where('id_kategori', $request->id_kategori)
                            ->orderBy('id', 'desc')
                            ->select('posting.*', 'kategori.nama_kategori')
                            ->get();
            else
                $data = DB::table('posting')
                            ->join('kategori', 'posting.id_kategori', '=', 'kategori.id')
                            ->where('id_kategori', $request->id_kategori)
                            ->select('posting.*', 'kategori.nama_kategori')
                            ->get();
        else
            if ($request->urutan == 'terbaru')
                $data = DB::table('posting')
                            ->join('kategori', 'posting.id_kategori', '=', 'kategori.id')
                            ->orderBy('id', 'desc')
                            ->select('posting.*', 'kategori.nama_kategori')
                            ->get();
            else
                $data = DB::table('posting')
                            ->join('kategori', 'posting.id_kategori', '=', 'kategori.id')
                            ->select('posting.*', 'kategori.nama_kategori')
                            ->get();
        

        for ($i=0; $i < sizeof($data); $i++) { 
            if (!empty($data[$i]->gambar))
                $data[$i]->url_gambar = url('/public/gambar').'/'.$data[$i]->gambar;
        }
        
        return response ([
            'status'  => true,
            'message' => 'Behasil tampil data',
            'data'    => $data
        ], 200);

    }

    public function detail(Request $request)
    {

        $data = DB::table('posting')
                    ->where('id', $request->id)
                    ->get();

        if (!empty($data)) {
            
            $hasil = [
                'status'  => true,
                'message' => 'Berhasil tampil detail data',
                'data'    => $data
            ];

        } else {

            $hasil = [
                'status'  => false,
                'message' => 'Gagal tampil detail data',
                'data'    => []
            ];

        }

        return response($hasil, 200);

    }

    public function cari(Request $request)
    {

        $cari = '%'.$request->cari.'%';

        $data = DB::table('posting')
                     ->join('kategori', 'posting.id_kategori', '=', 'kategori.id')
                     ->where('posting.judul', 'like', $cari)
                     ->orwhere('posting.isi', 'like', $cari)
                     ->orwhere('kategori.nama_kategori', 'like', $cari)
                     ->select('posting.*', 'kategori.nama_kategori')
                     ->get();
        

        if ($data == '[]') {

            $hasil = [
                'status'  => false,
                'message' => 'Data tidak ditemukan',
                'data'    => []
            ];

        } else {

            $hasil = [
                'status'  => true,
                'message' => 'Data berhasil didapat',
                'data'    => $data
            ];

        }

        return response($hasil, 200);

    }

    public function tambah(Request $request)
    {

        $nama_foto = $this->foto($request->file('gambar'));

        $data = [
            'id_kategori' => $request->input('id_kategori'),
            'judul'       => $request->input('judul'),
            'gambar'      => $nama_foto,
            'isi'         => $request->input('isi'),
            'created_at'  => date('Y-m-d')
        ];

        DB::table('posting')->insert($data);

        $data['url_gambar'] = url('/public/gambar/').$nama_foto;

        return response([
            'status'  => true,
            'message' => 'Berhasil tambah data',
            'data'    => $data
        ], 200);

    }

    private function foto($foto)
    {
            
        $resorce = $foto;//$request->file('foto');
        $nama    = $resorce->getClientOriginalName();
        $gambar_lama = \base_path() ."/public/gambar/". $nama;
        
        if (file_exists($gambar_lama))
            @unlink($gambar_lama);

        $resorce->move(\base_path() ."/public/gambar", $nama);

        return $nama;

    }

    private function edit_foto($id, $foto)
    {
            
        $resorce = $foto;//$request->file('foto');
        $nama    = $resorce->getClientOriginalName();

        $cek_lama = DB::table('posting')
                        ->where('id', $id)
                        ->get();

        $gambar_lama = \base_path() ."/public/gambar/". $cek_lama[0]->gambar;
        
        if (file_exists($gambar_lama))
            @unlink($gambar_lama);

        $resorce->move(\base_path() ."/public/gambar", $nama);

        return $nama;

    }

    public function edit(Request $request)
    {

        $nama_gambar = $this->edit_foto($request->id, $request->file('gambar'));

        $data = [
            'judul'      => $request->judul,
            'isi'        => $request->isi,
            'gambar'     => $nama_gambar,
            'updated_at' => date('Y-m-d')
        ];

        $cek = DB::table('posting')
                ->where('id', $request->id)
                ->where('id_kategori', $request->id_kategori)
                ->update($data);

        if (!empty($cek)) {

            $get_data = DB::table('posting')
                            ->where('id', $request->id)
                            ->where('id_kategori', $request->id_kategori)
                            ->get();
            $get_data['url_gambar'] = url('/public/gambar/').$nama_gambar;
            
            $hasil = [
                'status'  => true,
                'message' => 'Berhasil edit data',
                'data'    => $get_data
            ];

        } else {

            $hasil = [
                'status'  => false,
                'message' => 'Gagal edit data',
                'data'    => []
            ];

        }

        return response($hasil, 200);

    }

    public function hapus(Request $request)
    {

        $cek = DB::table('posting')
                   ->where('id', $request->id)
                   ->where('id_kategori', $request->id_kategori)
                   ->delete();

        if (!empty($cek)) {
            
            $hasil = [
                'status'  => true,
                'message' => 'Berhasil hapus data',
                'data'    => []
            ];

        } else {

            $hasil = [
                'status'  => false,
                'message' => 'Gagal hapus data',
                'data'    => []
            ];

        }

        return response($hasil, 200);

    }

    //
}
