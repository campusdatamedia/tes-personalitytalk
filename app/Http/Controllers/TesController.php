<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use App\Hasil;
use App\HRD;
use App\Karyawan;
use App\Lowongan;
use App\PaketSoal;
use App\Pelamar;
use App\Posisi;
use App\Seleksi;
use App\Soal;
use App\Tes;
use App\User;

class TesController extends Controller
{
    public $message;
    
    /**
     * Menampilkan dashboard pelamar
     * 
     * @return \Illuminate\Http\Response
     */
    public function dashboard()
    {
        // Jika role pelamar
        if(Auth::user()->role == 4){
        	// Get akun
        	$akun = Pelamar::where('id_user','=',Auth::user()->id_user)->first();
            
            // Seleksi
            $seleksi = Seleksi::where('id_pelamar','=',$akun->id_pelamar)->first();
            
            // Hasil
            $ids = array();
            $hasil = Hasil::where('id_user','=',Auth::user()->id_user)->get();
            if(count($hasil) > 0){
                foreach($hasil as $h){
                    if(!in_array($h->id_tes, $ids)){
                        array_push($ids, $h->id_tes);
                    }
                }
            }
            
            // Tes
            $lowongan = Lowongan::find($akun->posisi);
            $posisi = Posisi::find($lowongan->posisi);
            $posisi->tes = $posisi->tes != '' ? explode(",", $posisi->tes) : array();
            //$tes = !empty($posisi->tes) ? Tes::whereIn('id_tes', $posisi->tes)->whereNotIn('id_tes', $ids)->where('id_tes','!=',3)->get() : array();
            $tes = !empty($posisi->tes) ? Tes::whereIn('id_tes', $posisi->tes)->whereNotIn('id_tes', $ids)->get() : array();
			
			// Check
			$check = null;

        	// View
        	return view('dashboard/index', [
        		'akun' => $akun,
                'check' => $check,
                'seleksi' => $seleksi,
                'tes' => $tes,
        	]);
        }
        // Jika role karyawan
        elseif(Auth::user()->role == 3){
        	// Get akun
        	$akun = Karyawan::where('id_user','=',Auth::user()->id_user)->first();
            
            // Seleksi
            $seleksi = false;
			
            // Tes
            $posisi = Posisi::find($akun->posisi);
            $posisi->tes = $posisi->tes != '' ? explode(",", $posisi->tes) : array();
            $tes = !empty($posisi->tes) ? Tes::whereIn('id_tes', $posisi->tes)->get() : array();
			
			// Check
			$check = null;

        	// View
        	return view('dashboard/index', [
        		'akun' => $akun,
                'check' => $check,
                'seleksi' => $seleksi,
                'tes' => $tes,
        	]);
		}
        // Jika role bukan pelamar
        else{
        	// Get akun
        	$akun = User::find(Auth::user()->id_user);
            
            // Seleksi
            $seleksi = false;
            
            // Tes
            $tes = Tes::all();
			
			// Check
			if(Auth::user()->role == 6){
				$check = Hasil::where('id_user','=',Auth::user()->id_user)->first();
			}
			else{
				$check = null;
			}

        	// View
        	return view('dashboard/index', [
        		'akun' => $akun,
                'check' => $check,
                'seleksi' => $seleksi,
                'tes' => $tes,
        	]);
        }
    }
    
    /**
     * Menampilkan halaman tes
     * 
     * @return \Illuminate\Http\Response
     */
    public function tes($path)
    {
        // Get tes
        $tes = Tes::where('path','=',$path)->first();
        
        // Jika tidak ada
        if(!$tes){
            abort(404);
        }
        
        // Jika role pelamar
        if(Auth::user()->role == 4){
        	// Get akun
        	$akun = Pelamar::where('id_user','=',Auth::user()->id_user)->first();
            
            // Seleksi
            $seleksi = Seleksi::where('id_pelamar','=',$akun->id_pelamar)->first();
        }
        // Jika role bukan pelamar
        else{
            // Seleksi
            $seleksi = false;
        }
			
		// Check
		if(Auth::user()->role == 6){
			$check = Hasil::where('id_user','=',Auth::user()->id_user)->first();
		}
		else{
			$check = null;
		}
            
        // Tes DISC 40
        if($path == 'disc-40-soal'){
            $paket = PaketSoal::where('id_tes','=',$tes->id_tes)->where('status','=',1)->first();
            $soal = Soal::join('paket_soal','soal.id_paket','=','paket_soal.id_paket')->where('id_tes','=',$tes->id_tes)->where('status','=',1)->get();
            foreach($soal as $data){
                $data->soal = json_decode($data->soal, true);
            }
        }
        // Tes DISC 24
        elseif($path == 'disc-24-soal'){
            $paket = PaketSoal::where('id_tes','=',$tes->id_tes)->where('status','=',1)->first();
            $soal = Soal::join('paket_soal','soal.id_paket','=','paket_soal.id_paket')->where('id_tes','=',$tes->id_tes)->where('status','=',1)->get();
        }
        // Tes Papikostick
        elseif($path == 'papikostick'){
            $paket = PaketSoal::where('id_tes','=',$tes->id_tes)->where('status','=',1)->first();
            
        	// View
        	return view('tes/'.$path, [
                'paket' => $paket,
                'path' => $path,
                'seleksi' => $seleksi,
                'soal' => $this->data_papikostick(),
        		'tes' => $tes,
        	]);
        }
        // Tes SDI
        elseif($path == 'sdi'){
            $paket = PaketSoal::where('id_tes','=',$tes->id_tes)->where('status','=',1)->first();
            
        	// View
        	return view('tes/'.$path, [
                'paket' => $paket,
                'path' => $path,
                'seleksi' => $seleksi,
                'soal1' => $this->data_sdi()['soal1'],
                'soal2' => $this->data_sdi()['soal2'],
        		'tes' => $tes,
        	]);
        }
        // Tes MSDT
        elseif($path == 'msdt'){
            $paket = PaketSoal::where('id_tes','=',$tes->id_tes)->where('status','=',1)->first();
            $soal = Soal::join('paket_soal','soal.id_paket','=','paket_soal.id_paket')->where('paket_soal.id_tes','=',$tes->id_tes)->where('status','=',1)->first();
            $soal->soal = json_decode($soal->soal, true);
        }
        else{
            abort(404);
        }
            
    	// View
    	return view('tes/'.$path, [
            'check' => $check,
            'paket' => $paket,
            'path' => $path,
            'seleksi' => $seleksi,
            'soal' => $soal,
    		'tes' => $tes,
    	]);
    }

    /**
     * Mengirim lembar tes...
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if($request->path == 'disc-40-soal'){
            $this->storeDISC40($request);
        }
        elseif($request->path == 'disc-24-soal'){
            $this->storeDISC24($request);
        }
        elseif($request->path == 'papikostick'){
            $this->storePapikostick($request);
        }
        elseif($request->path == 'sdi'){
            $this->storeSDI($request);
        }
        elseif($request->path == 'msdt'){
            $this->storeMSDT($request);
        }

        // Redirect
        return redirect('/dashboard')->with(['message' => $this->message]);
    }

    /**
     * Proses lembar tes DISC 40 Soal...
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeDISC40(Request $request)
    {
    	// Get data HRD
    	if(Auth::user()->role == 2){
            $hrd = HRD::where('id_user','=',Auth::user()->id_user)->first();
        }
    	elseif(Auth::user()->role == 3){
    	    $karyawan = Karyawan::where('id_user','=',Auth::user()->id_user)->first();
            $hrd = HRD::find($karyawan->id_hrd);
        }
    	elseif(Auth::user()->role == 4){
    	    $pelamar = Pelamar::where('id_user','=',Auth::user()->id_user)->first();
            $hrd = HRD::find($pelamar->id_hrd);
        }
        
        // Declare variables
        $m = $request->get('m');
        $l = $request->get('l');
        $disc = array('D', 'I', 'S','C');
        $disc_m = array();
        $disc_l = array();
        $disc_score_m = array();
        $disc_score_l = array();
        $soal = Soal::join('paket_soal','soal.id_paket','=','paket_soal.id_paket')->where('status','=',1)->orderBy('nomor','asc')->get();
        foreach($soal as $key=>$data){
            array_push($disc_m, $m[$data->nomor]);
            array_push($disc_l, $l[$data->nomor]);
        }

        // Hitung score MOST dan LEAST
        $array_count_m = array_count_values($disc_m);
        $array_count_l = array_count_values($disc_l);
        foreach($disc as $letter){
            $disc_score_m[$letter] = array_key_exists($letter, $array_count_m) ? discScoringM($array_count_m[$letter]) : 0;
            $disc_score_l[$letter] = array_key_exists($letter, $array_count_l) ? discScoringL($array_count_l[$letter]) : 0;
        }
        
        // Convert DISC score to JSON
        $array = array('M' => $disc_score_m, 'L' => $disc_score_l);

        // Menyimpan data
        $hasil = new Hasil;
        $hasil->id_hrd = isset($hrd) ? $hrd->id_hrd : 0;
        $hasil->id_user = Auth::user()->id_user;
        $hasil->id_tes = $request->id_tes;
        $hasil->id_paket = $request->id_paket;
        $hasil->hasil = json_encode($array);
        $hasil->test_at = date("Y-m-d H:i:s");
        $hasil->save();
        
        // Message
        $this->message = "Berhasil melakukan tes DISC.";
    }

    /**
     * Proses lembar tes DISC 24 Soal...
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeDISC24(Request $request)
    {
    	// Get data HRD
    	if(Auth::user()->role == 2){
            $hrd = HRD::where('id_user','=',Auth::user()->id_user)->first();
        }
    	elseif(Auth::user()->role == 3){
    	    $karyawan = Karyawan::where('id_user','=',Auth::user()->id_user)->first();
            $hrd = HRD::find($karyawan->id_hrd);
        }
    	elseif(Auth::user()->role == 4){
    	    $pelamar = Pelamar::where('id_user','=',Auth::user()->id_user)->first();
            $hrd = HRD::find($pelamar->id_hrd);
        }
        
        // Convert DISC score to JSON
        $array = array(
            'dm' => $request->Dm,
            'im' => $request->Im,
            'sm' => $request->Sm,
            'cm' => $request->Cm,
            'bm' => $request->Bm,
            'dl' => $request->Dl,
            'il' => $request->Il,
            'sl' => $request->Sl,
            'cl' => $request->Cl,
            'bl' => $request->Bl
        );

        // Menyimpan data
        $hasil = new Hasil;
        $hasil->id_hrd = isset($hrd) ? $hrd->id_hrd : 0;
        $hasil->id_user = Auth::user()->id_user;
        $hasil->id_tes = $request->id_tes;
        $hasil->id_paket = $request->id_paket;
        $hasil->hasil = json_encode($array);
        $hasil->test_at = date("Y-m-d H:i:s");
        $hasil->save();
        
        // Message
        $this->message = "Berhasil melakukan tes DISC.";
    }

    /**
     * Proses lembar tes Papikostick...
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storePapikostick(Request $request)
    {
    	// Get data HRD
    	if(Auth::user()->role == 2){
            $hrd = HRD::where('id_user','=',Auth::user()->id_user)->first();
        }
    	elseif(Auth::user()->role == 3){
    	    $karyawan = Karyawan::where('id_user','=',Auth::user()->id_user)->first();
            $hrd = HRD::find($karyawan->id_hrd);
        }
    	elseif(Auth::user()->role == 4){
    	    $pelamar = Pelamar::where('id_user','=',Auth::user()->id_user)->first();
            $hrd = HRD::find($pelamar->id_hrd);
        }
        
        // Declare variables
		$jawaban = $request->get('jawaban');
		$count_jawaban = array_count_values($jawaban);
		$huruf = ["N","G","A","L","P","I","T","V","X","S","B","O","R","D","C","Z","E","K","F","W"];
		$array = array();
		foreach($huruf as $h){
			$array[$h] = array_key_exists($h, $count_jawaban) ? $count_jawaban[$h] : 0;
		}
		
// 		foreach($huruf as $h){
// 		    var_dump($this->analisisPapikostick($array[$h], $this->dataAnalisisPapikostick()[$h]));
// 		}

        // Menyimpan data
        $hasil = new Hasil;
        $hasil->id_hrd = isset($hrd) ? $hrd->id_hrd : 0;
        $hasil->id_user = Auth::user()->id_user;
        $hasil->id_tes = $request->id_tes;
        $hasil->id_paket = $request->id_paket;
        $hasil->hasil = json_encode($array);
        $hasil->test_at = date("Y-m-d H:i:s");
        $hasil->save();
        
        // Message
        $this->message = "Berhasil melakukan tes Papikostick.";
    }

    /**
     * Proses lembar tes SDI...
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeSDI(Request $request)
    {
    	// Get data HRD
    	if(Auth::user()->role == 2){
            $hrd = HRD::where('id_user','=',Auth::user()->id_user)->first();
        }
    	elseif(Auth::user()->role == 3){
    	    $karyawan = Karyawan::where('id_user','=',Auth::user()->id_user)->first();
            $hrd = HRD::find($karyawan->id_hrd);
        }
    	elseif(Auth::user()->role == 4){
    	    $pelamar = Pelamar::where('id_user','=',Auth::user()->id_user)->first();
            $hrd = HRD::find($pelamar->id_hrd);
        }
        
        // Declare variables
        $data = [
            ['Col1a' => $request->a1, 'Col2a' => $request->b1, 'Col3a' => $request->c1],
            ['Col1a' => $request->a2, 'Col2a' => $request->b2, 'Col3a' => $request->c2],
            ['Col1a' => $request->a3, 'Col2a' => $request->b3, 'Col3a' => $request->c3],
            ['Col1a' => $request->a4, 'Col2a' => $request->b4, 'Col3a' => $request->c4],
            ['Col1a' => $request->a5, 'Col2a' => $request->b5, 'Col3a' => $request->c5],
            ['Col1a' => $request->a6, 'Col2a' => $request->b6, 'Col3a' => $request->c6],
            ['Col1a' => $request->a7, 'Col2a' => $request->b7, 'Col3a' => $request->c7],
            ['Col1a' => $request->a8, 'Col2a' => $request->b8, 'Col3a' => $request->c8],
            ['Col1a' => $request->a9, 'Col2a' => $request->b9, 'Col3a' => $request->c9],
            ['Col1a' => $request->a10, 'Col2a' => $request->b10, 'Col3a' => $request->c10],
        ];

        $data2 = [
            ['Col1b' => $request->d1, 'Col2b' => $request->e1, 'Col3b' => $request->f1],
            ['Col1b' => $request->d2, 'Col2b' => $request->e2, 'Col3b' => $request->f2],
            ['Col1b' => $request->d3, 'Col2b' => $request->e3, 'Col3b' => $request->f3],
            ['Col1b' => $request->d4, 'Col2b' => $request->e4, 'Col3b' => $request->f4],
            ['Col1b' => $request->d5, 'Col2b' => $request->e5, 'Col3b' => $request->f5],
            ['Col1b' => $request->d6, 'Col2b' => $request->e6, 'Col3b' => $request->f6],
            ['Col1b' => $request->d7, 'Col2b' => $request->e7, 'Col3b' => $request->f7],
            ['Col1b' => $request->d8, 'Col2b' => $request->e8, 'Col3b' => $request->f8],
            ['Col1b' => $request->d9, 'Col2b' => $request->e9, 'Col3b' => $request->f9],
            ['Col1b' => $request->d10, 'Col2b' => $request->e10, 'Col3b' => $request->f10],
        ];
        
        // Soal 1-10
        $A = 0;
        $B = 0;
        $C = 0;
        foreach($data as $value){
            $A = $A+$value['Col1a'];
            $B = $B+$value['Col2a'];
            $C = $C+$value['Col3a'];
        }

        // Soal 11-20
        $D = 0;
        $E = 0;
        $F = 0;
        foreach($data2 as $value){
            $D = $D+$value['Col1b'];
            $E = $E+$value['Col2b'];
            $F = $F+$value['Col3b'];
        }
        
        // Data to array
        $array = array(
            'A' => $A,
            'B' => $B,
            'C' => $C,
            'D' => $D,
            'E' => $E,
            'F' => $F,
        );

        // Menyimpan data
        $hasil = new Hasil;
        $hasil->id_hrd = isset($hrd) ? $hrd->id_hrd : 0;
        $hasil->id_user = Auth::user()->id_user;
        $hasil->id_tes = $request->id_tes;
        $hasil->id_paket = $request->id_paket;
        $hasil->hasil = json_encode($array);
        $hasil->test_at = date("Y-m-d H:i:s");
        $hasil->save();
        
        // Message
        $this->message = "Berhasil melakukan tes SDI.";
    }
    

    /**
     * Proses lembar tes MSDT...
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeMSDT(Request $request)
    {
    	// Get data HRD
    	if(Auth::user()->role == 2){
            $hrd = HRD::where('id_user','=',Auth::user()->id_user)->first();
        }
    	elseif(Auth::user()->role == 3){
    	    $karyawan = Karyawan::where('id_user','=',Auth::user()->id_user)->first();
            $hrd = HRD::find($karyawan->id_hrd);
        }
    	elseif(Auth::user()->role == 4){
    	    $pelamar = Pelamar::where('id_user','=',Auth::user()->id_user)->first();
            $hrd = HRD::find($pelamar->id_hrd);
        }
        
        // Get tes
        $tes = Tes::where('path','=',$request->path)->first();
        
        // Data soal
        $soal = Soal::join('paket_soal','soal.id_paket','=','paket_soal.id_paket')->where('paket_soal.id_tes','=',$tes->id_tes)->where('status','=',1)->first();
        $array = json_decode($soal->soal, true);
        
        // Get jawaban
        $hasil = $request->get('p');
        foreach($hasil as $key=>$value){
            $array[$key-1]['jawaban'] = $value;
        }

        // Jumlah A
        $arrayA = array();
        for($a=1; $a<=8; $a++){
            $jawabanA = array();
            for($i=(($a-1)*8); $i<($a*8); $i++){
                array_push($jawabanA, $array[$i]['jawaban']);
            }
            $countA = array_count_values($jawabanA);
            $h = array_key_exists('A', $countA) ? $countA['A'] : 0;
            array_push($arrayA, $h);
        }

        // Jumlah B
        $arrayB = array();
        for($b=1; $b<=8; $b++){
            $jawabanB = array();
            for($i=$b; $i<=64; $i+=8){
                array_push($jawabanB, $array[$i-1]['jawaban']);
            }
            $countB = array_count_values($jawabanB);
            $h = array_key_exists('B', $countB) ? $countB['B'] : 0;
            array_push($arrayB, $h);
        }
        
        for($i=1; $i<=8; $i++){
            for($j=(($i-1)*8); $j<($i*8); $j++){
            }
        }

        $koreksi = [
            1 , 2 , 1 , 0 , 3 , -1 , 0 , -4 
        ];

        $jumlah = [

        ];

        for ($i=0 ; $i<8 ; $i++)
        {
            $x = $arrayA[$i] + $arrayB[$i] + $koreksi[$i] ;
            array_push($jumlah,$x) ; 
        }

        $TO = $jumlah[2] + $jumlah[3] + $jumlah[6] + $jumlah[7] ; 
        $RO = $jumlah[1] + $jumlah[3] + $jumlah[5] + $jumlah[7] ; 
        $E = $jumlah[4] + $jumlah[5] + $jumlah[6] + $jumlah[7] ;
        $O = $jumlah[0] ; 
        

        /// MEMBERIKAN NILAI TO , RO , E , O 
        if ($TO >=0 && $TO <=29 ){
            $TO = 0 ; 
        }
        else if ($TO >=30 && $TO <=31){
            $TO = 0.6 ; 
        }
        else if ($TO == 32){
            $TO = 1.2 ; 
        }
        else if ($TO == 33) {
            $TO = 1.8 ; 
        }
        else if ($TO == 34){
            $TO = 2.4 ; 
        }
        else if ($TO == 35)
        {
            $TO = 3.0 ; 
        } 
        else if ($TO >=36 && $TO <= 37)
        {
            $TO = 3.6 ;
        }
        else
        {
            $TO = 4.0 ; 
        }

        
        if ($RO >=0 && $RO <=29 ){
            $RO = 0 ; 
        }
        else if ($RO >=30 && $RO <=31){
            $RO = 0.6 ; 
        }
        else if ($RO == 32){
            $RO = 1.2 ; 
        }
        else if ($RO == 33) {
            $RO = 1.8 ; 
        }
        else if ($RO == 34){
            $RO = 2.4 ; 
        }
        else if ($RO == 35)
        {
            $RO = 3.0 ; 
        } 
        else if ($RO >=36 && $RO <= 37)
        {
            $RO = 3.6 ;
        }
        else
        {
            $RO = 4.0 ; 
        }

        
        if ($E >=0 && $E <=29 ){
            $E = 0 ; 
        }
        else if ($E >=30 && $E <=31){
            $E = 0.6 ; 
        }
        else if ($E == 32){
            $E = 1.2 ; 
        }
        else if ($E == 33) {
            $E = 1.8 ; 
        }
        else if ($E == 34){
            $E = 2.4 ; 
        }
        else if ($E == 35)
        {
            $E = 3.0 ; 
        } 
        else if ($E >=36 && $E <= 37)
        {
            $E = 3.6 ;
        }
        else
        {
            $E = 4.0 ; 
        }

        if ($O >=0 && $O <=29 ){
            $O = 0 ; 
        }
        else if ($O >=30 && $O <=31){
            $O = 0.6 ; 
        }
        else if ($O == 32){
            $O = 1.2 ; 
        }
        else if ($O == 33) {
            $O = 1.8 ; 
        }
        else if ($O == 34){
            $O = 2.4 ; 
        }
        else if ($O == 35)
        {
            $O = 3.0 ; 
        } 
        else if ($O >=36 && $O <= 37)
        {
            $O = 3.6 ;
        }
        else
        {
            $O = 4.0 ; 
        }
        
        $final = ""; 
        
        if($TO > 2){
            if($RO > 2){
                if($E > 2){
                    $final = "Executive" ; 
                }
                else {
                    $final = "Compromiser" ;
                }
            }
            else
            {
                if($E > 2){
                    $final = "Benevolent Autocrat" ;
                }
                else{
                    $final = "Autocrat" ;
                }   
            }
        }
        else{
            if($RO > 2){
                if($E > 2){
                    $final = "Developer" ;
                }
                else
                {
                    $final = "Missionary" ;
                }
            }
            else
            {
                if($E > 2){
                    $final = "Bereaucrat" ;
                }
                else
                {
                    $final = "Deserter" ;
                }   
            }
        }
        
        // Result
        $result = array(
            'TO' => round($TO, 2),
            'RO' => round($RO, 2),
            'E' => round($E, 2),
            'O' => round($O, 2),
            'tipe' => $final,
        );

        // Menyimpan data
        $hasil = new Hasil;
        $hasil->id_hrd = isset($hrd) ? $hrd->id_hrd : 0;
        $hasil->id_user = Auth::user()->id_user;
        $hasil->id_tes = $request->id_tes;
        $hasil->id_paket = $request->id_paket;
        $hasil->hasil = json_encode($result);
        $hasil->test_at = date("Y-m-d H:i:s");
        $hasil->save();
        
        // Message
        $this->message = "Berhasil melakukan tes MSDT.";
    }
    
    public function data_sdi(){
        
        $soal1 = array(
            array('id' => '1', 'header' => 'Saya sangat menikmati sesuatu ketika saya ....',
            'val1' => 'a1' ,'soal1' => 'Membantu orang lain melakukan apa yang ingin mereka lakukan',
            'val2' => 'b1', 'soal2' => 'Meminta orang lain untuk melakukan apa yang ingin saya lakukan',
            'val3' => 'c1', 'soal3' => 'Melakukan apa yang ingin saya lakukan tanpa harus bergantung pada orang lain'),

            array('id' => '2', 'header' => 'Hampir tiap waktu saya nampaknya seperti ....',
            'val1' => 'a2', 'soal1' => 'Seseorang yang peka yang cepat merespon kebutuhan orang lain',
            'val2' => 'b2', 'soal2' => 'Seseorang yang enerjik cepat melihat peluang dan keuntungan',
            'val3' => 'c2', 'soal3' => 'Seseorang yang praktis yang tclassak tergesa-gesa terhadap sesuatu sebelum saya siap'),

            array('id' => '3', 'header' => 'Ketika saya bertemu seseorang untuk pertama kali, saya sering bersikap seperti ....',
            'val1' => 'a3', 'soal1' => 'Peduli dengan apakah mereka akap menganggap saya orang yang menyenangkan atau tidak',
            'val2' => 'b3', 'soal2' => 'Sangat ingin tahu dari mereka jika ada sesuatu tentang saya',
            'val3' => 'c3', 'soal3' => 'Berhati-hati sampai saya mempelajari apa yang mungkin mereka inginkan dari saya'),

            array('id' => '4', 'header' => 'Hampir setiap waktu saya menemukan diri saya seperti ....',
            'val1' => 'a4', 'soal1' => 'Seorang yang menyenangkan, yang dapat diandalkan orang lain',
            'val2' => 'b4', 'soal2' => 'Seorang yang kuat, yang memberikan pengarahan untuk orang lain',
            'val3' => 'c4', 'soal3' => 'Seorang pemikir, yang mempelajari sesuatu sebelum bertindak'),

            array('id' => '5', 'header' => 'Saya merasa sangat puas, ketika ....',
            'val1' => 'a5', 'soal1' => 'Keputusan terbesar telah dibuat oleh orang lain, dan bagaimana saya dapat membantu agar selesai',
            'val2' => 'b5', 'soal2' => 'Orang lain mengandalkan saya untuk membuat keputusan besar dan mengajarkan mereka apa yang harus dilakukan',
            'val3' => 'c5', 'soal3' => 'Saya telah mengambil waktu untuk mempelajari sebuah keputusan besar dan mengukur arah tindakan terbaik saya'),

            array('id' => '6', 'header' => 'Orang-orang yang mengenal saya dengan baik, melihat saya sebagai seseorang yang bisa diandalkan ....',
            'val1' => 'a6', 'soal1' => 'Untuk dapat mereka percaya dan setia',
            'val2' => 'b6', 'soal2' => 'Untuk ambisi yang tinggi dan inisiatif',
            'val3' => 'c6', 'soal3' => 'Untuk ketegasan dalam keyakinan dan pendirian saya'),

            array('id' => '7', 'header' => 'Saya sangat menyukai untuk ....',
            'val1' => 'a7', 'soal1' => 'Melakukan yang terbaik saya bisa dan mempercayai orang lain untuk mengakui kontribusi saya',
            'val2' => 'b7', 'soal2' => 'Mengambil peran utama dalam membangun peluang dan mempengaruhi keputusan',
            'val3' => 'c7', 'soal3' => 'Sabar, praktis dan yakin terhadap apa yang saya lakukan'),

            array('id' => '8', 'header' => 'Saya akan menggambarkan diri saya sebagai seseorang yang setiap saat ....',
            'val1' => 'a8', 'soal1' => 'Ramah, terbuka dan seseorang yang melihat hal baik baik pada hampir setiap orang',
            'val2' => 'b8', 'soal2' => 'Enerjik, percaya diri, dan melihat kesempatan yang orang lain tclassak lihat',
            'val3' => 'c8', 'soal3' => 'Berhati-hati dan adil, dan orang yang berdiri pada apa yang dipercayainya'),

            array('id' => '9', 'header' => 'Saya menemukan banyak hubungan yang menyenangkan dimana saya dapat menjadi ....',
            'val1' => 'a9', 'soal1' => 'Dukungan untuk seorang pemimpin yang kuat yang saya percaya',
            'val2' => 'b9', 'soal2' => 'Seseorang yang mampu menjadi pemimpin yang ingin dijadikan panutan bagi orang lain',
            'val3' => 'c9', 'soal3' => 'Seorang pemimpin atau bukan, tapi bebas untuk mengejar kebebasan saya sendiri'),

            array('id' => '10', 'header' => 'Ketika saya dalam keadaan terbaik saya, saya sangat menikmati ....',
            'val1' => 'a10', 'soal1' => 'Melihat manfaat bagi orang lain, dari apa yang saya telah mampu lakukan untuk mereka',
            'val2' => 'b10', 'soal2' => 'Orang lain menunjuk saya untuk memimpin dan mengarahkan mereka dan memberi mereka makna',
            'val3' => 'c10', 'soal3' => 'Menjadi bos untuk diri sendiri dan melakukan sesuatu untuk  diri sendiri dan oleh diri sendiri'),
        );

        $soal2 = array(
            array('id' => '1', 'header' => 'Ketika menjumpai lingkungan yang berseberangan dengan apa
            yang saya lakukan, saya seringkali bersikap ....',
            'val1' => 'd1' ,'soal1' => 'Menghentikan apa yang saya lakukan dan mengesampingkan
            keinginan saya agar lebih membantu',
            'val2' => 'e1', 'soal2' => 'Agresif dan mengusahakan hak saya untuk melakukannya',
            'val3' => 'f1', 'soal3' => 'Menjadi lebih berhati-hati dan mengawasi posisi saya
            dengan sangat hati-hati'),

            array('id' => '2', 'header' => 'Jika saya memutuskan untuk mengalahkan seseorang, saya
            mencoba untuk ....',
            'val1' => 'd2' ,'soal1' => 'Merubah apa yang saya lakukan dan mencoba membuat agar
            lebih diterima orang',
            'val2' => 'e2', 'soal2' => 'Mencari kelemahan pada argumen orang lain dan
            menekankan nilai yang kuat pada argument sendiri',
            'val3' => 'f2', 'soal3' => 'Menampakkan rasa respek untuk bersaing secara logis dan
            adil'),

            array('id' => '3', 'header' => 'Saat bergaul dengan orang yang sulit, saya biasanya ....',
            'val1' => 'd3' ,'soal1' => 'Menganggapnya mudah dan mengiyakan harapan-harapan
            mereka untuk sementara',
            'val2' => 'e3', 'soal2' => 'Menganggap mereka sebagai tantangan untuk dikalahkan',
            'val3' => 'f3', 'soal3' => 'Menghormati hak mereka dan meminta mereka juga
            menghormati hak dan kepentingan saya'),

            array('id' => '4', 'header' => 'Ketika seseorang sangat tclassak setuju dengan saya, saya
            cenderung ....',
            'val1' => 'd4' ,'soal1' => 'Menyerah dan mengikuti cara orang itu kecuali itu
            sangat penting bagi saya',
            'val2' => 'e4', 'soal2' => 'Segera menantang orang tersebut dan berdebat sekeras
            mungkin',
            'val3' => 'f4', 'soal3' => 'Memisahkan diri dari situasi tersebut sampai akhirnya
            saya yakin akan posisi saya'),

            array('id' => '5', 'header' => 'Ketika seseorang secara terbuka melawan saya, saya biasanya ....',
            'val1' => 'd5' ,'soal1' => 'Menyerah demi keharmonisan dan mengandalkan kepekaan
            orang lain untuk melakukan hal yang benar atas saya',
            'val2' => 'e5', 'soal2' => 'Menerima kenyataan bahwa ini adalah perang dan
            menyiapkan diri untuk menang',
            'val3' => 'f5', 'soal3' => 'Berusaha menarik diri dari pergaulan tersebut dan
            mencari yang sesuai dengan saya'),

            array('id' => '6', 'header' => 'Jika saya tclassak mendapatkan apa yang saya inginkan dalam
            sebuah hubungan, saya biasanya bersikap ....',
            'val1' => 'd6' ,'soal1' => 'Tetap berharap dan percaya sesuatu akan merubah mereka
            seiring berjalannya waktu',
            'val2' => 'e6', 'soal2' => 'Menjadi lebih agresif dan persuasif, & lebih berusaha
            keras untuk mendapatkan apa yang saya mau',
            'val3' => 'f6', 'soal3' => 'Mengabaikan hubungan itu dan mencari yang lain untuk
            apa yang saya mau'),

            array('id' => '7', 'header' => 'Ketika saya merasa orang lain mengambil manfaat dari saya,
            saya biasanya ....',
            'val1' => 'd7' ,'soal1' => 'Berbalik pada seseorang yang memiliki pengalaman lebih
            dan meminta saran mereka',
            'val2' => 'e7', 'soal2' => 'Menegaskan hak saya dan berjuang atas apa yang berhak
            untuk saya',
            'val3' => 'f7', 'soal3' => 'Menyatakan hak-hak saya dengan jelas, dan berpegang
            teguh pada kejujuran di sekitar kita'),

            array('id' => '8', 'header' => 'Ketika orang lain bersikeras pada jalan mereka sendiri,
            saya cenderung ....',
            'val1' => 'd8' ,'soal1' => 'Mengesampingkan keinginan saya sementara waktu dan
            mengikuti keinginan mereka',
            'val2' => 'e8', 'soal2' => 'Membalas argumennya dan berusaha untuk merubah pikiran
            orang itu',
            'val3' => 'f8', 'soal3' => 'Menghormati hak orang itu untuk menentukan jalannya
            sendiri, sepanjang tclassak berkaitan dengan saya'),

            array('id' => '9', 'header' => 'Ketika orang lain secara terbuka mengkritik saya, saya
            seringkali bersikap ....',
            'val1' => 'd9' ,'soal1' => 'Menenangkan mereka, dan meredakan amarah mereka
            terhadap saya',
            'val2' => 'e9', 'soal2' => 'Marah dan tantang apa hak mereka untuk mengkritisi',
            'val3' => 'f9', 'soal3' => 'Menjadi lebih waspada dan menganalisa setiap kritik
            dengan detail'),

            array('id' => '10', 'header' => 'Ketika seseorang dengan jelas menyalahgunakan kepercayaan
            saya, saya cenderung ....',
            'val1' => 'd10' ,'soal1' => 'Merasa tindakan itu lebih menyakitkan untuk diri mereka
            sendiri daripada dampak pada saya',
            'val2' => 'e10', 'soal2' => 'Marah pada orang itu dan jika perlu mengambil langkah
            untuk membalasnya',
            'val3' => 'f10', 'soal3' => 'Menganalisa apa yang salah dan menghindari hal serupa
            terulang di masa depan'),
        );
        
        return array(
            'soal1' => $soal1,
            'soal2' => $soal2,
        );
    }
    
    public function data_papikostick(){
        $data = array(
            // Nomor 1
            array(
                'soalA' => 'Saya seorang pekerja <i><u>“keras”</u></i>',
                'soalB' => 'Saya <i><u>bukan</u></i> seorang pemurung',
                'jawabanA' => 'G',
                'jawabanB' => 'E',
            ),
            // Nomor 2
            array(
                'soalA' => 'Saya suka bekerja <i><u>lebih baik</u></i> dari orang lain',
                'soalB' => 'Saya suka mengerjakan <i><u>apa yang sedang saya kerjakan</u></i>, sampai selesai',
                'jawabanA' => 'A',
                'jawabanB' => 'N',
            ),
            // Nomor 3
            array(
                'soalA' => 'Saya suka <i><u>menunjukkan caranya</u></i> melaksanakan sesuatu hal',
                'soalB' => 'Saya ingin bekerja <i><u>sebaik mungkin</u></i>',
                'jawabanA' => 'P',
                'jawabanB' => 'A',
            ),
            // Nomor 4
            array(
                'soalA' => 'Saya suka <i><u>berkelakar</u></i>',
                'soalB' => 'Saya senang <i><u>mengatakan kepada orang lain, apa yang harus dilakukannya</u></i>',
                'jawabanA' => 'X',
                'jawabanB' => 'P',
            ),
            // Nomor 5
            array(
                'soalA' => 'Saya suka <i><u>menggabungkan diri</u></i> dengan kelompok-kelompok',
                'soalB' => 'Saya suka <i><u>diperhatikan</u></i> oleh kelompok-kelompok',
                'jawabanA' => 'B',
                'jawabanB' => 'X',
            ),
            // Nomor 6
            array(
                'soalA' => 'Saya senang <i><u>bersahabat intim</u></i> dengan seseorang',
                'soalB' => 'Saya senang bersahabat dengan <i><u>sekelompok orang</u></i>',
                'jawabanA' => 'O',
                'jawabanB' => 'B',
            ),
            // Nomor 7
            array(
                'soalA' => 'Saya cepat <i><u>berubah</u></i> bila hal itu diperlukan',
                'soalB' => 'Saya berusaha untuk <i><u>intim dengan teman-teman</u></i>',
                'jawabanA' => 'Z',
                'jawabanB' => 'O',
            ),
            // Nomor 8
            array(
                'soalA' => 'Saya suka <i><u>“membalas dendam”</u></i> bila saya benar-benar disakiti',
                'soalB' => 'Saya suka melakukan hal-hal yang <i><u>baru dan berbeda</u></i>',
                'jawabanA' => 'K',
                'jawabanB' => 'Z',
            ),
            // Nomor 9
            array(
                'soalA' => 'Saya ingin <i><u>atasan</u></i> saya menyukai saya',
                'soalB' => 'Saya suka <i><u>mengatakan kepada orang lain</u></i>, bila mereka salah',
                'jawabanA' => 'F',
                'jawabanB' => 'K',
            ),
            // Nomor 10
            array(
                'soalA' => 'Saya suka <i><u>mengikuti</u></i> perintah-perintah yang diberikan kepada saya',
                'soalB' => 'Saya suka menyenangkan hati <i><u>orang yang memimpin saya</u></i>',
                'jawabanA' => 'W',
                'jawabanB' => 'F',
            ),
            // Nomor 11
            array(
                'soalA' => 'Saya mencoba <i><u>sekuat tenaga</u></i>',
                'soalB' => 'Saya seorang yang <i><u>tertib</u></i>. Saya meletakan segala sesuatu pada tempatnya',
                'jawabanA' => 'G',
                'jawabanB' => 'C',
            ),
            // Nomor 12
            array(
                'soalA' => 'Saya membuat orang lain melakukan apa yang <i><u>saya</u></i> inginkan',
                'soalB' => 'Saya <i><u>bukan</u></i> orang yang cepat gusar',
                'jawabanA' => 'L',
                'jawabanB' => 'E',
            ),
            // Nomor 13
            array(
                'soalA' => '<i><u>Saya</u></i> suka mengatakan kepada kelompok, apa yang harus saya lakukan',
                'soalB' => 'Saya <i><u>menekuni</u></i> satu pekerjaan sampai selesai',
                'jawabanA' => 'P',
                'jawabanB' => 'N',
            ),
            // Nomor 14
            array(
                'soalA' => 'Saya ingin tampak <i><u>bersemangat dan menarik</u></i>',
                'soalB' => 'Saya ingin menjadi <i><u>sangat sukses</u></i>',
                'jawabanA' => 'X',
                'jawabanB' => 'A',
            ),
            // Nomor 15
            array(
                'soalA' => 'Saya suka <i><u>menyelaraskan diri</u></i> dengan kelompok',
                'soalB' => 'Saya suka <i><u>membantu orang lain</u></i> menentukan pendapatnya',
                'jawabanA' => 'B',
                'jawabanB' => 'P',
            ),
            // Nomor 16
            array(
                'soalA' => 'Saya cemas kalau orang lain <i><u>tidak menyukai saya</u></i>',
                'soalB' => 'Saya senang kalau orang-orang <i><u>memperhatikan</u></i> saya',
                'jawabanA' => 'O',
                'jawabanB' => 'X',
            ),
            // Nomor 17
            array(
                'soalA' => 'Saya suka mencoba <i><u>sesuatu yang baru</u></i>',
                'soalB' => 'Saya lebih suka bekerja <i><u>bersama orang-orang</u></i> daripada bekerja sendiri',
                'jawabanA' => 'Z',
                'jawabanB' => 'B',
            ),
            // Nomor 18
            array(
                'soalA' => 'Kadang-kadang saya <i><u>menyalahkan orang lain</u></i> bila terjadi sesuatu kesalahan',
                'soalB' => 'Saya cemas bila <i><u>seseorang tidak menyukai</u></i> saya',
                'jawabanA' => 'K',
                'jawabanB' => 'O',
            ),
            // Nomor 19
            array(
                'soalA' => 'Saya suka <i><u>menyenangkan hati</u></i> orang yang memimpin saya',
                'soalB' => 'Saya suka mencoba pekerjaan-pekerjaan yang <i><u>baru dan berbeda</u></i>',
                'jawabanA' => 'F',
                'jawabanB' => 'Z',
            ),
            // Nomor 20
            array(
                'soalA' => 'Saya menyukai <i><u>petunjuk</u></i> yang terinci untuk melakukan sesuatu pekerjaan',
                'soalB' => 'Saya suka mengatakan kepada orang lain bila <i><u>mereka menganggu saya</u></i>',
                'jawabanA' => 'W',
                'jawabanB' => 'K',
            ),
            // Nomor 21
            array(
                'soalA' => 'Saya selalu mencoba <i><u>sekuat tenaga</u></i>',
                'soalB' => 'Saya senang bekerja dengan sangat <i><u>cermat dan hati-hati</u></i>',
                'jawabanA' => 'G',
                'jawabanB' => 'D',
            ),
            // Nomor 22
            array(
                'soalA' => 'Saya adalah seorang pemimpin yang <i><u>baik</u></i>',
                'soalB' => 'Saya mengorganisir <i><u>tugas-tugas secara baik</u></i>',
                'jawabanA' => 'L',
                'jawabanB' => 'C',
            ),
            // Nomor 23
            array(
                'soalA' => 'Saya mudah menjadi <i><u>gusar</u></i>',
                'soalB' => 'Saya seorang yang <i><u>lambat</u></i> dalam membuat keputusan',
                'jawabanA' => 'I',
                'jawabanB' => 'E',
            ),
            // Nomor 24
            array(
                'soalA' => 'Saya senang mengerjakan <i><u>beberapa pekerjaan</u></i> pada waktu yang bersamaan',
                'soalB' => 'Bila di dalam kelompok, saya lebih suka <i><u>diam</u></i>',
                'jawabanA' => 'X',
                'jawabanB' => 'N',
            ),
            // Nomor 25
            array(
                'soalA' => 'Saya senang bila <i><u>diundang</u></i>',
                'soalB' => 'Saya ingin melakukan sesuatu <i><u>lebih baik</u></i> dari orang lain',
                'jawabanA' => 'B',
                'jawabanB' => 'A',
            ),
            // Nomor 26
            array(
                'soalA' => 'Saya suka berteman <i><u>intim</u></i> dengan teman-teman saya',
                'soalB' => 'Saya suka memberi <i><u>nasehat</u></i> kepada orang lain',
                'jawabanA' => 'O',
                'jawabanB' => 'P',
            ),
            // Nomor 27
            array(
                'soalA' => 'Saya suka melakukan hal-hal yang <i><u>baru dan berbeda</u></i>',
                'soalB' => 'Saya <i><u>suka menceritakan keberhasilan saya</u></i> dalam mengerjakan tugas',
                'jawabanA' => 'Z',
                'jawabanB' => 'X',
            ),
            // Nomor 28
            array(
                'soalA' => 'Bila saya benar, saya suka mempertahankannya <i><u>“mati-matian”</u></i>',
                'soalB' => 'Saya suka <i><u>bergabung ke dalam</u></i> suatu kelompok',
                'jawabanA' => 'K',
                'jawabanB' => 'B',
            ),
            // Nomor 29
            array(
                'soalA' => 'Saya tidak mau <i><u>berbeda</u></i> dengan orang lain',
                'soalB' => 'Saya berusaha untuk sangat <i><u>intim</u></i> dengan orang-orang',
                'jawabanA' => 'F',
                'jawabanB' => 'O',
            ),
            // Nomor 30
            array(
                'soalA' => 'Saya suka <i><u>diajari mengenai caranya mengerjakan</u></i> suatu pekerjaan',
                'soalB' => 'Saya mudah merasa <i><u>jemu</u></i>( bosan )',
                'jawabanA' => 'W',
                'jawabanB' => 'Z',
            ),
            // Nomor 31
            array(
                'soalA' => 'Saya bekerja <i><u>“keras”</u></i>',
                'soalB' => 'Saya banyak <i><u>berfikir dan berencana</u></i>',
                'jawabanA' => 'G',
                'jawabanB' => 'R',
            ),
            // Nomor 32
            array(
                'soalA' => 'Saya <i><u>memimpin</u></i> kelompok',
                'soalB' => 'Hal-hal yang kecil (detail) <i><u>menarik hati</u></i> saya',
                'jawabanA' => 'L',
                'jawabanB' => 'D',
            ),
            // Nomor 33
            array(
                'soalA' => 'Saya <i><u>cepat dan mudah</u></i> mengambil keputusan',
                'soalB' => 'Saya meletakkan segala sesuatu secara <i><u>rapih dan teratur</u></i>',
                'jawabanA' => 'I',
                'jawabanB' => 'C',
            ),
            // Nomor 34
            array(
                'soalA' => 'Tugas-tugas saya kerjakan secara <i><u>cepat</u></i>',
                'soalB' => 'Saya jarang <i><u>marah atau sedih</u></i>',
                'jawabanA' => 'T',
                'jawabanB' => 'E',
            ),
            // Nomor 35
            array(
                'soalA' => 'Saya ingin menjadi bagian dari <i><u>kelompok</u></i>',
                'soalB' => 'Pada suatu waktu tertentu, saya hanya ingin mengerjakan <i><u>satu</u></i> tugas saja',
                'jawabanA' => 'B',
                'jawabanB' => 'N',
            ),
            // Nomor 36
            array(
                'soalA' => 'Saya berusaha untuk <i><u>intim dengan teman-teman saya</u></i>',
                'soalB' => 'Saya berusaha keras untuk menjadi yang <i><u>terbaik</u></i>',
                'jawabanA' => 'O',
                'jawabanB' => 'A',
            ),
            // Nomor 37
            array(
                'soalA' => 'Saya menyukai model baju <i><u>baru</u></i> dan tipe-tipe mobil <i><u>baru</u></i>',
                'soalB' => 'Saya ingin menjadi <i><u>penanggung jawab</u></i> bagi orang-orang lain',
                'jawabanA' => 'Z',
                'jawabanB' => 'P',
            ),
            // Nomor 38
            array(
                'soalA' => 'Saya suka <i><u>berdebat</u></i>',
                'soalB' => 'Saya ingin <i><u>diperhatikan</u></i>',
                'jawabanA' => 'K',
                'jawabanB' => 'X',
            ),
            // Nomor 39
            array(
                'soalA' => 'Saya suka <i><u>menyenangkan hati</u></i> orang yang memimpin saya',
                'soalB' => 'Saya tertarik <i><u>menjadi anggota</u></i> dari suatu kelompok',
                'jawabanA' => 'F',
                'jawabanB' => 'B',
            ),
            // Nomor 40
            array(
                'soalA' => 'Saya senang <i><u>mengikuti</u></i> aturan secara tertib',
                'soalB' => 'Saya suka orang-orang <i><u>mengenal saya benar-benar</u></i>',
                'jawabanA' => 'W',
                'jawabanB' => 'O',
            ),
            // Nomor 41
            array(
                'soalA' => 'Saya mencoba <i><u>sekuat tenaga</u></i>',
                'soalB' => 'Saya sangat <i><u>menyenangkan</u></i>',
                'jawabanA' => 'G',
                'jawabanB' => 'S',
            ),
            // Nomor 42
            array(
                'soalA' => 'Orang lain beranggapan bahwa saya adalah seorang <i><u>pemimpin yang baik</u></i>',
                'soalB' => 'Saya berpikir <i><u>jauh ke depan dan terinci</u></i>',
                'jawabanA' => 'L',
                'jawabanB' => 'R',
            ),
            // Nomor 43
            array(
                'soalA' => 'Seringkali saya <i><u>memanfaatkan peluang</u></i>',
                'soalB' => 'Saya senang <i><u>memperhatikan</u></i> hal-hal sampai sekecil-kecilnya',
                'jawabanA' => 'I',
                'jawabanB' => 'D',
            ),
            // Nomor 44
            array(
                'soalA' => 'Orang lain menganggap saya <i><u>bekerja cepat</u></i>',
                'soalB' => 'Orang lain menganggap saya dapat melakukan penataan yang <i><u>rapih dan teratur</u></i>',
                'jawabanA' => 'T',
                'jawabanB' => 'C',
            ),
            // Nomor 45
            array(
                'soalA' => 'Saya menyukai <i><u>permainan-permainan dan olahraga</u></i>',
                'soalB' => 'Saya sangat <i><u>menyenangkan</u></i>',
                'jawabanA' => 'V',
                'jawabanB' => 'E',
            ),
            // Nomor 46
            array(
                'soalA' => 'Saya senang bila orang-orang <i><u>dapat intim dan bersahabat</u></i>',
                'soalB' => 'Saya selalu berusaha <i><u>menyelesaikan apa yang telah saya mulai</u></i>',
                'jawabanA' => 'O',
                'jawabanB' => 'N',
            ),
            // Nomor 47
            array(
                'soalA' => 'Saya suka <i><u>bereksperimen dan mencoba sesuatu yang baru</u></i>',
                'soalB' => 'Saya suka mengerjakan <i><u>pekerjaan-pekerjaan yang sulit dengan baik</u></i>',
                'jawabanA' => 'Z',
                'jawabanB' => 'A',
            ),
            // Nomor 48
            array(
                'soalA' => 'Saya senang diperlakukan secara <i><u>adil</u></i>',
                'soalB' => 'Saya senang mengajari <i><u>orang lain</u></i> bagaimana caranya mengerjakan sesuatu',
                'jawabanA' => 'K',
                'jawabanB' => 'P',
            ),
            // Nomor 49
            array(
                'soalA' => 'Saya suka mengerjakan apa yang <i><u>diharapkan</u></i> dari saya',
                'soalB' => 'Saya suka menarik <i><u>perhatian</u></i>',
                'jawabanA' => 'F',
                'jawabanB' => 'X',
            ),
            // Nomor 50
            array(
                'soalA' => 'Saya suka petunjuk-petunjuk <i><u>terinci</u></i> dalam melaksanakan suatu pekerjaan',
                'soalB' => 'Saya senang <i><u>berada bersama dengan</u></i> orang-orang lain',
                'jawabanA' => 'W',
                'jawabanB' => 'B',
            ),
            // Nomor 51
            array(
                'soalA' => 'Saya selalu berusaha mengerjakan tugas secara <i><u>sempurna</u></i>',
                'soalB' => 'Orang lain menganggap, saya <i><u>tidak mengenal</u></i> Lelah, dalam kerja sehari-hari',
                'jawabanA' => 'G',
                'jawabanB' => 'V',
            ),
            // Nomor 52
            array(
                'soalA' => 'Saya tergolong tipe <i><u>pemimpin</u></i>',
                'soalB' => 'Saya <i><u>mudah</u></i> berteman',
                'jawabanA' => 'L',
                'jawabanB' => 'S',
            ),
            // Nomor 53
            array(
                'soalA' => 'Saya memanfaatkan <i><u>peluang-peluang</u></i>',
                'soalB' => 'Saya banyak <i><u>berfikir</u></i>',
                'jawabanA' => 'I',
                'jawabanB' => 'R',
            ),
            // Nomor 54
            array(
                'soalA' => 'Saya bekerja dengan kecepatan yang <i><u>mantap dan cepat</u></i>',
                'soalB' => 'Saya senang mengerjakan hal-hal yang <i><u>detail</u></i>',
                'jawabanA' => 'T',
                'jawabanB' => 'D',
            ),
            // Nomor 55
            array(
                'soalA' => 'Saya memiliki banyak <i><u>energi</u></i> untuk permainan-permainan dan olahraga',
                'soalB' => 'Saya menempatkan segala sesuatunya secara <i><u>rapih dan teratur</u></i>',
                'jawabanA' => 'V',
                'jawabanB' => 'C',
            ),
            // Nomor 56
            array(
                'soalA' => 'Saya bergaul baik dengan <i><u>semua</u></i> orang',
                'soalB' => 'Saya <i><u>pandai mengendalikan diri</u></i>',
                'jawabanA' => 'S',
                'jawabanB' => 'E',
            ),
            // Nomor 57
            array(
                'soalA' => 'Saya ingin berkenalan dengan orang-orang <i><u>baru</u></i> dan mengerjakan hal baru',
                'soalB' => 'Saya selalu ingin <i><u>menyelesaikan</u></i> pekerjaan yang sudah saya mulai',
                'jawabanA' => 'Z',
                'jawabanB' => 'N',
            ),
            // Nomor 58
            array(
                'soalA' => 'Biasanya saya <i><u>bersikeras</u></i> mengenai apa yang saya yakini',
                'soalB' => 'Biasanya saya suka bekerja <i><u>“keras”</u></i>',
                'jawabanA' => 'K',
                'jawabanB' => 'A',
            ),
            // Nomor 59
            array(
                'soalA' => 'Saya menyukai <i><u>saran-saran</u></i> dari orang yang saya kagumi',
                'soalB' => 'Saya senang <i><u>mengatur</u></i> orang lain',
                'jawabanA' => 'F',
                'jawabanB' => 'P',
            ),
            // Nomor 60
            array(
                'soalA' => 'Saya biarkan orang-orang lain <i><u>mempengaruhi</u></i> saya',
                'soalB' => 'Saya suka menerima banyak <i><u>perhatian</u></i>',
                'jawabanA' => 'W',
                'jawabanB' => 'X',
            ),
            // Nomor 61
            array(
                'soalA' => 'Biasanya saya bekerja sangat <i><u>“keras”</u></i>',
                'soalB' => 'Biasanya saya bekerja <i><u>cepat</u></i>',
                'jawabanA' => 'G',
                'jawabanB' => 'T',
            ),
            // Nomor 62
            array(
                'soalA' => 'Bila saya berbicara, kelompok akan <i><u>mendengarkan</u></i>',
                'soalB' => 'Saya <i><u>terampil</u></i> mempergunakan alat-alat kerja',
                'jawabanA' => 'L',
                'jawabanB' => 'V',
            ),
            // Nomor 63
            array(
                'soalA' => 'Saya <i><u>lambat</u></i> membina persahabatan',
                'soalB' => 'Saya <i><u>lambat</u></i> dalam mengambil keputusan',
                'jawabanA' => 'I',
                'jawabanB' => 'S',
            ),
            // Nomor 64
            array(
                'soalA' => 'Biasanya saya makan secara <i><u>cepat</u></i>',
                'soalB' => 'Saya suka <i><u>membaca</u></i>',
                'jawabanA' => 'T',
                'jawabanB' => 'R',
            ),
            // Nomor 65
            array(
                'soalA' => 'Saya menyukai pekerjaan yang memungkinkan saya <i><u>“berkeliling”</u></i>',
                'soalB' => 'Saya menyukai pekerjaan yang harus dilakukan secara <i><u>teliti</u></i>',
                'jawabanA' => 'V',
                'jawabanB' => 'D',
            ),
            // Nomor 66
            array(
                'soalA' => 'Saya berteman <i><u>sebanyak</u></i> mungkin',
                'soalB' => 'Saya dapat <i><u>menemukan</u></i> hal-hal yang telah saya pindahkan',
                'jawabanA' => 'S',
                'jawabanB' => 'C',
            ),
            // Nomor 67
            array(
                'soalA' => 'Perencanaan saya <i><u>jauh ke masa depan</u></i>',
                'soalB' => 'Saya selalu <i><u>menyenangkan</u></i>',
                'jawabanA' => 'R',
                'jawabanB' => 'E',
            ),
            // Nomor 68
            array(
                'soalA' => 'Saya merasa <i><u>bangga</u></i> akan nama baik saya',
                'soalB' => 'Saya selalu <i><u>menyenangkan</u></i>',
                'jawabanA' => 'K',
                'jawabanB' => 'N',
            ),
            // Nomor 69
            array(
                'soalA' => 'Saya suka <i><u>menyenangkan hati</u></i> orang-orang yang saya <i><u>kagumi</u></i>',
                'soalB' => 'Saya suka menjadi orang yang <i><u>berhasil</u></i>',
                'jawabanA' => 'F',
                'jawabanB' => 'A',
            ),
            // Nomor 70
            array(
                'soalA' => 'Saya senang bila <i><u>orang-orang lain mengambil keputusan</u></i> untuk kelompok',
                'soalB' => '<i><u>Saya</u></i> suka mengambil keputusan untuk kelompok',
                'jawabanA' => 'W',
                'jawabanB' => 'P',
            ),
            // Nomor 71
            array(
                'soalA' => 'Saya selalu berusaha sangat <i><u>“keras”</u></i>',
                'soalB' => 'Saya <i><u>cepat dan mudah</u></i> mengambil keputusan',
                'jawabanA' => 'G',
                'jawabanB' => 'I',
            ),
            // Nomor 72
            array(
                'soalA' => 'Biasanya kelompok saya mengerjakan hal-hal yang <i><u>saya</u></i> inginkan',
                'soalB' => 'Biasanya saya <i><u>tergesa-gesa</u></i>',
                'jawabanA' => 'L',
                'jawabanB' => 'T',
            ),
            // Nomor 73
            array(
                'soalA' => 'Saya seringkali merasa <i><u>lelah</u></i>',
                'soalB' => 'Saya <i><u>lambat</u></i> dalam mengambil keputusan',
                'jawabanA' => 'I',
                'jawabanB' => 'V',
            ),
            // Nomor 74
            array(
                'soalA' => 'Saya bekerja secara <i><u>cepat</u></i>',
                'soalB' => 'Saya <i><u>mudah</u></i> mendapat kawan',
                'jawabanA' => 'T',
                'jawabanB' => 'S',
            ),
            // Nomor 75
            array(
                'soalA' => 'Biasanya saya <i><u>bersemangat atau bergairah</u></i>',
                'soalB' => 'Sebagian besar waktu saya untuk <i><u>berpikir</u></i>',
                'jawabanA' => 'V',
                'jawabanB' => 'R',
            ),
            // Nomor 76
            array(
                'soalA' => 'Saya sangat <i><u>hangat</u></i> kepada orang-orang',
                'soalB' => 'Saya menyukai pekerjaan yang menuntut <i><u>ketepatan</u></i>',
                'jawabanA' => 'S',
                'jawabanB' => 'D',
            ),
            // Nomor 77
            array(
                'soalA' => 'Saya banyak <i><u>berpikir</u></i> dan merencana',
                'soalB' => 'Saya meletakkan segala sesuatu <i><u>pada tempatnya</u></i>',
                'jawabanA' => 'R',
                'jawabanB' => 'C',
            ),
            // Nomor 78
            array(
                'soalA' => 'Saya suka tugas yang perlu ditekuni sampai kepada <i><u>hal sedetailnya</u></i>',
                'soalB' => 'Saya <i><u>tidak cepat</u></i> marah',
                'jawabanA' => 'D',
                'jawabanB' => 'E',
            ),
            // Nomor 79
            array(
                'soalA' => 'Saya senang <i><u>mengikuti</u></i> orang-orang yang saya kagumi',
                'soalB' => 'Saya selalu <i><u>menyelesaikan</u></i> pekerjaan yang saya mulai',
                'jawabanA' => 'F',
                'jawabanB' => 'N',
            ),
            // Nomor 80
            array(
                'soalA' => 'Saya menyukai petunjuk-petunjuk yang <i><u>jelas</u></i>',
                'soalB' => 'Saya suka bekerja <i><u>“keras”</u></i>',
                'jawabanA' => 'W',
                'jawabanB' => 'A',
            ),
            // Nomor 81
            array(
                'soalA' => 'Saya <i><u>mengejar</u></i> apa yang saya inginkan',
                'soalB' => 'Saya adalah seorang pemimpin yang <i><u>baik</u></i>',
                'jawabanA' => 'G',
                'jawabanB' => 'L',
            ),
            // Nomor 82
            array(
                'soalA' => 'Saya membuat <i><u>orang lain</u></i> bekerja keras',
                'soalB' => 'Saya adalah seorang yang <i><u>“gampangan”</u></i> (tak banyak pertimbangan)',
                'jawabanA' => 'L',
                'jawabanB' => 'I',
            ),
            // Nomor 83
            array(
                'soalA' => 'Saya membuat keputusan-keputusan secara <i><u>cepat</u></i>',
                'soalB' => 'Bicara saya <i><u>cepat</u></i>',
                'jawabanA' => 'I',
                'jawabanB' => 'T',
            ),
            // Nomor 84
            array(
                'soalA' => 'Biasanya saya bekerja <i><u>tergesa-gesa</u></i>',
                'soalB' => 'Secara teratur saya <i><u>berolah raga</u></i>',
                'jawabanA' => 'T',
                'jawabanB' => 'V',
            ),
            // Nomor 85
            array(
                'soalA' => 'Saya <i><u>tidak suka</u></i> bertemu dengan orang-orang',
                'soalB' => 'Saya <i><u>cepat</u></i> Lelah',
                'jawabanA' => 'V',
                'jawabanB' => 'S',
            ),
            // Nomor 86
            array(
                'soalA' => 'Saya mempunyai <i><u>banyak</u></i> sekali teman',
                'soalB' => '<i><u>Banyak</u></i> waktu saya untuk berpikir',
                'jawabanA' => 'S',
                'jawabanB' => 'R',
            ),
            // Nomor 87
            array(
                'soalA' => 'Saya suka bekerja dengan <i><u>teori</u></i>',
                'soalB' => 'Saya suka bekerja <i><u>sedetail-detailnya</u></i>',
                'jawabanA' => 'R',
                'jawabanB' => 'D',
            ),
            // Nomor 88
            array(
                'soalA' => 'Saya suka bekerja sampai <i><u>sedetail-detailnya</u></i>',
                'soalB' => 'Saya suka <i><u>mengorganisir</u></i> pekerjaan saya',
                'jawabanA' => 'D',
                'jawabanB' => 'C',
            ),
            // Nomor 89
            array(
                'soalA' => 'Saya meletakan segala sesuatu <i><u>pada tempatnya</u></i>',
                'soalB' => 'Saya selalu <i><u>menyenangkan</u></i>',
                'jawabanA' => 'C',
                'jawabanB' => 'E',
            ),
            // Nomor 90
            array(
                'soalA' => 'Saya senang <i><u>diberi petunjuk</u></i> mengenai apa yang harus saya lakukan',
                'soalB' => 'Saya harus <i><u>menyelesaikan</u></i> apa yang sudah saya mulai',
                'jawabanA' => 'W',
                'jawabanB' => 'N',
            ),
        );
        
        return $data;
    }
    
    public function dataAnalisisPapikostick(){
		$analisis = array(
			"N" => array(
				array(
					"syarat" => 3,
					"deskripsi" => "Cenderung ragu-ragu dalam situasi pengambilan keputusan, cenderung ragu-ragu, menunda atau menghindari situasi pengambilan keputusan",
				),
				array(
					"syarat" => 4,
					"deskripsi" => "Berhati-hati dan cenderung ragu-ragu",
				),
				array(
					"syarat" => 6,
					"deskripsi" => "Cukup bertanggung jawab terhadap pekerjaan",
				),
				array(
					"syarat" => "else",
					"deskripsi" => "Ketekunan, tanggung jawab terhadap tugas tinggi",
				),
			),
			"G" => array(
				array(
					"syarat" => 4,
					"deskripsi" => "Bekerja hanya untuk mengejar kesenangan saja bukan untuk memberikan suatu hasil yang baik",
				),
				array(
					"syarat" => "else",
					"deskripsi" => "Kemauan bekerja keras tinggi",
				),
			),
			"A" => array(
				array(
					"syarat" => 5,
					"deskripsi" => "Mencerminkan ketidakpastian tujuan. Juga mencerminkan kepuasan dalam suatu pekerjaan, tidak perlu melanjutkan usaha untuk sukses",
				),
				array(
					"syarat" => "else",
					"deskripsi" => "Tujuan-tujuan didefinisikan secara jelas, kebutuhan untuk sukses tinggi, ambisi pribadi tinggi",
				),
			),
			"L" => array(
				array(
					"syarat" => 4,
					"deskripsi" => "Cenderung tidak suka aktif menggunakan orang lain dalam bekerja",
				),
				array(
					"syarat" => "else",
					"deskripsi" => "Yaitu tingkat dimana seseorang memproyeksikan dirinya sebagai pemimpin suatu tingkat, dimana ia mencoba menggunakan orang lain untuk mencapai tujuannya. Nilai S menunjukkan apakah pola kepemimpinannya bersifat persuasive, demokratis, atau otoriter",
				),
			),
			"P" => array(
				array(
					"syarat" => 4,
					"deskripsi" => "Menurunnya keinginan untuk bertanggung jawab terhadap pekerjaan dan tindakan orang lain",
				),
				array(
					"syarat" => "else",
					"deskripsi" => "Tingkat kebutuhan untuk menerima tanggung jawab orang lain, menjadi orang yang bertanggung jawab",
				),
			),
			"I" => array(
				array(
					"syarat" => 3,
					"deskripsi" => "Ragu-ragu sampai penundaan/menolak situasi pengambilan keputusan",
				),
				array(
					"syarat" => 4,
					"deskripsi" => "Berhati-hati sampai ragu-ragu dalam membuat keputusan",
				),
				array(
					"syarat" => 7,
					"deskripsi" => "Mudah dan lancar sampai berhati-hati dalam membuat keputusan",
				),
				array(
					"syarat" => "else",
					"deskripsi" => "Tidak ragu-ragu dalam proses pengambilan keputusan",
				),
			),
			"T" => array(
				array(
					"syarat" => 3,
					"deskripsi" => "Melakukan segala sesuatu menurut kemauannya sendiri",
				),
				array(
					"syarat" => "else",
					"deskripsi" => "Tergolong aktif secara internal dan mental",
				),
			),
			"V" => array(
				array(
					"syarat" => 4,
					"deskripsi" => "Keaktifannya tergolong rendah, cenderung pasif (hanya duduk-duduk saja",
				),
				array(
					"syarat" => "else",
					"deskripsi" => "Keaktifannya secara fisik tergolong agak baik, cenderung tipe sportif",
				),
			),
			"X" => array(
				array(
					"syarat" => 1,
					"deskripsi" => "Cenderung pemalu, suka menyendiri",
				),
				array(
					"syarat" => 3,
					"deskripsi" => "Rendah hati, tulus",
				),
				array(
					"syarat" => 5,
					"deskripsi" => "Khusus, memiliki pola yang nyata",
				),
				array(
					"syarat" => "else",
					"deskripsi" => "Membutuhkan perhatian yang nyata",
				),
			),
			"S" => array(
				array(
					"syarat" => 5,
					"deskripsi" => "Memiliki penilaian yang rendah terhadap hubungan sosial, cenderung kurang percaya pada orang lain",
				),
				array(
					"syarat" => "else",
					"deskripsi" => "Tingkat kepercayaan dalam hubungan sosial tinggi, menyukai interaksi sosial",
				),
			),
			"B" => array(
				array(
					"syarat" => 3,
					"deskripsi" => "Selektif, secara umum melepaskan diri dari kelompok",
				),
				array(
					"syarat" => 5,
					"deskripsi" => "Ada kebutuhan untuk diterima dan diakui tetapi tidak terlalu mudah dipengaruhi oleh kelompok",
				),
				array(
					"syarat" => "else",
					"deskripsi" => "Kebutuhan untuk disukai, diakui oleh semua orang. Mudah dipengaruhi kelompok",
				),
			),
			"O" => array(
				array(
					"syarat" => 2,
					"deskripsi" => "Tidak menyukai hubungan antar pribadi. Tidak menyukai interaksi perseorangan",
				),
				array(
					"syarat" => 4,
					"deskripsi" => "Sadar akan kebutuhan antar pribadi tetapi dapat melepaskan diri dari orang lain/tidak terlalu tergantung",
				),
				array(
					"syarat" => "else",
					"deskripsi" => "Ketergantungan yang sangat besar akan pengakuan dan penerimaan diri",
				),
			),
			"R" => array(
				array(
					"syarat" => 4,
					"deskripsi" => "Kurang perhatian-praktis",
				),
				array(
					"syarat" => "else",
					"deskripsi" => "Penekanan pada nialai-nilai penalaran tergolong tinggi",
				),
			),
			"D" => array(
				array(
					"syarat" => 3,
					"deskripsi" => "Menyadari kebutuhan akan kecermatan tetapi secara pribadi tidak berminat menangani hal-hal detail",
				),
				array(
					"syarat" => "else",
					"deskripsi" => "Minat menangani hal-hal detail tergolong tinggi",
				),
			),
			"C" => array(
				array(
					"syarat" => 2,
					"deskripsi" => "Fleksibilitas sampai ketidak-teraturan",
				),
				array(
					"syarat" => 5,
					"deskripsi" => "Tergolong teratur tetapi dengan fleksibilitas",
				),
				array(
					"syarat" => "else",
					"deskripsi" => "Memiliki keteraturan yang sangat tinggi, cenderung kaku",
				),
			),
			"Z" => array(
				array(
					"syarat" => 2,
					"deskripsi" => "Tidak menyukai dan menolak perubahan. Cenderung menggunakan pendekatan-pendekatan tradisional",
				),
				array(
					"syarat" => 4,
					"deskripsi" => "Tidak suka akan perubahan jika dipaksakan kepadanya",
				),
				array(
					"syarat" => 6,
					"deskripsi" => "Mudah menyesuaikan diri",
				),
				array(
					"syarat" => 7,
					"deskripsi" => "Pembuat perubahan yang selektif. Berpikir jauh ke depan",
				),
				array(
					"syarat" => "else",
					"deskripsi" => "Mudah gelisah, mudah frustrasi mungkin karena segala sesuatu bergerak tidak cukup cepat",
				),
			),
			"E" => array(
				array(
					"syarat" => 1,
					"deskripsi" => "Terbuka, cepat bereaksi, tidak memikirkan nilai dalam pengendalian diri",
				),
				array(
					"syarat" => 3,
					"deskripsi" => "Terbuka",
				),
				array(
					"syarat" => 6,
					"deskripsi" => "Memiliki pendekatan emosional yang seimbang. Mampu mengendalikan perasaannya",
				),
				array(
					"syarat" => "else",
					"deskripsi" => "Sangat menepatkan nilai-nilai dalam setiap aktivitasnya. Kebutuhan pengendalian diri yang berlebih-lebihan, mungkin digunakan sebagai defence mechanisme",
				),
			),
			"K" => array(
				array(
					"syarat" => 2,
					"deskripsi" => "Selalu menghindari masalah. Cenderung mengabaikan situasi atau cenderung menolak untuk mengenali sesuatu sebagai sebuah masalah",
				),
				array(
					"syarat" => 4,
					"deskripsi" => "Lebih menyukai lingkungan yang tenang. Menghindari konflik. Cenderung menunda masalah",
				),
				array(
					"syarat" => 5,
					"deskripsi" => "Kukuh pendirian, cenderung keras kepala",
				),
				array(
					"syarat" => 7,
					"deskripsi" => "Agresi pribadi yang berkaitan dengan pekerjaan, dorongan dan semangat bersaing",
				),
				array(
					"syarat" => "else",
					"deskripsi" => "Agresif, cenderung defensive",
				),
			),
			"F" => array(
				array(
					"syarat" => 1,
					"deskripsi" => "Cenderung egois, kemungkinan bisa bersikap memberontak",
				),
				array(
					"syarat" => 3,
					"deskripsi" => "Mengurus kepentingan diri sendiri",
				),
				array(
					"syarat" => 5,
					"deskripsi" => "Setia terhadap perusahaan",
				),
				array(
					"syarat" => "else",
					"deskripsi" => "Bersikap setia dan membantu secara pribadi, ada kemungkinan bantuannya bermotivasi politis",
				),
			),
			"W" => array(
				array(
					"syarat" => 3,
					"deskripsi" => "Berorientasi pada tujuan, mandiri",
				),
				array(
					"syarat" => 5,
					"deskripsi" => "Kebutuhan akan pengarahan dan harapan yang dirumuskan untuknya",
				),
				array(
					"syarat" => "else",
					"deskripsi" => "Meningkatnya orientasi terhadap tugas dan membutuhkan instruksi yang jelas",
				),
			),
		);
		
		return $analisis;
    }

	public function analisisPapikostick($jawaban, $array){
	    	// Menghitung jumlah if else
		$count = count($array);

	    	// Jika jumlah if else 2
		if($count == 2){
			if($jawaban <= $array[0]["syarat"]) return $array[0]["deskripsi"];
			else return $array[1]["deskripsi"];
		}
	    	// Jika jumlah if else 3
		elseif($count == 3){
			if($jawaban <= $array[0]["syarat"]) return $array[0]["deskripsi"];
			elseif($jawaban <= $array[1]["syarat"]) return $array[1]["deskripsi"];
			else return $array[2]["deskripsi"];
		}
	    	// Jika jumlah if else 4
		elseif($count == 4){
			if($jawaban <= $array[0]["syarat"]) return $array[0]["deskripsi"];
			elseif($jawaban <= $array[1]["syarat"]) return $array[1]["deskripsi"];
			elseif($jawaban <= $array[2]["syarat"]) return $array[2]["deskripsi"];
			else return $array[3]["deskripsi"];
		}
			//Jika jumlah if else 5
		elseif($count == 5){
			if($jawaban <= $array[0]["syarat"]) return $array[0]["deskripsi"];
			elseif($jawaban <= $array[1]["syarat"]) return $array[1]["deskripsi"];
			elseif($jawaban <= $array[2]["syarat"]) return $array[2]["deskripsi"];
			elseif($jawaban <= $array[3]["syarat"]) return $array[3]["deskripsi"];
			else return $array[4]["deskripsi"];
		}
	}
}
