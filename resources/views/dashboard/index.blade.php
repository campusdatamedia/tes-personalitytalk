@extends('template/main')

@section('content')

<section>
    <div class="bg-theme-1" style="padding: 6em 0 2em 0">
        <div class="d-none">
        @include('template/_welcome')
        </div>
        <main class="container text-center text-white">
            <h3 class="text-capitalize"><span id="demo"></span></h3>
            <p>Selamat datang <span class="fw-bold">{{Auth::user()->nama_user}}</span> di Tes Online PersonalityTalk<br>Anda dapat melakukan tes online disini dengan memilih menu tes yang ada di bawah ini.</p>
        </main>
    </div>
</section>
<div class="custom-shape-divider-top-1617699401">
    <svg data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none">
        <path d="M0,0V46.29c47.79,22.2,103.59,32.17,158,28,70.36-5.37,136.33-33.31,206.8-37.5C438.64,32.43,512.34,53.67,583,72.05c69.27,18,138.3,24.88,209.4,13.08,36.15-6,69.85-17.84,104.45-29.34C989.49,25,1113-14.29,1200,52.47V0Z" opacity=".25" class="shape-fill"></path>
        <path d="M0,0V15.81C13,36.92,27.64,56.86,47.69,72.05,99.41,111.27,165,111,224.58,91.58c31.15-10.15,60.09-26.07,89.67-39.8,40.92-19,84.73-46,130.83-49.67,36.26-2.85,70.9,9.42,98.6,31.56,31.77,25.39,62.32,62,103.63,73,40.44,10.79,81.35-6.69,119.13-24.28s75.16-39,116.92-43.05c59.73-5.85,113.28,22.88,168.9,38.84,30.2,8.66,59,6.17,87.09-7.5,22.43-10.89,48-26.93,60.65-49.24V0Z" opacity=".5" class="shape-fill"></path>
        <path d="M0,0V5.63C149.93,59,314.09,71.32,475.83,42.57c43-7.64,84.23-20.12,127.61-26.46,59-8.63,112.48,12.24,165.56,35.4C827.93,77.22,886,95.24,951.2,90c86.53-7,172.46-45.71,248.8-84.81V0Z" class="shape-fill"></path>
    </svg>
</div>

<section class="container py-2">
    @if($seleksi != null)
        @if(strtotime('now') < strtotime($seleksi->waktu_wawancara))
        <div class="row">
            <!-- Alert -->
            <div class="col-12 mb-2">
                <div class="alert alert-danger fade show text-center" role="alert">
                    Tes akan dilaksanakan pada tanggal <strong>{{ setFullDate($seleksi->waktu_wawancara) }}</strong> mulai pukul <strong>{{ date('H:i:s', strtotime($seleksi->waktu_wawancara)) }}</strong>.
                </div>
            </div>
        </div>
        @endif
    @endif

    @if(Auth::user()->role == 1 || Auth::user()->role == 2 || Auth::user()->role == 3 || Auth::user()->role == 5)
    <div class="content">
        @if(Session::get('message'))
        <div class="row">
            <!-- Alert -->
            <div class="col-12 mb-2">
                <div class="alert alert-success alert-dismissible fade show text-center" role="alert">
                    {{ Session::get('message') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            </div>
        </div>
        @endif
        <div class="row text-center">
            @if(count($tes)>0)
                @foreach($tes as $key=>$data)
                <div class="col-auto">
                    <a href="/tes/{{ $data->path }}" class="btn btn-md btn-block btn-outline-dark border-0 fw-bold py-3 my-3">
                        <p class="m-0 mb-2">{{ $data->nama_tes }}</p>
                        <img width="100" src="{{asset('assets/images/icon/'.$gambar[$key])}}">
                    </a>
                </div>
                @endforeach
            @else
                <div class="col-12 mb-0">
                    <div class="alert alert-danger fade show text-center mb-0" role="alert">
                        Tidak ada tes yang akan dilakukan.
                    </div>
                </div>
            @endif
        </div>
    </div>
    @endif

    @if(Auth::user()->role == 4)
        @if($seleksi != null)
            @if(strtotime('now') >= strtotime($seleksi->waktu_wawancara))
            <div class="content">
                @if(Session::get('message'))
                <div class="row">
                    <!-- Alert -->
                    <div class="col-12 mb-2">
                        <div class="alert alert-success alert-dismissible fade show text-center" role="alert">
                            {{ Session::get('message') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    </div>
                </div>
                @endif
                <div class="row justify-content-center">
                    @if(count($tes)>0)
                        @foreach($tes as $key=>$data)
                        <div class="col-auto">
                            <a href="/tes/{{ $data->path }}" class="btn btn-md btn-block btn-outline-dark border-0 fw-bold py-3 my-3">
                                <p class="m-0 mb-2">{{ $data->nama_tes }}</p>
                                <img width="100" src="{{asset('assets/images/icon/'.$gambar[$key])}}">
                            </a>
                        </div>
                        @endforeach
                    @else
                        <div class="col-12 mb-0">
                            <div class="alert alert-danger fade show text-center mb-0" role="alert">
                                Tidak ada tes yang akan dilakukan.
                            </div>
                        </div>
                    @endif
                </div>
            </div>
            @endif
        @endif
    @endif

    @if(Auth::user()->role == 6)
        @if($check != null)
        <div class="row">
            <!-- Alert -->
            <div class="col-12 mb-2">
                <div class="alert alert-danger fade show text-center" role="alert">
                    Anda sudah melakukan tes.
                </div>
            </div>
        </div>
        @else
        <div class="content">
            @if(Session::get('message'))
            <div class="row">
                <!-- Alert -->
                <div class="col-12 mb-2">
                    <div class="alert alert-success alert-dismissible fade show text-center" role="alert">
                        {{ Session::get('message') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                </div>
            </div>
            @endif
            <div class="row justify-content-center">
                <div class="col">
                    <a href="/tes/disc-40-soal" class="btn btn-md btn-block btn-outline-dark border-0 fw-bold py-3 my-3">
                        <img width="100" src="{{asset('assets/images/icon/lightning-bolts.svg')}}">
                        <p class="m-0">DISC 40 Soal</p>
                    </a>
                </div>
            </div>
        </div>
        @endif
    @endif
</section>
<script>
function myFunction() {
    var greeting;
    var time = new Date().getHours();
    if (time < 12) {
        greeting = "Selamat Pagi";
    } else if (time >= 12 && time < 18) {
        greeting = "Selamat Siang";
    } else {
        greeting = "Selamat Malam";
    }
    document.getElementById("demo").innerHTML = greeting;
}
myFunction();
</script>
@endsection
