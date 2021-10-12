<?php

namespace App\Http\Controllers\Test;

use Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Hasil;
use App\HRD;
use App\Karyawan;
use App\PaketSoal;
use App\Pelamar;
use App\Soal;
use App\Tes;
use App\User;

class PapikostickController extends Controller
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
            'soal' => self::data(),
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
        $soal = self::data();
        $jawaban = $request->get('jawaban');
        $konversi_jawaban = array();
        foreach($soal as $key=>$data) {
            $konversi_jawaban[$key+1] = $data['jawaban'.$jawaban[$key+1]];
        }        
        $count_jawaban = array_count_values($konversi_jawaban);
        $huruf = ["N","G","A","L","P","I","T","V","X","S","B","O","R","D","C","Z","E","K","F","W"];
        $array = array();
        foreach($huruf as $h){
            $array[$h] = array_key_exists($h, $count_jawaban) ? $count_jawaban[$h] : 0;
        }
        $array['answers'] = $jawaban;

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
        return redirect('/dashboard')->with(['message' => 'Berhasil mengerjakan tes Papikostick']);
    }
    
    /**
     * Data soal
     *
     * @return \Illuminate\Http\Response
     */
    public static function data(){
        $data = array(
            // Nomor 1
            array(
                'soalA' => 'Saya seorang pekerja <i><u>“keras”</u></i>',
                'soalB' => 'Saya <i><u>bukan</u></i> seorang pemurung',
                'jawabanA' => 'G',
                'jawabanB' => 'E',
            ),
            // Nomor 2
            array(
                'soalA' => 'Saya suka bekerja <i><u>lebih baik</u></i> dari orang lain',
                'soalB' => 'Saya suka mengerjakan <i><u>apa yang sedang saya kerjakan</u></i>, sampai selesai',
                'jawabanA' => 'A',
                'jawabanB' => 'N',
            ),
            // Nomor 3
            array(
                'soalA' => 'Saya suka <i><u>menunjukkan caranya</u></i> melaksanakan sesuatu hal',
                'soalB' => 'Saya ingin bekerja <i><u>sebaik mungkin</u></i>',
                'jawabanA' => 'P',
                'jawabanB' => 'A',
            ),
            // Nomor 4
            array(
                'soalA' => 'Saya suka <i><u>berkelakar</u></i>',
                'soalB' => 'Saya senang <i><u>mengatakan kepada orang lain, apa yang harus dilakukannya</u></i>',
                'jawabanA' => 'X',
                'jawabanB' => 'P',
            ),
            // Nomor 5
            array(
                'soalA' => 'Saya suka <i><u>menggabungkan diri</u></i> dengan kelompok-kelompok',
                'soalB' => 'Saya suka <i><u>diperhatikan</u></i> oleh kelompok-kelompok',
                'jawabanA' => 'B',
                'jawabanB' => 'X',
            ),
            // Nomor 6
            array(
                'soalA' => 'Saya senang <i><u>bersahabat intim</u></i> dengan seseorang',
                'soalB' => 'Saya senang bersahabat dengan <i><u>sekelompok orang</u></i>',
                'jawabanA' => 'O',
                'jawabanB' => 'B',
            ),
            // Nomor 7
            array(
                'soalA' => 'Saya cepat <i><u>berubah</u></i> bila hal itu diperlukan',
                'soalB' => 'Saya berusaha untuk <i><u>intim dengan teman-teman</u></i>',
                'jawabanA' => 'Z',
                'jawabanB' => 'O',
            ),
            // Nomor 8
            array(
                'soalA' => 'Saya suka <i><u>“membalas dendam”</u></i> bila saya benar-benar disakiti',
                'soalB' => 'Saya suka melakukan hal-hal yang <i><u>baru dan berbeda</u></i>',
                'jawabanA' => 'K',
                'jawabanB' => 'Z',
            ),
            // Nomor 9
            array(
                'soalA' => 'Saya ingin <i><u>atasan</u></i> saya menyukai saya',
                'soalB' => 'Saya suka <i><u>mengatakan kepada orang lain</u></i>, bila mereka salah',
                'jawabanA' => 'F',
                'jawabanB' => 'K',
            ),
            // Nomor 10
            array(
                'soalA' => 'Saya suka <i><u>mengikuti</u></i> perintah-perintah yang diberikan kepada saya',
                'soalB' => 'Saya suka menyenangkan hati <i><u>orang yang memimpin saya</u></i>',
                'jawabanA' => 'W',
                'jawabanB' => 'F',
            ),
            // Nomor 11
            array(
                'soalA' => 'Saya mencoba <i><u>sekuat tenaga</u></i>',
                'soalB' => 'Saya seorang yang <i><u>tertib</u></i>. Saya meletakan segala sesuatu pada tempatnya',
                'jawabanA' => 'G',
                'jawabanB' => 'C',
            ),
            // Nomor 12
            array(
                'soalA' => 'Saya membuat orang lain melakukan apa yang <i><u>saya</u></i> inginkan',
                'soalB' => 'Saya <i><u>bukan</u></i> orang yang cepat gusar',
                'jawabanA' => 'L',
                'jawabanB' => 'E',
            ),
            // Nomor 13
            array(
                'soalA' => '<i><u>Saya</u></i> suka mengatakan kepada kelompok, apa yang harus saya lakukan',
                'soalB' => 'Saya <i><u>menekuni</u></i> satu pekerjaan sampai selesai',
                'jawabanA' => 'P',
                'jawabanB' => 'N',
            ),
            // Nomor 14
            array(
                'soalA' => 'Saya ingin tampak <i><u>bersemangat dan menarik</u></i>',
                'soalB' => 'Saya ingin menjadi <i><u>sangat sukses</u></i>',
                'jawabanA' => 'X',
                'jawabanB' => 'A',
            ),
            // Nomor 15
            array(
                'soalA' => 'Saya suka <i><u>menyelaraskan diri</u></i> dengan kelompok',
                'soalB' => 'Saya suka <i><u>membantu orang lain</u></i> menentukan pendapatnya',
                'jawabanA' => 'B',
                'jawabanB' => 'P',
            ),
            // Nomor 16
            array(
                'soalA' => 'Saya cemas kalau orang lain <i><u>tidak menyukai saya</u></i>',
                'soalB' => 'Saya senang kalau orang-orang <i><u>memperhatikan</u></i> saya',
                'jawabanA' => 'O',
                'jawabanB' => 'X',
            ),
            // Nomor 17
            array(
                'soalA' => 'Saya suka mencoba <i><u>sesuatu yang baru</u></i>',
                'soalB' => 'Saya lebih suka bekerja <i><u>bersama orang-orang</u></i> daripada bekerja sendiri',
                'jawabanA' => 'Z',
                'jawabanB' => 'B',
            ),
            // Nomor 18
            array(
                'soalA' => 'Kadang-kadang saya <i><u>menyalahkan orang lain</u></i> bila terjadi sesuatu kesalahan',
                'soalB' => 'Saya cemas bila <i><u>seseorang tidak menyukai</u></i> saya',
                'jawabanA' => 'K',
                'jawabanB' => 'O',
            ),
            // Nomor 19
            array(
                'soalA' => 'Saya suka <i><u>menyenangkan hati</u></i> orang yang memimpin saya',
                'soalB' => 'Saya suka mencoba pekerjaan-pekerjaan yang <i><u>baru dan berbeda</u></i>',
                'jawabanA' => 'F',
                'jawabanB' => 'Z',
            ),
            // Nomor 20
            array(
                'soalA' => 'Saya menyukai <i><u>petunjuk</u></i> yang terinci untuk melakukan sesuatu pekerjaan',
                'soalB' => 'Saya suka mengatakan kepada orang lain bila <i><u>mereka menganggu saya</u></i>',
                'jawabanA' => 'W',
                'jawabanB' => 'K',
            ),
            // Nomor 21
            array(
                'soalA' => 'Saya selalu mencoba <i><u>sekuat tenaga</u></i>',
                'soalB' => 'Saya senang bekerja dengan sangat <i><u>cermat dan hati-hati</u></i>',
                'jawabanA' => 'G',
                'jawabanB' => 'D',
            ),
            // Nomor 22
            array(
                'soalA' => 'Saya adalah seorang pemimpin yang <i><u>baik</u></i>',
                'soalB' => 'Saya mengorganisir <i><u>tugas-tugas secara baik</u></i>',
                'jawabanA' => 'L',
                'jawabanB' => 'C',
            ),
            // Nomor 23
            array(
                'soalA' => 'Saya mudah menjadi <i><u>gusar</u></i>',
                'soalB' => 'Saya seorang yang <i><u>lambat</u></i> dalam membuat keputusan',
                'jawabanA' => 'I',
                'jawabanB' => 'E',
            ),
            // Nomor 24
            array(
                'soalA' => 'Saya senang mengerjakan <i><u>beberapa pekerjaan</u></i> pada waktu yang bersamaan',
                'soalB' => 'Bila di dalam kelompok, saya lebih suka <i><u>diam</u></i>',
                'jawabanA' => 'X',
                'jawabanB' => 'N',
            ),
            // Nomor 25
            array(
                'soalA' => 'Saya senang bila <i><u>diundang</u></i>',
                'soalB' => 'Saya ingin melakukan sesuatu <i><u>lebih baik</u></i> dari orang lain',
                'jawabanA' => 'B',
                'jawabanB' => 'A',
            ),
            // Nomor 26
            array(
                'soalA' => 'Saya suka berteman <i><u>intim</u></i> dengan teman-teman saya',
                'soalB' => 'Saya suka memberi <i><u>nasehat</u></i> kepada orang lain',
                'jawabanA' => 'O',
                'jawabanB' => 'P',
            ),
            // Nomor 27
            array(
                'soalA' => 'Saya suka melakukan hal-hal yang <i><u>baru dan berbeda</u></i>',
                'soalB' => 'Saya <i><u>suka menceritakan keberhasilan saya</u></i> dalam mengerjakan tugas',
                'jawabanA' => 'Z',
                'jawabanB' => 'X',
            ),
            // Nomor 28
            array(
                'soalA' => 'Bila saya benar, saya suka mempertahankannya <i><u>“mati-matian”</u></i>',
                'soalB' => 'Saya suka <i><u>bergabung ke dalam</u></i> suatu kelompok',
                'jawabanA' => 'K',
                'jawabanB' => 'B',
            ),
            // Nomor 29
            array(
                'soalA' => 'Saya tidak mau <i><u>berbeda</u></i> dengan orang lain',
                'soalB' => 'Saya berusaha untuk sangat <i><u>intim</u></i> dengan orang-orang',
                'jawabanA' => 'F',
                'jawabanB' => 'O',
            ),
            // Nomor 30
            array(
                'soalA' => 'Saya suka <i><u>diajari mengenai caranya mengerjakan</u></i> suatu pekerjaan',
                'soalB' => 'Saya mudah merasa <i><u>jemu</u></i>( bosan )',
                'jawabanA' => 'W',
                'jawabanB' => 'Z',
            ),
            // Nomor 31
            array(
                'soalA' => 'Saya bekerja <i><u>“keras”</u></i>',
                'soalB' => 'Saya banyak <i><u>berfikir dan berencana</u></i>',
                'jawabanA' => 'G',
                'jawabanB' => 'R',
            ),
            // Nomor 32
            array(
                'soalA' => 'Saya <i><u>memimpin</u></i> kelompok',
                'soalB' => 'Hal-hal yang kecil (detail) <i><u>menarik hati</u></i> saya',
                'jawabanA' => 'L',
                'jawabanB' => 'D',
            ),
            // Nomor 33
            array(
                'soalA' => 'Saya <i><u>cepat dan mudah</u></i> mengambil keputusan',
                'soalB' => 'Saya meletakkan segala sesuatu secara <i><u>rapih dan teratur</u></i>',
                'jawabanA' => 'I',
                'jawabanB' => 'C',
            ),
            // Nomor 34
            array(
                'soalA' => 'Tugas-tugas saya kerjakan secara <i><u>cepat</u></i>',
                'soalB' => 'Saya jarang <i><u>marah atau sedih</u></i>',
                'jawabanA' => 'T',
                'jawabanB' => 'E',
            ),
            // Nomor 35
            array(
                'soalA' => 'Saya ingin menjadi bagian dari <i><u>kelompok</u></i>',
                'soalB' => 'Pada suatu waktu tertentu, saya hanya ingin mengerjakan <i><u>satu</u></i> tugas saja',
                'jawabanA' => 'B',
                'jawabanB' => 'N',
            ),
            // Nomor 36
            array(
                'soalA' => 'Saya berusaha untuk <i><u>intim dengan teman-teman saya</u></i>',
                'soalB' => 'Saya berusaha keras untuk menjadi yang <i><u>terbaik</u></i>',
                'jawabanA' => 'O',
                'jawabanB' => 'A',
            ),
            // Nomor 37
            array(
                'soalA' => 'Saya menyukai model baju <i><u>baru</u></i> dan tipe-tipe mobil <i><u>baru</u></i>',
                'soalB' => 'Saya ingin menjadi <i><u>penanggung jawab</u></i> bagi orang-orang lain',
                'jawabanA' => 'Z',
                'jawabanB' => 'P',
            ),
            // Nomor 38
            array(
                'soalA' => 'Saya suka <i><u>berdebat</u></i>',
                'soalB' => 'Saya ingin <i><u>diperhatikan</u></i>',
                'jawabanA' => 'K',
                'jawabanB' => 'X',
            ),
            // Nomor 39
            array(
                'soalA' => 'Saya suka <i><u>menyenangkan hati</u></i> orang yang memimpin saya',
                'soalB' => 'Saya tertarik <i><u>menjadi anggota</u></i> dari suatu kelompok',
                'jawabanA' => 'F',
                'jawabanB' => 'B',
            ),
            // Nomor 40
            array(
                'soalA' => 'Saya senang <i><u>mengikuti</u></i> aturan secara tertib',
                'soalB' => 'Saya suka orang-orang <i><u>mengenal saya benar-benar</u></i>',
                'jawabanA' => 'W',
                'jawabanB' => 'O',
            ),
            // Nomor 41
            array(
                'soalA' => 'Saya mencoba <i><u>sekuat tenaga</u></i>',
                'soalB' => 'Saya sangat <i><u>menyenangkan</u></i>',
                'jawabanA' => 'G',
                'jawabanB' => 'S',
            ),
            // Nomor 42
            array(
                'soalA' => 'Orang lain beranggapan bahwa saya adalah seorang <i><u>pemimpin yang baik</u></i>',
                'soalB' => 'Saya berpikir <i><u>jauh ke depan dan terinci</u></i>',
                'jawabanA' => 'L',
                'jawabanB' => 'R',
            ),
            // Nomor 43
            array(
                'soalA' => 'Seringkali saya <i><u>memanfaatkan peluang</u></i>',
                'soalB' => 'Saya senang <i><u>memperhatikan</u></i> hal-hal sampai sekecil-kecilnya',
                'jawabanA' => 'I',
                'jawabanB' => 'D',
            ),
            // Nomor 44
            array(
                'soalA' => 'Orang lain menganggap saya <i><u>bekerja cepat</u></i>',
                'soalB' => 'Orang lain menganggap saya dapat melakukan penataan yang <i><u>rapih dan teratur</u></i>',
                'jawabanA' => 'T',
                'jawabanB' => 'C',
            ),
            // Nomor 45
            array(
                'soalA' => 'Saya menyukai <i><u>permainan-permainan dan olahraga</u></i>',
                'soalB' => 'Saya sangat <i><u>menyenangkan</u></i>',
                'jawabanA' => 'V',
                'jawabanB' => 'E',
            ),
            // Nomor 46
            array(
                'soalA' => 'Saya senang bila orang-orang <i><u>dapat intim dan bersahabat</u></i>',
                'soalB' => 'Saya selalu berusaha <i><u>menyelesaikan apa yang telah saya mulai</u></i>',
                'jawabanA' => 'O',
                'jawabanB' => 'N',
            ),
            // Nomor 47
            array(
                'soalA' => 'Saya suka <i><u>bereksperimen dan mencoba sesuatu yang baru</u></i>',
                'soalB' => 'Saya suka mengerjakan <i><u>pekerjaan-pekerjaan yang sulit dengan baik</u></i>',
                'jawabanA' => 'Z',
                'jawabanB' => 'A',
            ),
            // Nomor 48
            array(
                'soalA' => 'Saya senang diperlakukan secara <i><u>adil</u></i>',
                'soalB' => 'Saya senang mengajari <i><u>orang lain</u></i> bagaimana caranya mengerjakan sesuatu',
                'jawabanA' => 'K',
                'jawabanB' => 'P',
            ),
            // Nomor 49
            array(
                'soalA' => 'Saya suka mengerjakan apa yang <i><u>diharapkan</u></i> dari saya',
                'soalB' => 'Saya suka menarik <i><u>perhatian</u></i>',
                'jawabanA' => 'F',
                'jawabanB' => 'X',
            ),
            // Nomor 50
            array(
                'soalA' => 'Saya suka petunjuk-petunjuk <i><u>terinci</u></i> dalam melaksanakan suatu pekerjaan',
                'soalB' => 'Saya senang <i><u>berada bersama dengan</u></i> orang-orang lain',
                'jawabanA' => 'W',
                'jawabanB' => 'B',
            ),
            // Nomor 51
            array(
                'soalA' => 'Saya selalu berusaha mengerjakan tugas secara <i><u>sempurna</u></i>',
                'soalB' => 'Orang lain menganggap, saya <i><u>tidak mengenal</u></i> Lelah, dalam kerja sehari-hari',
                'jawabanA' => 'G',
                'jawabanB' => 'V',
            ),
            // Nomor 52
            array(
                'soalA' => 'Saya tergolong tipe <i><u>pemimpin</u></i>',
                'soalB' => 'Saya <i><u>mudah</u></i> berteman',
                'jawabanA' => 'L',
                'jawabanB' => 'S',
            ),
            // Nomor 53
            array(
                'soalA' => 'Saya memanfaatkan <i><u>peluang-peluang</u></i>',
                'soalB' => 'Saya banyak <i><u>berfikir</u></i>',
                'jawabanA' => 'I',
                'jawabanB' => 'R',
            ),
            // Nomor 54
            array(
                'soalA' => 'Saya bekerja dengan kecepatan yang <i><u>mantap dan cepat</u></i>',
                'soalB' => 'Saya senang mengerjakan hal-hal yang <i><u>detail</u></i>',
                'jawabanA' => 'T',
                'jawabanB' => 'D',
            ),
            // Nomor 55
            array(
                'soalA' => 'Saya memiliki banyak <i><u>energi</u></i> untuk permainan-permainan dan olahraga',
                'soalB' => 'Saya menempatkan segala sesuatunya secara <i><u>rapih dan teratur</u></i>',
                'jawabanA' => 'V',
                'jawabanB' => 'C',
            ),
            // Nomor 56
            array(
                'soalA' => 'Saya bergaul baik dengan <i><u>semua</u></i> orang',
                'soalB' => 'Saya <i><u>pandai mengendalikan diri</u></i>',
                'jawabanA' => 'S',
                'jawabanB' => 'E',
            ),
            // Nomor 57
            array(
                'soalA' => 'Saya ingin berkenalan dengan orang-orang <i><u>baru</u></i> dan mengerjakan hal baru',
                'soalB' => 'Saya selalu ingin <i><u>menyelesaikan</u></i> pekerjaan yang sudah saya mulai',
                'jawabanA' => 'Z',
                'jawabanB' => 'N',
            ),
            // Nomor 58
            array(
                'soalA' => 'Biasanya saya <i><u>bersikeras</u></i> mengenai apa yang saya yakini',
                'soalB' => 'Biasanya saya suka bekerja <i><u>“keras”</u></i>',
                'jawabanA' => 'K',
                'jawabanB' => 'A',
            ),
            // Nomor 59
            array(
                'soalA' => 'Saya menyukai <i><u>saran-saran</u></i> dari orang yang saya kagumi',
                'soalB' => 'Saya senang <i><u>mengatur</u></i> orang lain',
                'jawabanA' => 'F',
                'jawabanB' => 'P',
            ),
            // Nomor 60
            array(
                'soalA' => 'Saya biarkan orang-orang lain <i><u>mempengaruhi</u></i> saya',
                'soalB' => 'Saya suka menerima banyak <i><u>perhatian</u></i>',
                'jawabanA' => 'W',
                'jawabanB' => 'X',
            ),
            // Nomor 61
            array(
                'soalA' => 'Biasanya saya bekerja sangat <i><u>“keras”</u></i>',
                'soalB' => 'Biasanya saya bekerja <i><u>cepat</u></i>',
                'jawabanA' => 'G',
                'jawabanB' => 'T',
            ),
            // Nomor 62
            array(
                'soalA' => 'Bila saya berbicara, kelompok akan <i><u>mendengarkan</u></i>',
                'soalB' => 'Saya <i><u>terampil</u></i> mempergunakan alat-alat kerja',
                'jawabanA' => 'L',
                'jawabanB' => 'V',
            ),
            // Nomor 63
            array(
                'soalA' => 'Saya <i><u>lambat</u></i> membina persahabatan',
                'soalB' => 'Saya <i><u>lambat</u></i> dalam mengambil keputusan',
                'jawabanA' => 'I',
                'jawabanB' => 'S',
            ),
            // Nomor 64
            array(
                'soalA' => 'Biasanya saya makan secara <i><u>cepat</u></i>',
                'soalB' => 'Saya suka <i><u>membaca</u></i>',
                'jawabanA' => 'T',
                'jawabanB' => 'R',
            ),
            // Nomor 65
            array(
                'soalA' => 'Saya menyukai pekerjaan yang memungkinkan saya <i><u>“berkeliling”</u></i>',
                'soalB' => 'Saya menyukai pekerjaan yang harus dilakukan secara <i><u>teliti</u></i>',
                'jawabanA' => 'V',
                'jawabanB' => 'D',
            ),
            // Nomor 66
            array(
                'soalA' => 'Saya berteman <i><u>sebanyak</u></i> mungkin',
                'soalB' => 'Saya dapat <i><u>menemukan</u></i> hal-hal yang telah saya pindahkan',
                'jawabanA' => 'S',
                'jawabanB' => 'C',
            ),
            // Nomor 67
            array(
                'soalA' => 'Perencanaan saya <i><u>jauh ke masa depan</u></i>',
                'soalB' => 'Saya selalu <i><u>menyenangkan</u></i>',
                'jawabanA' => 'R',
                'jawabanB' => 'E',
            ),
            // Nomor 68
            array(
                'soalA' => 'Saya merasa <i><u>bangga</u></i> akan nama baik saya',
                'soalB' => 'Saya selalu <i><u>menyenangkan</u></i>',
                'jawabanA' => 'K',
                'jawabanB' => 'N',
            ),
            // Nomor 69
            array(
                'soalA' => 'Saya suka <i><u>menyenangkan hati</u></i> orang-orang yang saya <i><u>kagumi</u></i>',
                'soalB' => 'Saya suka menjadi orang yang <i><u>berhasil</u></i>',
                'jawabanA' => 'F',
                'jawabanB' => 'A',
            ),
            // Nomor 70
            array(
                'soalA' => 'Saya senang bila <i><u>orang-orang lain mengambil keputusan</u></i> untuk kelompok',
                'soalB' => '<i><u>Saya</u></i> suka mengambil keputusan untuk kelompok',
                'jawabanA' => 'W',
                'jawabanB' => 'P',
            ),
            // Nomor 71
            array(
                'soalA' => 'Saya selalu berusaha sangat <i><u>“keras”</u></i>',
                'soalB' => 'Saya <i><u>cepat dan mudah</u></i> mengambil keputusan',
                'jawabanA' => 'G',
                'jawabanB' => 'I',
            ),
            // Nomor 72
            array(
                'soalA' => 'Biasanya kelompok saya mengerjakan hal-hal yang <i><u>saya</u></i> inginkan',
                'soalB' => 'Biasanya saya <i><u>tergesa-gesa</u></i>',
                'jawabanA' => 'L',
                'jawabanB' => 'T',
            ),
            // Nomor 73
            array(
                'soalA' => 'Saya seringkali merasa <i><u>lelah</u></i>',
                'soalB' => 'Saya <i><u>lambat</u></i> dalam mengambil keputusan',
                'jawabanA' => 'I',
                'jawabanB' => 'V',
            ),
            // Nomor 74
            array(
                'soalA' => 'Saya bekerja secara <i><u>cepat</u></i>',
                'soalB' => 'Saya <i><u>mudah</u></i> mendapat kawan',
                'jawabanA' => 'T',
                'jawabanB' => 'S',
            ),
            // Nomor 75
            array(
                'soalA' => 'Biasanya saya <i><u>bersemangat atau bergairah</u></i>',
                'soalB' => 'Sebagian besar waktu saya untuk <i><u>berpikir</u></i>',
                'jawabanA' => 'V',
                'jawabanB' => 'R',
            ),
            // Nomor 76
            array(
                'soalA' => 'Saya sangat <i><u>hangat</u></i> kepada orang-orang',
                'soalB' => 'Saya menyukai pekerjaan yang menuntut <i><u>ketepatan</u></i>',
                'jawabanA' => 'S',
                'jawabanB' => 'D',
            ),
            // Nomor 77
            array(
                'soalA' => 'Saya banyak <i><u>berpikir</u></i> dan merencana',
                'soalB' => 'Saya meletakkan segala sesuatu <i><u>pada tempatnya</u></i>',
                'jawabanA' => 'R',
                'jawabanB' => 'C',
            ),
            // Nomor 78
            array(
                'soalA' => 'Saya suka tugas yang perlu ditekuni sampai kepada <i><u>hal sedetailnya</u></i>',
                'soalB' => 'Saya <i><u>tidak cepat</u></i> marah',
                'jawabanA' => 'D',
                'jawabanB' => 'E',
            ),
            // Nomor 79
            array(
                'soalA' => 'Saya senang <i><u>mengikuti</u></i> orang-orang yang saya kagumi',
                'soalB' => 'Saya selalu <i><u>menyelesaikan</u></i> pekerjaan yang saya mulai',
                'jawabanA' => 'F',
                'jawabanB' => 'N',
            ),
            // Nomor 80
            array(
                'soalA' => 'Saya menyukai petunjuk-petunjuk yang <i><u>jelas</u></i>',
                'soalB' => 'Saya suka bekerja <i><u>“keras”</u></i>',
                'jawabanA' => 'W',
                'jawabanB' => 'A',
            ),
            // Nomor 81
            array(
                'soalA' => 'Saya <i><u>mengejar</u></i> apa yang saya inginkan',
                'soalB' => 'Saya adalah seorang pemimpin yang <i><u>baik</u></i>',
                'jawabanA' => 'G',
                'jawabanB' => 'L',
            ),
            // Nomor 82
            array(
                'soalA' => 'Saya membuat <i><u>orang lain</u></i> bekerja keras',
                'soalB' => 'Saya adalah seorang yang <i><u>“gampangan”</u></i> (tak banyak pertimbangan)',
                'jawabanA' => 'L',
                'jawabanB' => 'I',
            ),
            // Nomor 83
            array(
                'soalA' => 'Saya membuat keputusan-keputusan secara <i><u>cepat</u></i>',
                'soalB' => 'Bicara saya <i><u>cepat</u></i>',
                'jawabanA' => 'I',
                'jawabanB' => 'T',
            ),
            // Nomor 84
            array(
                'soalA' => 'Biasanya saya bekerja <i><u>tergesa-gesa</u></i>',
                'soalB' => 'Secara teratur saya <i><u>berolah raga</u></i>',
                'jawabanA' => 'T',
                'jawabanB' => 'V',
            ),
            // Nomor 85
            array(
                'soalA' => 'Saya <i><u>tidak suka</u></i> bertemu dengan orang-orang',
                'soalB' => 'Saya <i><u>cepat</u></i> Lelah',
                'jawabanA' => 'V',
                'jawabanB' => 'S',
            ),
            // Nomor 86
            array(
                'soalA' => 'Saya mempunyai <i><u>banyak</u></i> sekali teman',
                'soalB' => '<i><u>Banyak</u></i> waktu saya untuk berpikir',
                'jawabanA' => 'S',
                'jawabanB' => 'R',
            ),
            // Nomor 87
            array(
                'soalA' => 'Saya suka bekerja dengan <i><u>teori</u></i>',
                'soalB' => 'Saya suka bekerja <i><u>sedetail-detailnya</u></i>',
                'jawabanA' => 'R',
                'jawabanB' => 'D',
            ),
            // Nomor 88
            array(
                'soalA' => 'Saya suka bekerja sampai <i><u>sedetail-detailnya</u></i>',
                'soalB' => 'Saya suka <i><u>mengorganisir</u></i> pekerjaan saya',
                'jawabanA' => 'D',
                'jawabanB' => 'C',
            ),
            // Nomor 89
            array(
                'soalA' => 'Saya meletakan segala sesuatu <i><u>pada tempatnya</u></i>',
                'soalB' => 'Saya selalu <i><u>menyenangkan</u></i>',
                'jawabanA' => 'C',
                'jawabanB' => 'E',
            ),
            // Nomor 90
            array(
                'soalA' => 'Saya senang <i><u>diberi petunjuk</u></i> mengenai apa yang harus saya lakukan',
                'soalB' => 'Saya harus <i><u>menyelesaikan</u></i> apa yang sudah saya mulai',
                'jawabanA' => 'W',
                'jawabanB' => 'N',
            ),
        );
        
        return $data;
    }
    
    /**
     * Data analisis
     *
     * @return \Illuminate\Http\Response
     */
    public function analysis_data(){
        $analisis = array(
            "N" => array(
                array(
                    "syarat" => 3,
                    "deskripsi" => "Cenderung ragu-ragu dalam situasi pengambilan keputusan, cenderung ragu-ragu, menunda atau menghindari situasi pengambilan keputusan",
                ),
                array(
                    "syarat" => 4,
                    "deskripsi" => "Berhati-hati dan cenderung ragu-ragu",
                ),
                array(
                    "syarat" => 6,
                    "deskripsi" => "Cukup bertanggung jawab terhadap pekerjaan",
                ),
                array(
                    "syarat" => "else",
                    "deskripsi" => "Ketekunan, tanggung jawab terhadap tugas tinggi",
                ),
            ),
            "G" => array(
                array(
                    "syarat" => 4,
                    "deskripsi" => "Bekerja hanya untuk mengejar kesenangan saja bukan untuk memberikan suatu hasil yang baik",
                ),
                array(
                    "syarat" => "else",
                    "deskripsi" => "Kemauan bekerja keras tinggi",
                ),
            ),
            "A" => array(
                array(
                    "syarat" => 5,
                    "deskripsi" => "Mencerminkan ketidakpastian tujuan. Juga mencerminkan kepuasan dalam suatu pekerjaan, tidak perlu melanjutkan usaha untuk sukses",
                ),
                array(
                    "syarat" => "else",
                    "deskripsi" => "Tujuan-tujuan didefinisikan secara jelas, kebutuhan untuk sukses tinggi, ambisi pribadi tinggi",
                ),
            ),
            "L" => array(
                array(
                    "syarat" => 4,
                    "deskripsi" => "Cenderung tidak suka aktif menggunakan orang lain dalam bekerja",
                ),
                array(
                    "syarat" => "else",
                    "deskripsi" => "Yaitu tingkat dimana seseorang memproyeksikan dirinya sebagai pemimpin suatu tingkat, dimana ia mencoba menggunakan orang lain untuk mencapai tujuannya. Nilai S menunjukkan apakah pola kepemimpinannya bersifat persuasive, demokratis, atau otoriter",
                ),
            ),
            "P" => array(
                array(
                    "syarat" => 4,
                    "deskripsi" => "Menurunnya keinginan untuk bertanggung jawab terhadap pekerjaan dan tindakan orang lain",
                ),
                array(
                    "syarat" => "else",
                    "deskripsi" => "Tingkat kebutuhan untuk menerima tanggung jawab orang lain, menjadi orang yang bertanggung jawab",
                ),
            ),
            "I" => array(
                array(
                    "syarat" => 3,
                    "deskripsi" => "Ragu-ragu sampai penundaan/menolak situasi pengambilan keputusan",
                ),
                array(
                    "syarat" => 4,
                    "deskripsi" => "Berhati-hati sampai ragu-ragu dalam membuat keputusan",
                ),
                array(
                    "syarat" => 7,
                    "deskripsi" => "Mudah dan lancar sampai berhati-hati dalam membuat keputusan",
                ),
                array(
                    "syarat" => "else",
                    "deskripsi" => "Tidak ragu-ragu dalam proses pengambilan keputusan",
                ),
            ),
            "T" => array(
                array(
                    "syarat" => 3,
                    "deskripsi" => "Melakukan segala sesuatu menurut kemauannya sendiri",
                ),
                array(
                    "syarat" => "else",
                    "deskripsi" => "Tergolong aktif secara internal dan mental",
                ),
            ),
            "V" => array(
                array(
                    "syarat" => 4,
                    "deskripsi" => "Keaktifannya tergolong rendah, cenderung pasif (hanya duduk-duduk saja",
                ),
                array(
                    "syarat" => "else",
                    "deskripsi" => "Keaktifannya secara fisik tergolong agak baik, cenderung tipe sportif",
                ),
            ),
            "X" => array(
                array(
                    "syarat" => 1,
                    "deskripsi" => "Cenderung pemalu, suka menyendiri",
                ),
                array(
                    "syarat" => 3,
                    "deskripsi" => "Rendah hati, tulus",
                ),
                array(
                    "syarat" => 5,
                    "deskripsi" => "Khusus, memiliki pola yang nyata",
                ),
                array(
                    "syarat" => "else",
                    "deskripsi" => "Membutuhkan perhatian yang nyata",
                ),
            ),
            "S" => array(
                array(
                    "syarat" => 5,
                    "deskripsi" => "Memiliki penilaian yang rendah terhadap hubungan sosial, cenderung kurang percaya pada orang lain",
                ),
                array(
                    "syarat" => "else",
                    "deskripsi" => "Tingkat kepercayaan dalam hubungan sosial tinggi, menyukai interaksi sosial",
                ),
            ),
            "B" => array(
                array(
                    "syarat" => 3,
                    "deskripsi" => "Selektif, secara umum melepaskan diri dari kelompok",
                ),
                array(
                    "syarat" => 5,
                    "deskripsi" => "Ada kebutuhan untuk diterima dan diakui tetapi tidak terlalu mudah dipengaruhi oleh kelompok",
                ),
                array(
                    "syarat" => "else",
                    "deskripsi" => "Kebutuhan untuk disukai, diakui oleh semua orang. Mudah dipengaruhi kelompok",
                ),
            ),
            "O" => array(
                array(
                    "syarat" => 2,
                    "deskripsi" => "Tidak menyukai hubungan antar pribadi. Tidak menyukai interaksi perseorangan",
                ),
                array(
                    "syarat" => 4,
                    "deskripsi" => "Sadar akan kebutuhan antar pribadi tetapi dapat melepaskan diri dari orang lain/tidak terlalu tergantung",
                ),
                array(
                    "syarat" => "else",
                    "deskripsi" => "Ketergantungan yang sangat besar akan pengakuan dan penerimaan diri",
                ),
            ),
            "R" => array(
                array(
                    "syarat" => 4,
                    "deskripsi" => "Kurang perhatian-praktis",
                ),
                array(
                    "syarat" => "else",
                    "deskripsi" => "Penekanan pada nialai-nilai penalaran tergolong tinggi",
                ),
            ),
            "D" => array(
                array(
                    "syarat" => 3,
                    "deskripsi" => "Menyadari kebutuhan akan kecermatan tetapi secara pribadi tidak berminat menangani hal-hal detail",
                ),
                array(
                    "syarat" => "else",
                    "deskripsi" => "Minat menangani hal-hal detail tergolong tinggi",
                ),
            ),
            "C" => array(
                array(
                    "syarat" => 2,
                    "deskripsi" => "Fleksibilitas sampai ketidak-teraturan",
                ),
                array(
                    "syarat" => 5,
                    "deskripsi" => "Tergolong teratur tetapi dengan fleksibilitas",
                ),
                array(
                    "syarat" => "else",
                    "deskripsi" => "Memiliki keteraturan yang sangat tinggi, cenderung kaku",
                ),
            ),
            "Z" => array(
                array(
                    "syarat" => 2,
                    "deskripsi" => "Tidak menyukai dan menolak perubahan. Cenderung menggunakan pendekatan-pendekatan tradisional",
                ),
                array(
                    "syarat" => 4,
                    "deskripsi" => "Tidak suka akan perubahan jika dipaksakan kepadanya",
                ),
                array(
                    "syarat" => 6,
                    "deskripsi" => "Mudah menyesuaikan diri",
                ),
                array(
                    "syarat" => 7,
                    "deskripsi" => "Pembuat perubahan yang selektif. Berpikir jauh ke depan",
                ),
                array(
                    "syarat" => "else",
                    "deskripsi" => "Mudah gelisah, mudah frustrasi mungkin karena segala sesuatu bergerak tidak cukup cepat",
                ),
            ),
            "E" => array(
                array(
                    "syarat" => 1,
                    "deskripsi" => "Terbuka, cepat bereaksi, tidak memikirkan nilai dalam pengendalian diri",
                ),
                array(
                    "syarat" => 3,
                    "deskripsi" => "Terbuka",
                ),
                array(
                    "syarat" => 6,
                    "deskripsi" => "Memiliki pendekatan emosional yang seimbang. Mampu mengendalikan perasaannya",
                ),
                array(
                    "syarat" => "else",
                    "deskripsi" => "Sangat menepatkan nilai-nilai dalam setiap aktivitasnya. Kebutuhan pengendalian diri yang berlebih-lebihan, mungkin digunakan sebagai defence mechanisme",
                ),
            ),
            "K" => array(
                array(
                    "syarat" => 2,
                    "deskripsi" => "Selalu menghindari masalah. Cenderung mengabaikan situasi atau cenderung menolak untuk mengenali sesuatu sebagai sebuah masalah",
                ),
                array(
                    "syarat" => 4,
                    "deskripsi" => "Lebih menyukai lingkungan yang tenang. Menghindari konflik. Cenderung menunda masalah",
                ),
                array(
                    "syarat" => 5,
                    "deskripsi" => "Kukuh pendirian, cenderung keras kepala",
                ),
                array(
                    "syarat" => 7,
                    "deskripsi" => "Agresi pribadi yang berkaitan dengan pekerjaan, dorongan dan semangat bersaing",
                ),
                array(
                    "syarat" => "else",
                    "deskripsi" => "Agresif, cenderung defensive",
                ),
            ),
            "F" => array(
                array(
                    "syarat" => 1,
                    "deskripsi" => "Cenderung egois, kemungkinan bisa bersikap memberontak",
                ),
                array(
                    "syarat" => 3,
                    "deskripsi" => "Mengurus kepentingan diri sendiri",
                ),
                array(
                    "syarat" => 5,
                    "deskripsi" => "Setia terhadap perusahaan",
                ),
                array(
                    "syarat" => "else",
                    "deskripsi" => "Bersikap setia dan membantu secara pribadi, ada kemungkinan bantuannya bermotivasi politis",
                ),
            ),
            "W" => array(
                array(
                    "syarat" => 3,
                    "deskripsi" => "Berorientasi pada tujuan, mandiri",
                ),
                array(
                    "syarat" => 5,
                    "deskripsi" => "Kebutuhan akan pengarahan dan harapan yang dirumuskan untuknya",
                ),
                array(
                    "syarat" => "else",
                    "deskripsi" => "Meningkatnya orientasi terhadap tugas dan membutuhkan instruksi yang jelas",
                ),
            ),
        );
        
        return $analisis;
    }

    /**
     * Menganalisis tes
     *
     * @return \Illuminate\Http\Response
     */
    public function analyze($jawaban, $array){
        // Menghitung jumlah if else
        $count = count($array);

        // Jika jumlah if else 2
        if($count == 2){
            if($jawaban <= $array[0]["syarat"]) return $array[0]["deskripsi"];
            else return $array[1]["deskripsi"];
        }
        // Jika jumlah if else 3
        elseif($count == 3){
            if($jawaban <= $array[0]["syarat"]) return $array[0]["deskripsi"];
            elseif($jawaban <= $array[1]["syarat"]) return $array[1]["deskripsi"];
            else return $array[2]["deskripsi"];
        }
        // Jika jumlah if else 4
        elseif($count == 4){
            if($jawaban <= $array[0]["syarat"]) return $array[0]["deskripsi"];
            elseif($jawaban <= $array[1]["syarat"]) return $array[1]["deskripsi"];
            elseif($jawaban <= $array[2]["syarat"]) return $array[2]["deskripsi"];
            else return $array[3]["deskripsi"];
        }
        //Jika jumlah if else 5
        elseif($count == 5){
            if($jawaban <= $array[0]["syarat"]) return $array[0]["deskripsi"];
            elseif($jawaban <= $array[1]["syarat"]) return $array[1]["deskripsi"];
            elseif($jawaban <= $array[2]["syarat"]) return $array[2]["deskripsi"];
            elseif($jawaban <= $array[3]["syarat"]) return $array[3]["deskripsi"];
            else return $array[4]["deskripsi"];
        }
    }
}