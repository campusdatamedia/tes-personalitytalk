<?php

// Set tanggal lengkap
if(!function_exists('setFullDate')){
    function setFullDate($date){
        $explode1 = explode(" ", $date);
        $explode2 = explode("-", $explode1[0]);
        $month = array('Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember');
        return $explode2[2]." ".$month[$explode2[1]-1]." ".$explode2[0];
    }
}

// Scoring DISC MOST
if(!function_exists('discScoringM')){
    function discScoringM($number){
        $score = round(50 * pow(2, log($number / 10, 4)));
        return $score;
    }
}

// Scoring DISC LEAST
if(!function_exists('discScoringL')){
    function discScoringL($number){
        $score = 100 - round(50 * pow(2, log($number / 10, 4)));
        return $score;
    }
}

// Meranking score
if(!function_exists('sortScore')){
    function sortScore($array){
        $ordered_array = $array;
        arsort($ordered_array);
        $i = 1;
        $last_value = '';
        foreach($ordered_array as $ordered_key=>$ordered_value){
            $ordered_array[$ordered_key] = array();
            $ordered_array[$ordered_key]['rank'] = $ordered_value == $last_value ? ($i-1) : $i;
            $ordered_array[$ordered_key]['score'] = $ordered_value;
            $last_value = $ordered_value;
            $i++;
        }
        return $ordered_array;
    }
}

// Membuat kode
if(!function_exists('setCode')){
    function setCode($array){
        $new_array = array();
        $i = 1;
        while($i<=4){
            foreach($array as $key=>$value){
                if($array[$key]['rank'] == $i){
                    if($array[$key]['score'] < 50){
                        $new_value = "L".$key;
                        array_push($new_array, $new_value);
                    }
                    else{
                        $new_value = "H".$key;
                        array_push($new_array, $new_value);
                    }
                }
            }
            $i++;
        }
        return $new_array;
    }
}

// Menghapus array yang bervalue kosong
if(!function_exists('removeEmptyArray')){
    function removeEmptyArray($array, $key = null){
        if($key == null){
            $array_count_values = array_count_values($array);
            if($array_count_values[""] == count($array)){
                unset($array);
            }
        }
        else{
            $array_count_values = array_count_values($array[$key]);
            if($array_count_values[""] == count($array[$key])){
                unset($array[$key]);
            }
        }
    }
}

// Generate string ke url
if(!function_exists('generate_url')){
    function generate_url($string){
        $url = trim($string);
        $url = strtolower($url);
        $url = str_replace(" ", "-", $url);
        return $url;
    }
}

// Hitung umur / usia
if(!function_exists('generate_age')){
    function generate_age($date){
        $birthdate = new DateTime($date);
        $today = new DateTime('today');

        $y = $today->diff($birthdate)->y;
        return $y;
    }
}

// Acak huruf
if(!function_exists('shuffleString')){
    function shuffleString($length){
        $string = '1234567890QWERTYUIOPASDFGHJKLZXCVBNMqwertyuiopasdfghjklzxcvbnm';
        $shuffle = substr(str_shuffle($string), 0, $length);
        return $shuffle;
    }
}