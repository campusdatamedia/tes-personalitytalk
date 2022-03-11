<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
// use App\Models\Pelamar;
// use App\Models\Tes;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // View::composer('*', function($view){
        //     if(Auth::check()){
        //         if(Auth::user()->role == 4){
        //             // Get tes
        //             // $tes = Tes::all();
                    
        //             // Get tes tersedia
        //             $pelamar = Pelamar::join('lowongan','pelamar.posisi','=','lowongan.id_lowongan')->join('posisi','lowongan.posisi','=','posisi.id_posisi')->where('id_user','=',Auth::user()->id_user)->first();
        //             if($pelamar->tes != ''){
        //                 $ids = explode(',', $pelamar->tes);
        //                 $tes = Tes::whereIn('id_tes', $ids)->get();
                        
        //                 // Send variable
        //                 view()->share('global_tes', $tes);
        //             }
        //             else{
        //                 // Send variable
        //                 view()->share('global_tes', false);
        //             }

        //         }
        //     }
        // });
    }
}
