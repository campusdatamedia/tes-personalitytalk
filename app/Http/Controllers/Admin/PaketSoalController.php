<?php

namespace App\Http\Controllers\Admin;

use Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\PaketSoal;
use App\Soal;

class PaketSoalController extends Controller
{    
    /**
     * Menampilkan data paket soal
     * 
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Data paket soal
        $paket = PaketSoal::join('tes','paket_soal.id_tes','=','tes.id_tes')->orderBy('status','asc')->orderBy('paket_soal.id_tes','asc')->orderBy('paket_soal.part','asc')->get();
        foreach($paket as $key=>$data){
            $paket[$key]->jumlah_soal = Soal::where('id_paket','=',$data->id_paket)->count();
        }

        // View
        return view('admin/paket-soal/index', [
            'paket' => $paket
        ]);
    }

    /**
     * Menampilkan detail paket soal
     * 
     * int $id
     * @return \Illuminate\Http\Response
     */
    public function detail($id)
    {
        // Data paket soal
        $paket = PaketSoal::join('tes','paket_soal.id_tes','=','tes.id_tes')->findOrFail($id);
        $paket->jumlah_soal = Soal::where('id_paket','=',$paket->id_paket)->count();

        // View
        return view('admin/paket-soal/detail', [
            'paket' => $paket
        ]);
    }

    /**
     * Menampilkan data soal
     * 
     * int $id
     * @return \Illuminate\Http\Response
     */
    public function questions($id)
    {
        // Data paket soal
        $paket = PaketSoal::join('tes','paket_soal.id_tes','=','tes.id_tes')->findOrFail($id);
        $soal = Soal::join('paket_soal','soal.id_paket','=','paket_soal.id_paket')->where('soal.id_paket','=',$paket->id_paket)->orderBy('nomor','asc')->get();

        // View
        return view('admin/paket-soal/questions', [
            'paket' => $paket,
            'soal' => $soal,
        ]);
    }
}