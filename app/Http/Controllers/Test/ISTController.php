<?php

namespace App\Http\Controllers\Test;

use Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Hasil;
use App\HRD;
use App\Karyawan;
use App\PaketSoal;
use App\Pelamar;
use App\Soal;
use App\TempTes;
use App\Tes;
use App\User;

class ISTController extends Controller
{    
    /**
     * Menampilkan halaman tes
     * 
     * string $path
     * @return \Illuminate\Http\Request
     * @return \Illuminate\Http\Response
     */
    public static function index(Request $request, $path, $tes, $seleksi, $check)
    {
        // Vars
        $first_soal = '';
        $last_soal = '';

        // Tes
        $part = $request->query('part') ?: 1;
        $paket = PaketSoal::where('id_tes','=',$tes->id_tes)->where('part','=',$part)->where('status','=',1)->firstOrFail();
        $soal = Soal::join('paket_soal','soal.id_paket','=','paket_soal.id_paket')->where('part','=',$part)->where('soal.id_paket','=',$paket->id_paket)->where('status','=',1)->orderBy('nomor','asc')->get();
        if(count($soal)>0){
            foreach($soal as $key=>$data){
                $data->soal = json_decode($data->soal, true);
                if($key == 0) $first_soal = $data->nomor;
                if($key == count($soal)-1) $last_soal = $data->nomor;
            }
        }
        $range_soal = count($soal) > 1 ? $first_soal.'-'.$last_soal : $first_soal; // Range soal
        $last_part = PaketSoal::where('id_tes','=',$tes->id_tes)->where('status','=',1)->latest('part')->first(); // Part paket soal terakhir

        // View
        return view('tes/'.$path, [
            'check' => $check,
            'paket' => $paket,
            'path' => $path,
            'part' => $part,
            'range_soal' => $range_soal,
            'seleksi' => $seleksi,
            'soal' => $soal,
            'tes' => $tes,
            'last_part' => $last_part,
        ]);
    }

    /**
     * Memproses dan menyimpan tes
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public static function store(Request $request)
    {
        $age = generate_age('1997-05-26');
        var_dump($age);
        echo "<br>";
        var_dump(self::data_SW($age));
        return;

        // Check temp tes
        $check_temp = TempTes::where('id_user','=',Auth::user()->id_user)->first();

        if($check_temp){
            $array = json_decode($check_temp->json, true); // Get array
            $array[$request->part] = array_filter($request->get('c'));
            $json = json_encode($array);

            // Update temp tes
            $check_temp->json = $json;
            $check_temp->save();
        }
        else{
            $array = [];
            $array[$request->part] = array_filter($request->get('c'));
            $json = json_encode($array);

            // Save to temp tes
            $temp = new TempTes;
            $temp->id_user = Auth::user()->id_user;
            $temp->json = $json;
            $temp->temp_at = date('Y-m-d H:i:s');
            $temp->save();
        }

        // If it's submitted, then process
        if($request->is_submitted == 1){
            $score = []; // Score
            // Check answers
            if(count($array)>0){
                foreach($array as $part=>$answers){
                    $score_by_part = 0; // Score by part
                    foreach($answers as $num=>$answer){
                        $soal = Soal::join('paket_soal','soal.id_paket','=','paket_soal.id_paket')->where('part','=',$part)->where('id_tes','=',$request->id_tes)->where('status','=',1)->where('nomor','=',$num)->first(); // Get soal
                        if($soal){
                            // Convert detail soal from JSON to array
                            $detail_soal = json_decode($soal->soal, true);
                            $detail_soal = is_array($detail_soal) ? $detail_soal[0] : [];

                            // Check answer
                            if($soal->tipe_soal == 'choice' || $soal->tipe_soal == 'image'){
                                if($answer == $detail_soal['jawaban']) $score_by_part++; // If the answer is true, so the score increments
                            }
                            elseif($soal->tipe_soal == 'essay'){
                                // Explode possibly answers
                                $jawaban_essay = $detail_soal['jawaban'];
                                foreach($jawaban_essay as $num_essay=>$string_essay){
                                    $jawaban_essay[$num_essay] = explode(",", $string_essay);
                                }

                                // If the answer is true, so the score increments
                                if(in_array(strtolower(trim($answer)), $jawaban_essay[2])) $score_by_part+=2;
                                elseif(in_array(strtolower(trim($answer)), $jawaban_essay[1])) $score_by_part++;
                            }
                            elseif($soal->tipe_soal == 'number'){
                                // Explode possibly answers
                                $jawaban_number = str_split($detail_soal['jawaban']);

                                // Sort answers before checking
                                sort($answer);
                                sort($jawaban_number);

                                if($answer === $jawaban_number) $score_by_part++; // If the answer is true, so the score increments
                            }
                        }
                    }
                    $score[$part] = $score_by_part;
                }
            }

            $result = []; // Array result
            $array_IST = ['SE','WA','AN','GE','ME','RA','ZR','FA','WU']; // Array IST
            foreach($score as $key=>$score_by_part){
                // If GE
                if($key == 4){
                    $result['RW'][$array_IST[$key-1]] = convert_GE($score_by_part);
                    $result['SW'][$array_IST[$key-1]] = 0;
                }
                // If not GE
                else{
                    $result['RW'][$array_IST[$key-1]] = $score_by_part;
                    $result['SW'][$array_IST[$key-1]] = 0;
                }
            }
            $result['TRW'] = array_sum($result['RW']);
            $result['TSW'] = array_sum($result['SW']);
            $result['IQ'] = 0;

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

            // Save result
            $hasil = new Hasil;
            $hasil->id_hrd = isset($hrd) ? $hrd->id_hrd : 0;
            $hasil->id_user = Auth::user()->id_user;
            $hasil->id_tes = $request->id_tes;
            $hasil->id_paket = $request->id_paket;
            $hasil->hasil = json_encode($result);
            $hasil->test_at = date("Y-m-d H:i:s");
            $hasil->save();

            // Delete temp
            $temp = TempTes::where('id_user','=',Auth::user()->id_user)->first();
            $temp->delete();
        }

        // Redirect
        if($request->is_submitted == 1)
            return redirect('/dashboard')->with(['message' => 'Berhasil mengerjakan tes IST']);
        elseif($request->is_submitted == 0)
            return redirect('/tes/ist?part='.($request->part+1));
    }
    
    /**
     * Data SW
     *
     * int $age
     * @return \Illuminate\Http\Response
     */
    public static function data_SW($age){
        $array = [];
        if($age >= 19 && $age <= 20){
            $array = [
                'SE' => [
                    0 => 70,
                    1 => 73,
                    2 => 76,
                    3 => 78,
                    4 => 81,
                    5 => 84,
                    6 => 87,
                    7 => 89,
                    8 => 92,
                    9 => 95,
                    10 => 98,
                    11 => 101,
                    12 => 103,
                    13 => 106,
                    14 => 109,
                    15 => 112,
                    16 => 114,
                    17 => 117,
                    18 => 120,
                    19 => 123,
                    20 => 126,
                ],
                'WA' => [
                    0 => 65,
                    1 => 68,
                    2 => 71,
                    3 => 75,
                    4 => 78,
                    5 => 81,
                    6 => 85,
                    7 => 88,
                    8 => 91,
                    9 => 95,
                    10 => 98,
                    11 => 101,
                    12 => 105,
                    13 => 108,
                    14 => 111,
                    15 => 115,
                    16 => 118,
                    17 => 121,
                    18 => 125,
                    19 => 128,
                    20 => 131,
                ],
                'AN' => [
                    0 => 74,
                    1 => 77,
                    2 => 79,
                    3 => 82,
                    4 => 85,
                    5 => 87,
                    6 => 90,
                    7 => 92,
                    8 => 95,
                    9 => 97,
                    10 => 100,
                    11 => 103,
                    12 => 105,
                    13 => 108,
                    14 => 110,
                    15 => 113,
                    16 => 115,
                    17 => 118,
                    18 => 121,
                    19 => 123,
                    20 => 126,
                ],
                'GE' => [
                    0 => 73,
                    1 => 75,
                    2 => 78,
                    3 => 81,
                    4 => 83,
                    5 => 86,
                    6 => 88,
                    7 => 91,
                    8 => 93,
                    9 => 96,
                    10 => 98,
                    11 => 101,
                    12 => 104,
                    13 => 106,
                    14 => 109,
                    15 => 111,
                    16 => 114,
                    17 => 116,
                    18 => 119,
                    19 => 122,
                    20 => 124,
                ],
                'ME' => [
                    0 => 74,
                    1 => 77,
                    2 => 79,
                    3 => 81,
                    4 => 83,
                    5 => 86,
                    6 => 88,
                    7 => 90,
                    8 => 92,
                    9 => 94,
                    10 => 97,
                    11 => 99,
                    12 => 101,
                    13 => 103,
                    14 => 106,
                    15 => 108,
                    16 => 110,
                    17 => 112,
                    18 => 114,
                    19 => 117,
                    20 => 119,
                ],
                'RA' => [
                    0  => 74,
                    1  => 77,
                    2  => 80,
                    3  => 83,
                    4  => 85,
                    5  => 88,
                    6  => 91,
                    7  => 94,
                    8  => 96,
                    9  => 99,
                    10 => 102,
                    11 => 105,
                    12 => 108,
                    13 => 110,
                    14 => 113,
                    15 => 116,
                    16 => 119,
                    17 => 121,
                    18 => 124,
                    19 => 127,
                    20 => 130,
                ],
                'ZR' => [
                    0 => 76,
                    1 => 78,
                    2 => 81,
                    3 => 83,
                    4 => 85,
                    5 => 88,
                    6 => 90,
                    7 => 92,
                    8 => 95,
                    9 => 97,
                    10 => 99,
                    11 => 102,
                    12 => 104,
                    13 => 106,
                    14 => 109,
                    15 => 111,
                    16 => 113,
                    17 => 116,
                    18 => 118,
                    19 => 120,
                    20 => 123,
                ],
                'FA' => [
                    0 => 72,
                    1 => 75,
                    2 => 78,
                    3 => 80,
                    4 => 83,
                    5 => 86,
                    6 => 88,
                    7 => 91,
                    8 => 94,
                    9 => 96,
                    10 => 99,
                    11 => 102,
                    12 => 105,
                    13 => 107,
                    14 => 110,
                    15 => 113,
                    16 => 115,
                    17 => 118,
                    18 => 121,
                    19 => 124,
                    20 => 126,
                ],
                'WU' => [
                    0 => 72,
                    1 => 75,
                    2 => 78,
                    3 => 81,
                    4 => 83,
                    5 => 86,
                    6 => 89,
                    7 => 92,
                    8 => 95,
                    9 => 98,
                    10 => 101,
                    11 => 103,
                    12 => 106,
                    13 => 109,
                    14 => 112,
                    15 => 115,
                    16 => 118,
                    17 => 121,
                    18 => 123,
                    19 => 126,
                    20 => 129,
                ],
            ];
        }
        elseif($age >= 21 && $age <= 25){
            $array = [
                'SE' => [
                    0 => 68,
                    1 => 71,
                    2 => 74,
                    3 => 76,
                    4 => 79,
                    5 => 82,
                    6 => 85,
                    7 => 88,
                    8 => 91,
                    9 => 94,
                    10 => 97,
                    11 => 100,
                    12 => 103,
                    13 => 106,
                    14 => 109,
                    15 => 112,
                    16 => 115,
                    17 => 118,
                    18 => 121,
                    19 => 124,
                    20 => 126,
                ],
                'WA' => [
                    0 => 63,
                    1 => 66,
                    2 => 70,
                    3 => 74,
                    4 => 77,
                    5 => 81,
                    6 => 84,
                    7 => 88,
                    8 => 91,
                    9 => 95,
                    10 => 99,
                    11 => 102,
                    12 => 106,
                    13 => 109,
                    14 => 113,
                    15 => 116,
                    16 => 120,
                    17 => 124,
                    18 => 127,
                    19 => 131,
                    20 => 134,
                ],
                'AN' => [
                    0 => 76,
                    1 => 78,
                    2 => 81,
                    3 => 83,
                    4 => 86,
                    5 => 88,
                    6 => 91,
                    7 => 93,
                    8 => 96,
                    9 => 98,
                    10 => 101,
                    11 => 103,
                    12 => 106,
                    13 => 108,
                    14 => 111,
                    15 => 113,
                    16 => 116,
                    17 => 118,
                    18 => 121,
                    19 => 123,
                    20 => 126,
                ],
                'GE' => [
                    0 => 69,
                    1 => 72,
                    2 => 75,
                    3 => 78,
                    4 => 81,
                    5 => 83,
                    6 => 86,
                    7 => 89,
                    8 => 92,
                    9 => 94,
                    10 => 97,
                    11 => 100,
                    12 => 103,
                    13 => 106,
                    14 => 108,
                    15 => 111,
                    16 => 114,
                    17 => 117,
                    18 => 119,
                    19 => 122,
                    20 => 125,
                ],
                'ME' => [
                    0 => 75,
                    1 => 77,
                    2 => 80,
                    3 => 82,
                    4 => 84,
                    5 => 87,
                    6 => 89,
                    7 => 91,
                    8 => 94,
                    9 => 96,
                    10 => 98,
                    11 => 101,
                    12 => 103,
                    13 => 105,
                    14 => 108,
                    15 => 110,
                    16 => 112,
                    17 => 115,
                    18 => 117,
                    19 => 119,
                    20 => 122,
                ],
                'RA' => [
                    0 => 74,
                    1 => 77,
                    2 => 79,
                    3 => 82,
                    4 => 85,
                    5 => 88,
                    6 => 91,
                    7 => 94,
                    8 => 97,
                    9 => 99,
                    10 => 102,
                    11 => 105,
                    12 => 108,
                    13 => 111,
                    14 => 114,
                    15 => 117,
                    16 => 119,
                    17 => 122,
                    18 => 125,
                    19 => 128,
                    20 => 131,
                ],
                'ZR' => [
                    0 => 77,
                    1 => 80,
                    2 => 82,
                    3 => 84,
                    4 => 87,
                    5 => 89,
                    6 => 91,
                    7 => 94,
                    8 => 96,
                    9 => 99,
                    10 => 101,
                    11 => 103,
                    12 => 106,
                    13 => 108,
                    14 => 110,
                    15 => 113,
                    16 => 115,
                    17 => 118,
                    18 => 120,
                    19 => 122,
                    20 => 125,
                ],
                'FA' => [
                    0 => 70,
                    1 => 73,
                    2 => 76,
                    3 => 79,
                    4 => 81,
                    5 => 84,
                    6 => 87,
                    7 => 90,
                    8 => 93,
                    9 => 96,
                    10 => 99,
                    11 => 101,
                    12 => 104,
                    13 => 107,
                    14 => 110,
                    15 => 113,
                    16 => 116,
                    17 => 119,
                    18 => 121,
                    19 => 124,
                    20 => 127,
                ],
                'WU' => [
                    0 => 72,
                    1 => 75,
                    2 => 77,
                    3 => 80,
                    4 => 83,
                    5 => 86,
                    6 => 89,
                    7 => 92,
                    8 => 95,
                    9 => 97,
                    10 => 100,
                    11 => 103,
                    12 => 106,
                    13 => 109,
                    14 => 112,
                    15 => 115,
                    16 => 117,
                    17 => 120,
                    18 => 123,
                    19 => 126,
                    20 => 129,
                ],
            ];
        }
        elseif($age >= 26 && $age <= 30){
            $array = [
                'SE' => [
                    0 => 66,
                    1 => 69,
                    2 => 72,
                    3 => 75,
                    4 => 78,
                    5 => 81,
                    6 => 84,
                    7 => 87,
                    8 => 90,
                    9 => 93,
                    10 => 96,
                    11 => 99,
                    12 => 102,
                    13 => 105,
                    14 => 108,
                    15 => 112,
                    16 => 115,
                    17 => 118,
                    18 => 121,
                    19 => 124,
                    20 => 127,
                ],
                'WA' => [
                    0 => 66,
                    1 => 69,
                    2 => 73,
                    3 => 76,
                    4 => 79,
                    5 => 83,
                    6 => 86,
                    7 => 89,
                    8 => 93,
                    9 => 96,
                    10 => 99,
                    11 => 103,
                    12 => 106,
                    13 => 109,
                    14 => 113,
                    15 => 116,
                    16 => 119,
                    17 => 123,
                    18 => 126,
                    19 => 129,
                    20 => 133,
                ],
                'AN' => [
                    0 => 78,
                    1 => 80,
                    2 => 83,
                    3 => 85,
                    4 => 87,
                    5 => 90,
                    6 => 92,
                    7 => 95,
                    8 => 97,
                    9 => 99,
                    10 => 102,
                    11 => 104,
                    12 => 106,
                    13 => 109,
                    14 => 111,
                    15 => 114,
                    16 => 116,
                    17 => 118,
                    18 => 121,
                    19 => 123,
                    20 => 125,
                ],
                'GE' => [
                    0 => 69,
                    1 => 71,
                    2 => 74,
                    3 => 77,
                    4 => 80,
                    5 => 83,
                    6 => 85,
                    7 => 88,
                    8 => 91,
                    9 => 94,
                    10 => 96,
                    11 => 99,
                    12 => 102,
                    13 => 105,
                    14 => 108,
                    15 => 110,
                    16 => 113,
                    17 => 116,
                    18 => 119,
                    19 => 121,
                    20 => 124,
                ],
                'ME' => [
                    0 => 77,
                    1 => 80,
                    2 => 82,
                    3 => 84,
                    4 => 86,
                    5 => 89,
                    6 => 91,
                    7 => 93,
                    8 => 95,
                    9 => 98,
                    10 => 100,
                    11 => 102,
                    12 => 105,
                    13 => 107,
                    14 => 109,
                    15 => 111,
                    16 => 114,
                    17 => 116,
                    18 => 118,
                    19 => 120,
                    20 => 123,
                ],
                'RA' => [
                    0 => 74,
                    1 => 77,
                    2 => 79,
                    3 => 82,
                    4 => 85,
                    5 => 88,
                    6 => 91,
                    7 => 94,
                    8 => 97,
                    9 => 99,
                    10 => 102,
                    11 => 105,
                    12 => 108,
                    13 => 111,
                    14 => 114,
                    15 => 117,
                    16 => 119,
                    17 => 122,
                    18 => 125,
                    19 => 128,
                    20 => 131,
                ],
                'ZR' => [
                    0 => 79,
                    1 => 81,
                    2 => 83,
                    3 => 86,
                    4 => 88,
                    5 => 90,
                    6 => 93,
                    7 => 95,
                    8 => 97,
                    9 => 100,
                    10 => 102,
                    11 => 104,
                    12 => 107,
                    13 => 109,
                    14 => 111,
                    15 => 113,
                    16 => 116,
                    17 => 118,
                    18 => 120,
                    19 => 123,
                    20 => 125,
                ],
                'FA' => [
                    0 => 71,
                    1 => 73,
                    2 => 76,
                    3 => 79,
                    4 => 82,
                    5 => 85,
                    6 => 88,
                    7 => 91,
                    8 => 93,
                    9 => 96,
                    10 => 99,
                    11 => 102,
                    12 => 105,
                    13 => 108,
                    14 => 111,
                    15 => 113,
                    16 => 116,
                    17 => 119,
                    18 => 122,
                    19 => 125,
                    20 => 128,
                ],
                'WU' => [
                    0 => 72,
                    1 => 75,
                    2 => 78,
                    3 => 81,
                    4 => 84,
                    5 => 87,
                    6 => 90,
                    7 => 93,
                    8 => 96,
                    9 => 99,
                    10 => 101,
                    11 => 104,
                    12 => 107,
                    13 => 110,
                    14 => 113,
                    15 => 116,
                    16 => 119,
                    17 => 122,
                    18 => 125,
                    19 => 128,
                    20 => 131,
                ],
            ];
        }

        return $array;
    }
}