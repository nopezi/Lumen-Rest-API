<?php 

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\ResponseHandler;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Arr;

class TestController extends Controller
{

	public function palindrome(Request $request)
	{
		
		$kata = $request->kata;
		$cek = $this->cek_palindrome($kata);

		if (!empty($cek)) {
			echo 'true';
		} else {
			echo "false";
		}

	}

	private function cek_palindrome($kata)
	{
	    $cek   = strtolower(preg_replace("/[^A-Za-z0-9]/","",$kata));
	    $hasil = $cek==strrev($cek);
	    return $hasil;
	}

	public function merge(Request $request)
	{
		
		$huruf = $request->huruf;
		$jumlah = $request->jumlah;

		$hasil = $this->merge_the_words($huruf, $jumlah);

		echo json_encode($hasil);

	}

	private function merge_the_words($huruf, $jumlah)
	{
		
		# hilangkan_spasi
		$huruf = str_replace(' ', '', $huruf);
		# total jumlah baris
		$total_huruf = strlen($huruf);
		# total huruf di bagi jumlah
		$potong_ = $total_huruf / $jumlah;
		$total_looping = $total_huruf - $jumlah;
		$selanjutnya[] = 0;
		$potong[] =  $potong_;

		for ($i=0; $i < $total_looping; $i++) { 

			if (!empty($potong[$i]) && $potong[$i] <= $total_looping) {

				// echo $selanjutnya[$i].' potong '.$potong[$i].'<br>';
				# sisipkan spaci di dalam string nya
				$huruf = substr_replace($huruf, ' ', $potong[$i], 0);
				$selanjutnya[] = $selanjutnya[$i] + $potong[0];
				$potong[] = $potong[$i] +  $potong[0];
				
			}

		}

		# explode string dari supaya jadi array
		$pecah = explode(' ', $huruf);
		# bersihkan huruf dobel
		for ($i=0; $i < sizeof($pecah); $i++) { 
			$hasil[] = count_chars($pecah[$i], 3);
		}

		return $hasil;

	}

}