<?php

namespace App\Http\Controllers\Test;

use Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Packet;
use App\Models\Result;

class DISC40Controller extends Controller
{    
    /**
     * Display
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public static function index(Request $request, $path, $test, $selection)
    {
        // Get the packet and questions
        $packet = Packet::where('test_id','=',$test->id)->where('status','=',1)->first();
        $questions = $packet ? $packet->questions()->orderBy('number','asc')->get() : [];
        foreach($questions as $question) {
            $question->description = json_decode($question->description, true);
        }

        // View
        return view('test/'.$path, [
            'packet' => $packet,
            'path' => $path,
            'questions' => $questions,
            'selection' => $selection,
            'test' => $test,
        ]);
    }

    /**
     * Store
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public static function store(Request $request)
    {
        // Get the packet and questions
        $packet = Packet::where('test_id','=',$request->test_id)->where('status','=',1)->first();
        $questions = $packet ? $packet->questions()->orderBy('number','asc')->get() : [];
        
        // Declare variables
        $m = $request->get('m');
        $l = $request->get('l');
        $disc = array('D', 'I', 'S','C');
        $disc_m = array();
        $disc_l = array();
        $disc_score_m = array();
        $disc_score_l = array();
        foreach($questions as $question) {
            $json = json_decode($question->description, true);
            array_push($disc_m, $json[0]['disc'][$m[$question->number]]);
            array_push($disc_l, $json[0]['disc'][$l[$question->number]]);
        }

        // MOST dan LEAST
        $array_count_m = array_count_values($disc_m);
        $array_count_l = array_count_values($disc_l);
        foreach($disc as $letter){
            $disc_score_m[$letter] = array_key_exists($letter, $array_count_m) ? discScoringM($array_count_m[$letter]) : 0;
            $disc_score_l[$letter] = array_key_exists($letter, $array_count_l) ? discScoringL($array_count_l[$letter]) : 0;
        }
        
        // Convert DISC score to JSON
        $array = array('M' => $disc_score_m, 'L' => $disc_score_l);
        $array['answers']['m'] = $request->m;
        $array['answers']['l'] = $request->l;

        // Save the result
        $result = new Result;
        $result->user_id = Auth::user()->id;
        $result->company_id = Auth::user()->attribute->company_id;
        $result->test_id = $request->test_id;
        $result->packet_id = $request->packet_id;
        $result->result = json_encode($array);
        $result->save();

        // Return
        return redirect('/dashboard')->with(['message' => 'Berhasil mengerjakan tes '.$packet->test->name]);
    }
}