@extends('template/main')

@section('content')
<section>
    <div class="bg-theme-1" style="padding: 8em 0 4em 0">
        <div class="d-none">
        @include('template/_welcome')
        </div>
        <main class="container text-center text-white">
            <h3 class="text-capitalize">Selamat Datang {{Auth::user()->nama_user}}</h3>
            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
            tempor incididunt ut labore<br> Et dolore magna aliqua. Ut enim ad minim veniam</p>
            <div class="content">
                <div class="card-body">
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
                            @foreach($tes as $data)
                            <div class="col-md-3 col-sm-6 mb-3">
                                <a href="/tes/{{ $data->path }}" class="btn btn-md btn-block btn-outline-light font-weight-bold">{{ $data->nama_tes }}</a>
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
            </div>
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
@endsection