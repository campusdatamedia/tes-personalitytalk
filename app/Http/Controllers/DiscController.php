<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use PDF;
use App\Hasil;
use App\Keterangan;
use App\Lowongan;
use App\PaketSoal;
use App\Pelamar;
use App\Soal;
use App\TahapAdministrasi;
use App\TahapWawancara;
use App\Tutorial;
use App\User;

class DiscController extends Controller
{
    /**
     * Menampilkan data hasil DISC
     * 
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Get data
        $pelamar = Hasil::join('pelamar','hasil_disc.id_pelamar','=','pelamar.id_pelamar')->get();
        foreach($pelamar as $key=>$data){
            $data->id_user = User::find($data->id_user);
            $data->posisi = Lowongan::find($data->posisi);
            $ta = TahapAdministrasi::where('id_pelamar','=',$data->id_pelamar)->first();
            $pelamar[$key]->tanggal_wawancara = $ta->waktu_wawancara;
        }

        // View
        return view('disc/admin/result-list', [
            'pelamar' => $pelamar,
        ]);
    }

    /**
     * Menampilkan hasil DISC pelamar
     *
     * int $id
     * @return \Illuminate\Http\Response
     */
    public function result($id)
    {
        // Get data
        $hasil = HasilDisc::join('pelamar','hasil_disc.id_pelamar','=','pelamar.id_pelamar')->where('id_pelamar','=',$id)->first();

        // Jika tidak ada data
        if(!$hasil){
            abort(404);
        }

        // Ranking
        $disc_score_m = sortScore($hasil->m_score);
        $disc_score_l = sortScore($hasil->l_score);

        // Kode
        $code_m = setCode($disc_score_m);
        $code_l = setCode($disc_score_l);

        // Keterangan
        $keterangan = Keterangan::join('paket_soal','keterangan.id_paket','=','paket_soal.id_paket')->where('status','=',1)->first();
        switch(substr($code_l[0],1,1)){
            case 'D':
                $hasil_keterangan = $keterangan->d;
            break;
            case 'I':
                $hasil_keterangan = $keterangan->i;
            break;
            case 'S':
                $hasil_keterangan = $keterangan->s;
            break;
            case 'C':
                $hasil_keterangan = $keterangan->c;
            break;
        }

        // View
        return view('disc/admin/result', [
            'soal' => $soal,
            'disc_score_m' => $disc_score_m,
            'disc_score_l' => $disc_score_l,
            'code_m' => $code_m,
            'code_l' => $code_l,
            'hasil_keterangan' => $hasil_keterangan,
        ]);

        // View
        return view('disc/admin/result', [
            'hasil' => $hasil,
        ]);
    }

    /**
     * PDF
     *
     * @return \Illuminate\Http\Response
     */
    public function printPDF()
    {
        // Get data
        $paket_soal = PaketSoal::all();

        // PDF
        $pdf = PDF::loadview('disc/guest/pdf', ['paket_soal' => $paket_soal]);
        // return $pdf->download(time());
        return $pdf->stream();
    }

    /**
     * Menampilkan lembar tes...
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function test()
    {
        // Get data
        $pelamar = Pelamar::join('users','pelamar.id_user','=','users.id_user')->join('lowongan','pelamar.posisi','=','lowongan.id_lowongan')->where('users.id_user','=',Auth::user()->id_user)->first();

        // Jika tidak ada data
        if(!$pelamar){
            abort(404);
        }
        // Jika ada data
        else{
            // Get tahap wawancara
            $tw = TahapWawancara::where('id_pelamar','=',$pelamar->id_pelamar)->where('buka','=',1)->first();

            // Jika tidak lolos
            if(!$tw){
                abort(404);
            }
            else{
                // Mengecek apakah sudah melakukan tes atau belum
                $disc = HasilDisc::where('id_pelamar','=',$pelamar->id_pelamar)->first();
            }
        }

        // Tutorial
        $tutorial = Tutorial::where('nama','disc')->first();

        // Soal
        $soal = Soal::join('paket_soal','soal.id_paket','=','paket_soal.id_paket')->where('status','=',1)->orderBy('nomor','asc')->get();
        foreach($soal as $data){
            $array = json_decode($data->soal, true);
            $data->soal = $array;
        }

        // View
        return view('disc/applicant/test', [
            'disc' => $disc,
            'soal' => $soal,
            'tutorial' => $tutorial,
        ]);
    }

    /**
     * Menyimpan lembar tes...
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Get user
        $user = Pelamar::where('id_user','=',Auth::user()->id_user)->first();
        $m = $request->get('m');
        $l = $request->get('l');
        $disc = array('D', 'I', 'S','C');
        $disc_m = array();
        $disc_l = array();
        $disc_score_m = array();
        $disc_score_l = array();
        $soal = Soal::join('paket_soal','soal.id_paket','=','paket_soal.id_paket')->where('status','=',1)->orderBy('nomor','asc')->get();
        foreach($soal as $key=>$data){
            $array = json_decode($data->soal, true);
            $data->soal = $array;
            array_push($disc_m, $data->soal[0]['disc'][$m[($key+1)]]);
            array_push($disc_l, $data->soal[0]['disc'][$l[($key+1)]]);
        }

        // Hitung score MOST dan LEAST
        $array_count_m = array_count_values($disc_m);
        $array_count_l = array_count_values($disc_l);
        foreach($disc as $letter){
            $disc_score_m[$letter] = array_key_exists($letter, $array_count_m) ? discScoringM($array_count_m[$letter]) : 0;
            $disc_score_l[$letter] = array_key_exists($letter, $array_count_l) ? discScoringL($array_count_l[$letter]) : 0;
        }

        // Menyimpan data
        $hasil = new HasilDisc;
        $hasil->id_pelamar = $user->id_pelamar;
        $hasil->m_score = json_encode($disc_score_m);
        $hasil->l_score = json_encode($disc_score_l);
        $hasil->save();

        // Redirect
        return redirect('/applicant/disc')->with(['message' => 'Berhasil melakukan tes DISC.']);
    }

    /**
     * Mengirim lembar tes...
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function postTest2(Request $request)
    {
        // Get data
        $user = Pelamar::where('id_user','=',Auth::user()->id_user)->first();
        $m = $request->get('m');
        $l = $request->get('l');
        $disc = array('D', 'I', 'S','C');
        $disc_m = array();
        $disc_l = array();
        $disc_score_m = array();
        $disc_score_l = array();
        $soal = Soal::join('paket_soal','soal.id_paket','=','paket_soal.id_paket')->where('status','=',1)->orderBy('nomor','asc')->get();
        foreach($soal as $key=>$data){
            $array = json_decode($data->soal, true);
            $data->soal = $array;
            array_push($disc_m, $data->soal[0]['disc'][$m[($key+1)]]);
            array_push($disc_l, $data->soal[0]['disc'][$l[($key+1)]]);
        }

        // Hitung score MOST dan LEAST
        $array_count_m = array_count_values($disc_m);
        $array_count_l = array_count_values($disc_l);
        foreach($disc as $letter){
            $disc_score_m[$letter]['score'] = array_key_exists($letter, $array_count_m) ? discScoringM($array_count_m[$letter]) : 0;
            $disc_score_l[$letter]['score'] = array_key_exists($letter, $array_count_l) ? discScoringL($array_count_l[$letter]) : 0;
        }

        // Ranking
        $disc_score_m = sortScore($disc_score_m);
        $disc_score_l = sortScore($disc_score_l);

        // Kode
        $code_m = setCode($disc_score_m);
        $code_l = setCode($disc_score_l);

        // Keterangan
        $keterangan = Keterangan::join('paket_soal','keterangan.id_paket','=','paket_soal.id_paket')->where('status','=',1)->first();
        switch(substr($code_l[0],1,1)){
            case 'D':
                $hasil_keterangan = $keterangan->d;
            break;
            case 'I':
                $hasil_keterangan = $keterangan->i;
            break;
            case 'S':
                $hasil_keterangan = $keterangan->s;
            break;
            case 'C':
                $hasil_keterangan = $keterangan->c;
            break;
        }

        // View
        return view('disc/applicant/post', [
            'user' => $user,
            'soal' => $soal,
            'm' => $m,
            'l' => $l,
            'disc' => $disc,
            'array_count_m' => $array_count_m,
            'array_count_l' => $array_count_l,
            'disc_score_m' => $disc_score_m,
            'disc_score_l' => $disc_score_l,
            'code_m' => $code_m,
            'code_l' => $code_l,
            'hasil_keterangan' => $hasil_keterangan,
        ]);
    }

}
