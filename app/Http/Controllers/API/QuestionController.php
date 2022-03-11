<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Test;
use App\Models\TestSetting;
use App\Models\Packet;
use App\Models\Question;
use App\Models\Result;

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
        $test_ = $request->query('test');
        $part_ = $request->query('part');
        $user_ = $request->query('user');

        // Get the test
        $test = Test::where('code','=',$test_)->first();

        // Get the test settings
        $test_settings = TestSetting::whereHas('packet', function (Builder $query) use ($part_) {
            return $query->where('part','=',$part_)->where('status','=',1);
        })->where('company_id','=',1)->first();

        // Get the parts
        $parts = Packet::where('test_id','=',$test->id)->where('status','=',1)->orderBy('part','asc')->get();

        // Get questions
        $questions = Question::whereHas('packet', function (Builder $query) use ($test, $part_) {
            return $query->where('test_id','=',$test->id)->where('part','=',$part_)->where('status','=',1);
        })->where('is_example','=',0)->orderBy('number','asc')->get();
        if(count($questions) > 0){
            foreach($questions as $key=>$question) {
                $question->makeHidden('access_token'); // Hide column
                $q = json_decode($question->description, true);
                unset($q[0]['jawaban']);
                $question->description = $q;

                $questions[$key]->part = $questions[$key]->packet->part;
                $questions[$key]->type = $questions[$key]->packet->type;
                $questions[$key]->tutorial = $questions[$key]->packet->description;
                $questions[$key]->exam_time = $test_settings ? $test_settings->exam_time : 0;
                $questions[$key]->memorizing_time = $test_settings ? $test_settings->memorizing_time : 0;
                $questions[$key]->is_auth = $test_settings ? $test_settings->is_auth : 0;
                $questions[$key]->access_token = $test_settings ? $test_settings->access_token : '';
            }
        }

        // Get the examples
        $examples = Question::whereHas('packet', function (Builder $query) use ($test, $part_) {
            return $query->where('test_id','=',$test->id)->where('part','=',$part_)->where('status','=',1);
        })->where('is_example','=',1)->orderBy('number','asc')->get();
        if(count($examples) > 0){
            foreach($examples as $key=>$example) {
                $example->makeHidden('access_token'); // Hide column
                $q = json_decode($example->description, true);
                unset($q[0]['jawaban']);
                $example->description = $q;
                $examples[$key]->part = $examples[$key]->packet->part;
                $examples[$key]->type = $examples[$key]->packet->type;
            }
        }

        // Response
        return response()->json([
            'parts' => $parts,
            'questions' => $questions,
            'examples' => $examples,
            'test_settings' => $test_settings
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
        // Get the part
        $part_ = $request->part;

        // Get the test settings
        $test_settings = TestSetting::whereHas('packet', function (Builder $query) use ($part_) {
            return $query->where('part','=',$part_)->where('status','=',1);
        })->where('company_id','=',1)->first();

        // Success
        if($request->token === $test_settings->access_token) {
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
        $user_age = generate_age($user->attribute->birthdate);

        // Get the test
        $test = Test::where('code','=','ist')->first();

        // Check answers
        $score = [];
        if(count(array_filter($request->answers)) > 0) {
            foreach(array_filter($request->answers) as $number=>$answer) {
                // Get the question by number
                $question = Question::whereHas('packet', function (Builder $query) use ($test) {
                    return $query->where('test_id','=',$test->id)->where('status','=',1);
                })->where('is_example','=',0)->where('number','=',$number)->first();

                if($question) {
                    // Convert question detail from JSON to array
                    $question_detail = json_decode($question->description, true);
                    $question_detail = is_array($question_detail) ? $question_detail[0] : [];

                    // Check answer if the type is choice or image
                    if($question->packet->type == 'choice' || $question->packet->type == 'image' || $question->packet->type == 'choice-memo') {
                        // If the answer is true, so the score increments
                        if($answer == $question_detail['jawaban'])
                            $score[$question->packet->part] = array_key_exists($question->packet->part, $score) ? ++$score[$question->packet->part] : 1;
                    }
                    // Check answer if the type is essay
                    elseif($question->packet->type == 'essay'){
                        // Explode possibly answers
                        $essay_answer = $question_detail['jawaban'];
                        foreach($essay_answer as $essay_number=>$essay_string) {
                            $essay_answer[$essay_number] = explode(",", $essay_string);
                        }

                        // If the answer is true, so the score increments
                        if(in_array(strtolower(trim($answer)), $essay_answer[2])) {
							if(array_key_exists($question->packet->part, $score))
								$score[$question->packet->part] += 2;
							else
								$score[$question->packet->part] = 2;
						}
                        elseif(in_array(strtolower(trim($answer)), $essay_answer[1])) {
							if(array_key_exists($question->packet->part, $score))
								$score[$question->packet->part]++;
							else
								$score[$question->packet->part] = 1;
                        }
                    }
                    // Check answer if the type is number
                    elseif($question->packet->type == 'number') {
                        // Explode possibly answers
                        $number_answer = str_split($question_detail['jawaban']);

                        // Check if the answer is array
                        if(is_array($answer) && is_array($number_answer)) {
                            // Sort answers before checking
                            sort($answer);
                            sort($number_answer);

                            // If the answer is true, so the score increments
                            if($answer === $number_answer)
                                $score[$question->packet->part] = array_key_exists($question->packet->part, $score) ? ++$score[$question->packet->part] : 1;
                        }
                    }
                }
            }
        }

        // Process the result
        $array = []; // Array
        $array_IST = ['SE','WA','AN','GE','RA','ZR','FA','WU','ME']; // Array IST
        $array_SW = \App\Http\Controllers\Test\ISTController::data_SW($user_age); // Array SW
        $array_IQ = \App\Http\Controllers\Test\ISTController::data_IQ(); // Array IQ
        foreach($array_IST as $letter) {
            $array['RW'][$letter] = 0;
            $array['SW'][$letter] = 0;
        }
        foreach($score as $key=>$score_by_part) {
            // If GE
            if($key == 4) {
                $array['RW'][$array_IST[$key-1]] = convert_GE($score_by_part);
                $array['SW'][$array_IST[$key-1]] = array_key_exists($array_IST[$key-1], $array_SW) ? $array_SW[$array_IST[$key-1]][$array['RW'][$array_IST[$key-1]]] : 0;
            }
            // If not GE
            else {
                $array['RW'][$array_IST[$key-1]] = $score_by_part;
                $array['SW'][$array_IST[$key-1]] = array_key_exists($array_IST[$key-1], $array_SW) ? $array_SW[$array_IST[$key-1]][$array['RW'][$array_IST[$key-1]]] : 0;
            }
        }
        $array['TRW'] = array_sum($array['RW']);
        $array['TSW'] = \App\Http\Controllers\Test\ISTController::data_TSW($user_age, $array['TRW']);
        $array['IQ'] = array_key_exists($array['TSW'], $array_IQ) ? $array_IQ[$array['TSW']] : 0;
        $array['age'] = $user_age;
        $array['answers'] = $request->answers;
        $array['doubts'] = $request->doubts;

        // Save the result
        $result = new Result;
        $result->user_id = $user->id;
        $result->company_id = $user->attribute->company_id;
        $result->test_id = $test->id;
        $result->packet_id = 0;
        $result->result = json_encode($array);
        $result->save();

        // Response
        return response()->json([
            'status' => true,
            'data' => $request->all(),
            'score' => $score,
            'result' => $array,
            'message' => 'Submit berhasil!'
        ]);
    }

    /**
     * Submit the example
     * 
     * @return \Illuminate\Http\Request
     * @return \Illuminate\Http\Response
     */
    public function submitExample(Request $request)
    {
        // Check answers
        $score = [];
        $checkAnswers = [];
        $keyAnswers = [];

        // Get the part and test
        $part = $request->part;
        $test = Test::where('code','=','ist')->first();

        if(count(array_filter($request->answers)) > 0) {
            foreach(array_filter($request->answers) as $number=>$answer) {
                // Get the question by number
                $question = Question::whereHas('packet', function (Builder $query) use ($test, $part) {
                    return $query->where('test_id','=',$test->id)->where('part','=',$part)->where('status','=',1);
                })->where('is_example','=',1)->where('number','=',$number)->first();

                // $question = Soal::join('paket_soal','soal.id_paket','=','paket_soal.id_paket')->join('tes','paket_soal.id_tes','=','tes.id_tes')->where('tes.path','=','ist')->where('paket_soal.part','=',$request->part)->where('status','=',1)->where('is_example','=',1)->where('nomor','=',$number)->first();

                if($question) {
                    // Convert question detail from JSON to array
                    $question_detail = json_decode($question->description, true);
                    $question_detail = is_array($question_detail) ? $question_detail[0] : [];

                    // Check answer if the type is choice or image
                    if($question->packet->type == 'choice' || $question->packet->type == 'image' || $question->packet->type == 'choice-memo') {
                        if($answer == $question_detail['jawaban']) $checkAnswers[$number] = true;
                        else $checkAnswers[$number] = false;
                        $keyAnswers[$number] = $question_detail['jawaban'];
                    }
                    // Check answer if the type is essay
                    elseif($question->packet->type == 'essay'){
                        // Explode possibly answers
                        $essay_answer = $question_detail['jawaban'];
                        foreach($essay_answer as $essay_number=>$essay_string) {
                            $essay_answer[$essay_number] = explode(",", $essay_string);
                        }

                        if(in_array(strtolower(trim($answer)), $essay_answer[2]))
                            $checkAnswers[$number] = true;
                        elseif(in_array(strtolower(trim($answer)), $essay_answer[1]))
                            $checkAnswers[$number] = true;
                        else
                            $checkAnswers[$number] = false;

                        $essay_answers = array_filter(array_merge($essay_answer[1], $essay_answer[2]));
                        $keyAnswers[$number] = implode(' / ', $essay_answers);
                    }
                    // Check answer if the type is number
                    elseif($question->packet->type == 'number') {
                        // Explode possibly answers
                        $number_answer = str_split($question_detail['jawaban']);

                        // Check if the answer is array
                        if(is_array($answer) && is_array($number_answer)) {
                            // Sort answers before checking
                            sort($answer);
                            sort($number_answer);

                            if($answer === $number_answer) $checkAnswers[$number] = true;
                            else $checkAnswers[$number] = false;
                            $keyAnswers[$number] = implode(', ', $number_answer);
                        }
                    }
                }
            }
        }

        // Response
        return response()->json([
            'answers' => $request->answers,
            'checkAnswers' => $checkAnswers,
            'keyAnswers' => $keyAnswers,
        ]);
    }
}
