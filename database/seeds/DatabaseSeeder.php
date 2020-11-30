<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
        $data = [
        	'id_kategori' => 1,
        	'judul'       => 'judul',
        	'isi'		  => 'isi',
        	'gambar'      => '90020226_1096043830750783_8017120447319506944_n.jpg',
        	'created_at'  => date('Y-m-d'),
        	'created_at'  => date('Y-m-d'),
        ];

        DB::table('posting')->insert($data);

    }
}
