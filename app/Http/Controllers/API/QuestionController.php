<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\PaketSoal;
use App\Soal;

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
        return response()->json([
            'status' => true,
            'data' => $request->all(),
            'message' => 'Submit berhasil!'
        ]);
    }
}