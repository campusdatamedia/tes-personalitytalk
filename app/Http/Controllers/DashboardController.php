<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use App\Models\Test;
use App\Models\Selection;

class DashboardController extends Controller
{    
    /**
     * Menampilkan dashboard
     * 
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Default
        $selection = false;
        $images = ['lightning-bolts.svg','arrows.svg','thoughts.svg','gears.svg','keys.svg','lightning-bolts.svg','arrows.svg','thoughts.svg','gears.svg','keys.svg'];

        // Get tests
        if(Auth::user()->role->is_global === 1) {
            $tests = Test::all();
        }
        elseif(Auth::user()->role->is_global === 0) {
            $tests = Auth::user()->attribute->position->tests;
        }

        // Get the selection
        if(Auth::user()->role_id == role('applicant')) {
            $selection = Selection::where('user_id','=',Auth::user()->id)->first();
        }

        // View
        return view('dashboard/index', [
            'images' => $images,
            'selection' => $selection,
            'tests' => $tests,
        ]);
    }
}
