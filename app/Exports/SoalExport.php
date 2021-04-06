<?php

namespace App\Exports;

use App\Soal;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\Exportable;

class SoalExport implements FromView
{
	use Exportable;

	// Construct
	public function __construct(int $paket){
		$this->paket = $paket;
	}

    /**
    * @return \Illuminate\Support\Collection
    */
    public function view(): View
    {
    	// Get data
    	$soal = Soal::where('id_paket','=',$this->paket)->orderBy('nomor','asc')->get();
        foreach($soal as $data){
        	$array = json_decode($data->soal, true);
        	$data->soal = $array;
        }

    	// View
    	return view('soal/admin/excel', [
    		'soal' => $soal
    	]);
    }
}
