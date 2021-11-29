<?php

namespace App\Http\Controllers\Test;

use Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Hasil;
use App\Models\HRD;
use App\Models\Karyawan;
use App\Models\PaketSoal;
use App\Models\Pelamar;
use App\Models\Soal;
use App\Models\Tes;
use App\Models\User;

class SDIController extends Controller
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
        // Tes
        $paket = PaketSoal::where('id_tes','=',$tes->id_tes)->where('status','=',1)->first();

        // View
        return view('tes/'.$path, [
            'check' => $check,
            'paket' => $paket,
            'path' => $path,
            'seleksi' => $seleksi,
            'soal1' => self::data()['soal1'],
            'soal2' => self::data()['soal2'],
            'tes' => $tes,
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
        // Tes
        $paket = PaketSoal::where('id_paket','=',$request->id_paket)->where('status','=',1)->first();
        
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
        
        // Declare variables
        $data = [
            ['Col1a' => $request->a1, 'Col2a' => $request->b1, 'Col3a' => $request->c1],
            ['Col1a' => $request->a2, 'Col2a' => $request->b2, 'Col3a' => $request->c2],
            ['Col1a' => $request->a3, 'Col2a' => $request->b3, 'Col3a' => $request->c3],
            ['Col1a' => $request->a4, 'Col2a' => $request->b4, 'Col3a' => $request->c4],
            ['Col1a' => $request->a5, 'Col2a' => $request->b5, 'Col3a' => $request->c5],
            ['Col1a' => $request->a6, 'Col2a' => $request->b6, 'Col3a' => $request->c6],
            ['Col1a' => $request->a7, 'Col2a' => $request->b7, 'Col3a' => $request->c7],
            ['Col1a' => $request->a8, 'Col2a' => $request->b8, 'Col3a' => $request->c8],
            ['Col1a' => $request->a9, 'Col2a' => $request->b9, 'Col3a' => $request->c9],
            ['Col1a' => $request->a10, 'Col2a' => $request->b10, 'Col3a' => $request->c10],
        ];

        $data2 = [
            ['Col1b' => $request->d1, 'Col2b' => $request->e1, 'Col3b' => $request->f1],
            ['Col1b' => $request->d2, 'Col2b' => $request->e2, 'Col3b' => $request->f2],
            ['Col1b' => $request->d3, 'Col2b' => $request->e3, 'Col3b' => $request->f3],
            ['Col1b' => $request->d4, 'Col2b' => $request->e4, 'Col3b' => $request->f4],
            ['Col1b' => $request->d5, 'Col2b' => $request->e5, 'Col3b' => $request->f5],
            ['Col1b' => $request->d6, 'Col2b' => $request->e6, 'Col3b' => $request->f6],
            ['Col1b' => $request->d7, 'Col2b' => $request->e7, 'Col3b' => $request->f7],
            ['Col1b' => $request->d8, 'Col2b' => $request->e8, 'Col3b' => $request->f8],
            ['Col1b' => $request->d9, 'Col2b' => $request->e9, 'Col3b' => $request->f9],
            ['Col1b' => $request->d10, 'Col2b' => $request->e10, 'Col3b' => $request->f10],
        ];
        
        // Soal 1-10
        $A = 0;
        $B = 0;
        $C = 0;
        foreach($data as $value){
            $A = $A+$value['Col1a'];
            $B = $B+$value['Col2a'];
            $C = $C+$value['Col3a'];
        }

        // Soal 11-20
        $D = 0;
        $E = 0;
        $F = 0;
        foreach($data2 as $value){
            $D = $D+$value['Col1b'];
            $E = $E+$value['Col2b'];
            $F = $F+$value['Col3b'];
        }
        
        // Data to array
        $array = array(
            'A' => $A,
            'B' => $B,
            'C' => $C,
            'D' => $D,
            'E' => $E,
            'F' => $F,
        );
        $array['answers'] = array_merge($data, $data2);

        // Menyimpan data
        $hasil = new Hasil;
        $hasil->id_hrd = isset($hrd) ? $hrd->id_hrd : 0;
        $hasil->id_user = Auth::user()->id_user;
        $hasil->id_tes = $request->id_tes;
        $hasil->id_paket = $request->id_paket;
        $hasil->hasil = json_encode($array);
        $hasil->test_at = date("Y-m-d H:i:s");
        $hasil->save();

        // Return
        return redirect('/dashboard')->with(['message' => 'Berhasil mengerjakan tes SDI']);
    }
    
    /**
     * Data soal
     *
     * @return \Illuminate\Http\Response
     */
    public static function data(){
        
        $soal1 = array(
            array('id' => '1', 'header' => 'Saya sangat menikmati sesuatu ketika saya ....',
            'val1' => 'a1' ,'soal1' => 'Membantu orang lain melakukan apa yang ingin mereka lakukan',
            'val2' => 'b1', 'soal2' => 'Meminta orang lain untuk melakukan apa yang ingin saya lakukan',
            'val3' => 'c1', 'soal3' => 'Melakukan apa yang ingin saya lakukan tanpa harus bergantung pada orang lain'),

            array('id' => '2', 'header' => 'Hampir tiap waktu saya nampaknya seperti ....',
            'val1' => 'a2', 'soal1' => 'Seseorang yang peka yang cepat merespon kebutuhan orang lain',
            'val2' => 'b2', 'soal2' => 'Seseorang yang enerjik cepat melihat peluang dan keuntungan',
            'val3' => 'c2', 'soal3' => 'Seseorang yang praktis yang tclassak tergesa-gesa terhadap sesuatu sebelum saya siap'),

            array('id' => '3', 'header' => 'Ketika saya bertemu seseorang untuk pertama kali, saya sering bersikap seperti ....',
            'val1' => 'a3', 'soal1' => 'Peduli dengan apakah mereka akap menganggap saya orang yang menyenangkan atau tidak',
            'val2' => 'b3', 'soal2' => 'Sangat ingin tahu dari mereka jika ada sesuatu tentang saya',
            'val3' => 'c3', 'soal3' => 'Berhati-hati sampai saya mempelajari apa yang mungkin mereka inginkan dari saya'),

            array('id' => '4', 'header' => 'Hampir setiap waktu saya menemukan diri saya seperti ....',
            'val1' => 'a4', 'soal1' => 'Seorang yang menyenangkan, yang dapat diandalkan orang lain',
            'val2' => 'b4', 'soal2' => 'Seorang yang kuat, yang memberikan pengarahan untuk orang lain',
            'val3' => 'c4', 'soal3' => 'Seorang pemikir, yang mempelajari sesuatu sebelum bertindak'),

            array('id' => '5', 'header' => 'Saya merasa sangat puas, ketika ....',
            'val1' => 'a5', 'soal1' => 'Keputusan terbesar telah dibuat oleh orang lain, dan bagaimana saya dapat membantu agar selesai',
            'val2' => 'b5', 'soal2' => 'Orang lain mengandalkan saya untuk membuat keputusan besar dan mengajarkan mereka apa yang harus dilakukan',
            'val3' => 'c5', 'soal3' => 'Saya telah mengambil waktu untuk mempelajari sebuah keputusan besar dan mengukur arah tindakan terbaik saya'),

            array('id' => '6', 'header' => 'Orang-orang yang mengenal saya dengan baik, melihat saya sebagai seseorang yang bisa diandalkan ....',
            'val1' => 'a6', 'soal1' => 'Untuk dapat mereka percaya dan setia',
            'val2' => 'b6', 'soal2' => 'Untuk ambisi yang tinggi dan inisiatif',
            'val3' => 'c6', 'soal3' => 'Untuk ketegasan dalam keyakinan dan pendirian saya'),

            array('id' => '7', 'header' => 'Saya sangat menyukai untuk ....',
            'val1' => 'a7', 'soal1' => 'Melakukan yang terbaik saya bisa dan mempercayai orang lain untuk mengakui kontribusi saya',
            'val2' => 'b7', 'soal2' => 'Mengambil peran utama dalam membangun peluang dan mempengaruhi keputusan',
            'val3' => 'c7', 'soal3' => 'Sabar, praktis dan yakin terhadap apa yang saya lakukan'),

            array('id' => '8', 'header' => 'Saya akan menggambarkan diri saya sebagai seseorang yang setiap saat ....',
            'val1' => 'a8', 'soal1' => 'Ramah, terbuka dan seseorang yang melihat hal baik baik pada hampir setiap orang',
            'val2' => 'b8', 'soal2' => 'Enerjik, percaya diri, dan melihat kesempatan yang orang lain tclassak lihat',
            'val3' => 'c8', 'soal3' => 'Berhati-hati dan adil, dan orang yang berdiri pada apa yang dipercayainya'),

            array('id' => '9', 'header' => 'Saya menemukan banyak hubungan yang menyenangkan dimana saya dapat menjadi ....',
            'val1' => 'a9', 'soal1' => 'Dukungan untuk seorang pemimpin yang kuat yang saya percaya',
            'val2' => 'b9', 'soal2' => 'Seseorang yang mampu menjadi pemimpin yang ingin dijadikan panutan bagi orang lain',
            'val3' => 'c9', 'soal3' => 'Seorang pemimpin atau bukan, tapi bebas untuk mengejar kebebasan saya sendiri'),

            array('id' => '10', 'header' => 'Ketika saya dalam keadaan terbaik saya, saya sangat menikmati ....',
            'val1' => 'a10', 'soal1' => 'Melihat manfaat bagi orang lain, dari apa yang saya telah mampu lakukan untuk mereka',
            'val2' => 'b10', 'soal2' => 'Orang lain menunjuk saya untuk memimpin dan mengarahkan mereka dan memberi mereka makna',
            'val3' => 'c10', 'soal3' => 'Menjadi bos untuk diri sendiri dan melakukan sesuatu untuk  diri sendiri dan oleh diri sendiri'),
        );

        $soal2 = array(
            array('id' => '1', 'header' => 'Ketika menjumpai lingkungan yang berseberangan dengan apa
            yang saya lakukan, saya seringkali bersikap ....',
            'val1' => 'd1' ,'soal1' => 'Menghentikan apa yang saya lakukan dan mengesampingkan
            keinginan saya agar lebih membantu',
            'val2' => 'e1', 'soal2' => 'Agresif dan mengusahakan hak saya untuk melakukannya',
            'val3' => 'f1', 'soal3' => 'Menjadi lebih berhati-hati dan mengawasi posisi saya
            dengan sangat hati-hati'),

            array('id' => '2', 'header' => 'Jika saya memutuskan untuk mengalahkan seseorang, saya
            mencoba untuk ....',
            'val1' => 'd2' ,'soal1' => 'Merubah apa yang saya lakukan dan mencoba membuat agar
            lebih diterima orang',
            'val2' => 'e2', 'soal2' => 'Mencari kelemahan pada argumen orang lain dan
            menekankan nilai yang kuat pada argument sendiri',
            'val3' => 'f2', 'soal3' => 'Menampakkan rasa respek untuk bersaing secara logis dan
            adil'),

            array('id' => '3', 'header' => 'Saat bergaul dengan orang yang sulit, saya biasanya ....',
            'val1' => 'd3' ,'soal1' => 'Menganggapnya mudah dan mengiyakan harapan-harapan
            mereka untuk sementara',
            'val2' => 'e3', 'soal2' => 'Menganggap mereka sebagai tantangan untuk dikalahkan',
            'val3' => 'f3', 'soal3' => 'Menghormati hak mereka dan meminta mereka juga
            menghormati hak dan kepentingan saya'),

            array('id' => '4', 'header' => 'Ketika seseorang sangat tclassak setuju dengan saya, saya
            cenderung ....',
            'val1' => 'd4' ,'soal1' => 'Menyerah dan mengikuti cara orang itu kecuali itu
            sangat penting bagi saya',
            'val2' => 'e4', 'soal2' => 'Segera menantang orang tersebut dan berdebat sekeras
            mungkin',
            'val3' => 'f4', 'soal3' => 'Memisahkan diri dari situasi tersebut sampai akhirnya
            saya yakin akan posisi saya'),

            array('id' => '5', 'header' => 'Ketika seseorang secara terbuka melawan saya, saya biasanya ....',
            'val1' => 'd5' ,'soal1' => 'Menyerah demi keharmonisan dan mengandalkan kepekaan
            orang lain untuk melakukan hal yang benar atas saya',
            'val2' => 'e5', 'soal2' => 'Menerima kenyataan bahwa ini adalah perang dan
            menyiapkan diri untuk menang',
            'val3' => 'f5', 'soal3' => 'Berusaha menarik diri dari pergaulan tersebut dan
            mencari yang sesuai dengan saya'),

            array('id' => '6', 'header' => 'Jika saya tclassak mendapatkan apa yang saya inginkan dalam
            sebuah hubungan, saya biasanya bersikap ....',
            'val1' => 'd6' ,'soal1' => 'Tetap berharap dan percaya sesuatu akan merubah mereka
            seiring berjalannya waktu',
            'val2' => 'e6', 'soal2' => 'Menjadi lebih agresif dan persuasif, & lebih berusaha
            keras untuk mendapatkan apa yang saya mau',
            'val3' => 'f6', 'soal3' => 'Mengabaikan hubungan itu dan mencari yang lain untuk
            apa yang saya mau'),

            array('id' => '7', 'header' => 'Ketika saya merasa orang lain mengambil manfaat dari saya,
            saya biasanya ....',
            'val1' => 'd7' ,'soal1' => 'Berbalik pada seseorang yang memiliki pengalaman lebih
            dan meminta saran mereka',
            'val2' => 'e7', 'soal2' => 'Menegaskan hak saya dan berjuang atas apa yang berhak
            untuk saya',
            'val3' => 'f7', 'soal3' => 'Menyatakan hak-hak saya dengan jelas, dan berpegang
            teguh pada kejujuran di sekitar kita'),

            array('id' => '8', 'header' => 'Ketika orang lain bersikeras pada jalan mereka sendiri,
            saya cenderung ....',
            'val1' => 'd8' ,'soal1' => 'Mengesampingkan keinginan saya sementara waktu dan
            mengikuti keinginan mereka',
            'val2' => 'e8', 'soal2' => 'Membalas argumennya dan berusaha untuk merubah pikiran
            orang itu',
            'val3' => 'f8', 'soal3' => 'Menghormati hak orang itu untuk menentukan jalannya
            sendiri, sepanjang tclassak berkaitan dengan saya'),

            array('id' => '9', 'header' => 'Ketika orang lain secara terbuka mengkritik saya, saya
            seringkali bersikap ....',
            'val1' => 'd9' ,'soal1' => 'Menenangkan mereka, dan meredakan amarah mereka
            terhadap saya',
            'val2' => 'e9', 'soal2' => 'Marah dan tantang apa hak mereka untuk mengkritisi',
            'val3' => 'f9', 'soal3' => 'Menjadi lebih waspada dan menganalisa setiap kritik
            dengan detail'),

            array('id' => '10', 'header' => 'Ketika seseorang dengan jelas menyalahgunakan kepercayaan
            saya, saya cenderung ....',
            'val1' => 'd10' ,'soal1' => 'Merasa tindakan itu lebih menyakitkan untuk diri mereka
            sendiri daripada dampak pada saya',
            'val2' => 'e10', 'soal2' => 'Marah pada orang itu dan jika perlu mengambil langkah
            untuk membalasnya',
            'val3' => 'f10', 'soal3' => 'Menganalisa apa yang salah dan menghindari hal serupa
            terulang di masa depan'),
        );
        
        return array(
            'soal1' => $soal1,
            'soal2' => $soal2,
        );
    }
}