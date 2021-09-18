<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Hasil;
use App\HRD;
use App\Karyawan;
use App\PaketSoal;
use App\Pelamar;
use App\Soal;
use App\User;

class QuestionController extends Controller
{
    /**
     * Retrieve the question by part and packet
     * 
     * @return \Illuminate\Http\Request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // Get query requests
        $test = $request->query('test');
        $part = $request->query('part');

        // Get the parts
        $parts = PaketSoal::join('tes','paket_soal.id_tes','=','tes.id_tes')->where('tes.path','=',$test)->where('status','=',1)->orderBy('part','asc')->get();

        // Get the questions
        $questions = Soal::join('paket_soal','soal.id_paket','=','paket_soal.id_paket')->join('tes','paket_soal.id_tes','=','tes.id_tes')->where('tes.path','=',$test)->where('part','=',$part)->where('status','=',1)->orderBy('nomor','asc')->get();
        if(count($questions) > 0){
            foreach($questions as $question){
                $question->makeHidden('access_token'); // Hide column
                $soal = json_decode($question->soal, true);
                unset($soal[0]['jawaban']);
                $question->soal = $soal;
            }
        }

        // Response
        return response()->json([
            'parts' => $parts,
            'questions' => $questions,
        ], 200);
    }

    /**
     * Authenticate the test by part and packet
     * 
     * @return \Illuminate\Http\Request
     * @return \Illuminate\Http\Response
     */
    public function auth(Request $request)
    {
        // Get the packet
        $packet = PaketSoal::join('tes','paket_soal.id_tes','=','tes.id_tes')->where('tes.path','=',$request->test)->where('part','=',$request->part)->where('status','=',1)->first();

        // Success
        if($request->token === $packet->access_token) {
            return response()->json([
                'status' => true,
                'message' => 'Autentikasi berhasil!'
            ]);
        }
        // Failed
        else {
            return response()->json([
                'status' => false,
                'message' => 'Autentikasi gagal!'
            ]);
        }
    }

    /**
     * Submit the test
     * 
     * @return \Illuminate\Http\Request
     * @return \Illuminate\Http\Response
     */
    public function submit(Request $request)
    {
        // Check user's age
        $user = User::find($request->user_id);
        $user_age = generate_age($user->tanggal_lahir);

        // Check answers
        $score = [];
        if(count(array_filter($request->answers)) > 0) {
            foreach(array_filter($request->answers) as $number=>$answer) {
                // Get the question by number
                $question = Soal::join('paket_soal','soal.id_paket','=','paket_soal.id_paket')->join('tes','paket_soal.id_tes','=','tes.id_tes')->where('tes.path','=','ist')->where('status','=',1)->where('nomor','=',$number)->first();

                if($question) {
                    // Convert question detail from JSON to array
                    $question_detail = json_decode($question->soal, true);
                    $question_detail = is_array($question_detail) ? $question_detail[0] : [];

                    // Check answer if the type is choice or image
                    if($question->tipe_soal == 'choice' || $question->tipe_soal == 'image') {
                        // If the answer is true, so the score increments
                        if($answer == $question_detail['jawaban'])
                            $score[$question->part] = array_key_exists($question->part, $score) ? ++$score[$question->part] : 1;
                    }
                    // Check answer if the type is essay
                    elseif($question->tipe_soal == 'essay'){
                        // Explode possibly answers
                        $essay_answer = $question_detail['jawaban'];
                        foreach($essay_answer as $essay_number=>$essay_string) {
                            $essay_answer[$essay_number] = explode(",", $essay_string);
                        }

                        // If the answer is true, so the score increments
                        if(in_array(strtolower(trim($answer)), $essay_answer[2]))
                            $score[$question->part] = array_key_exists($question->part, $score) ? ++$score[$question->part] : 2;
                        elseif(in_array(strtolower(trim($answer)), $essay_answer[1]))
                            $score[$question->part] = array_key_exists($question->part, $score) ? ++$score[$question->part] : 1;
                    }
                    // Check answer if the type is number
                    elseif($question->tipe_soal == 'number') {
                        // Explode possibly answers
                        $number_answer = str_split($question_detail['jawaban']);

                        // Check if the answer is array
                        if(is_array($answer) && is_array($number_answer)) {
                            // Sort answers before checking
                            sort($answer);
                            sort($number_answer);

                            // If the answer is true, so the score increments
                            if($answer === $number_answer)
                                $score[$question->part] = array_key_exists($question->part, $score) ? ++$score[$question->part] : 1;
                        }
                    }
                }
            }
        }

        // Process the result
        $result = []; // Array result
        $array_IST = ['SE','WA','AN','GE','ME','RA','ZR','FA','WU']; // Array IST
        $array_SW = \App\Http\Controllers\Test\ISTController::data_SW($user_age); // Array SW
        $array_IQ = \App\Http\Controllers\Test\ISTController::data_IQ(); // Array IQ
        foreach($score as $key=>$score_by_part) {
            // If GE
            if($key == 4){
                $result['RW'][$array_IST[$key-1]] = convert_GE($score_by_part);
                $result['SW'][$array_IST[$key-1]] = $array_SW[$array_IST[$key-1]][$result['RW'][$array_IST[$key-1]]];
            }
            // If not GE
            else{
                $result['RW'][$array_IST[$key-1]] = $score_by_part;
                $result['SW'][$array_IST[$key-1]] = $array_SW[$array_IST[$key-1]][$result['RW'][$array_IST[$key-1]]];
            }
        }
        $result['TRW'] = array_sum($result['RW']);
        $result['TSW'] = \App\Http\Controllers\Test\ISTController::data_TSW($user_age, $result['TRW']);
        $result['IQ'] = array_key_exists($result['TSW'], $array_IQ) ? $array_IQ[$result['TSW']] : 0;
        $result['age'] = $user_age;
        
        // Get data HRD
        if($user->role == 2){
            $hrd = HRD::where('id_user','=',$user->id_user)->first();
        }
        elseif($user->role == 3){
            $karyawan = Karyawan::where('id_user','=',$user->id_user)->first();
            $hrd = HRD::find($karyawan->id_hrd);
        }
        elseif($user->role == 4){
            $pelamar = Pelamar::where('id_user','=',$user->id_user)->first();
            $hrd = HRD::find($pelamar->id_hrd);
        }

        // Save the result
        $hasil = new Hasil;
        $hasil->id_hrd = isset($hrd) ? $hrd->id_hrd : 0;
        $hasil->id_user = $user->id_user;
        $hasil->id_tes = 8; // IST
        $hasil->id_paket = 0;
        $hasil->hasil = json_encode($result);
        $hasil->test_at = date("Y-m-d H:i:s");
        $hasil->save();

        // Response
        return response()->json([
            'status' => true,
            'data' => $request->all(),
            'score' => $score,
            'result' => $result,
            'message' => 'Submit berhasil!'
        ]);
    }
}