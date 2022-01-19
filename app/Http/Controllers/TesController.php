<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use App\Models\Hasil;
use App\Models\PaketSoal;
use App\Models\Pelamar;
use App\Models\Seleksi;
use App\Models\Soal;
use App\Models\TempTes;
use App\Models\Tes;
use App\Models\User;

class TesController extends Controller
{    
    /**
     * Menampilkan halaman tes
     * 
     * @return \Illuminate\Http\Request
     * @return \Illuminate\Http\Response
     */
    public function tes(Request $request, $path)
    {
        // Variables
        $part = null;
        $seleksi = false;

        // Get tes
        $tes = Tes::where('path','=',$path)->firstOrFail(); // Get tes
        $check = Auth::user()->role == 6 ? Hasil::where('id_user','=',Auth::user()->id_user)->first() : null; // Check
        
        // Jika role pelamar
        if(Auth::user()->role == 4){
        	$akun = Pelamar::where('id_user','=',Auth::user()->id_user)->first(); // Get akun
            $seleksi = $akun ? Seleksi::where('id_pelamar','=',$akun->id_pelamar)->first() : false; // Get seleksi
        }
            
        // Tes DISC 40
        if($path == 'disc-40-soal')
            return \App\Http\Controllers\Test\DISC40Controller::index($request, $path, $tes, $seleksi, $check);
        // Tes DISC 24
        elseif($path == 'disc-24-soal')
            return \App\Http\Controllers\Test\DISC24Controller::index($request, $path, $tes, $seleksi, $check);
        // Tes Papikostick
        elseif($path == 'papikostick')
            return \App\Http\Controllers\Test\PapikostickController::index($request, $path, $tes, $seleksi, $check);
        // Tes SDI
        elseif($path == 'sdi')
            return \App\Http\Controllers\Test\SDIController::index($request, $path, $tes, $seleksi, $check);
        // Tes MSDT
        elseif($path == 'msdt')
            return \App\Http\Controllers\Test\MSDTController::index($request, $path, $tes, $seleksi, $check);
        // Tes IST
        elseif($path == 'ist')
            // return \App\Http\Controllers\Test\ISTController::index($request, $path, $tes, $seleksi, $check);
            return \App\Http\Controllers\Test\ISTController::try($request, $path, $tes, $seleksi, $check);
        // Tes RMIB
        elseif($path == 'rmib' || $path == 'rmib-2')
            return \App\Http\Controllers\Test\RMIBController::index($request, $path, $tes, $seleksi, $check);
        else
            abort(404);
    }

    /**
     * Memproses dan menyimpan tes
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Tes DISC 40
        if($request->path == 'disc-40-soal')
            return \App\Http\Controllers\Test\DISC40Controller::store($request);
        // Tes DISC 24
        elseif($request->path == 'disc-24-soal')
            return \App\Http\Controllers\Test\DISC24Controller::store($request);
        // Tes Papikostick
        elseif($request->path == 'papikostick')
            return \App\Http\Controllers\Test\PapikostickController::store($request);
        // Tes SDI
        elseif($request->path == 'sdi')
            return \App\Http\Controllers\Test\SDIController::store($request);
        // Tes MSDT
        elseif($request->path == 'msdt')
            return \App\Http\Controllers\Test\MSDTController::store($request);
        // Tes IST
        elseif($request->path == 'ist')
            return \App\Http\Controllers\Test\ISTController::store($request);
        // Tes RMIB
        elseif($request->path == 'rmib' || $request->path == 'rmib-2')
            return \App\Http\Controllers\Test\RMIBController::store($request);
    }

    /**
     * Menghapus temp tes (jika ada)
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request)
    {
        // Tes IST
        if($request->path == 'ist')
            return \App\Http\Controllers\Test\ISTController::delete($request);
    }






    public function try(Request $request)
    {
        // Variables
        $path = 'ist';
        $part = null;
        $seleksi = false;

        // Get tes
        $tes = Tes::where('path','=',$path)->firstOrFail(); // Get tes
        $check = Auth::user()->role == 6 ? Hasil::where('id_user','=',Auth::user()->id_user)->first() : null; // Check
        
        // Jika role pelamar
        if(Auth::user()->role == 4){
        	$akun = Pelamar::where('id_user','=',Auth::user()->id_user)->first(); // Get akun
            $seleksi = $akun ? Seleksi::where('id_pelamar','=',$akun->id_pelamar)->first() : false; // Get seleksi
        }

        // Return
        return \App\Http\Controllers\Test\ISTController::try($request, $path, $tes, $seleksi, $check);
    }
}