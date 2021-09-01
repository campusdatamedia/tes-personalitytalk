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
        $part = $request->query('part');
        $packet = $request->query('packet');

        // Get the questions
        $questions = Soal::join('paket_soal','soal.id_paket','=','paket_soal.id_paket')->where('part','=',$part)->where('soal.id_paket','=',$packet)->where('status','=',1)->get();
        if(count($questions) > 0){
            foreach($questions as $question){
                $soal = json_decode($question->soal, true);
                unset($soal[0]['jawaban']);
                $question->soal = $soal;
            }
        }

        // Response
        return response()->json([
            'questions' => $questions
        ], 200);
    }

    /**
     * Retrieve the question by part, packet and number
     * 
     * @return \Illuminate\Http\Request
     * @return \Illuminate\Http\Response
     */
    public function detail(Request $request)
    {
        // IST
        if($request->query('path') == 'ist') {
            // Get query requests
            $part = $request->query('part');
            $packet = $request->query('packet');
            $number = $request->query('number');

            // Get the question
            $question = Soal::join('paket_soal','soal.id_paket','=','paket_soal.id_paket')->where('part','=',$part)->where('soal.id_paket','=',$packet)->where('soal.nomor','=',$number)->where('status','=',1)->first();
            if($question){
                $soal = json_decode($question->soal, true);
                unset($soal[0]['jawaban']);
                $question->soal = $soal;
            }

            // Response
            return response()->json([
                'question' => $question
            ], 200);
        }
    }
}