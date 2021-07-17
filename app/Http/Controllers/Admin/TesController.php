<?php

namespace App\Http\Controllers\Admin;

use Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Tes;

class TesController extends Controller
{    
    /**
     * Menampilkan data tes
     * 
     * @return \Illuminate\Http\Request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // Data tes
        $tes = Tes::all();

        // View
        return view('admin/tes/index', [
            'tes' => $tes
        ]);
    }
}