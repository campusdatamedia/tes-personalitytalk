@extends('template/admin/template')

@section('content')

  <!-- Page Heading -->
  <h1 class="h3 mb-2 text-gray-800">Data Pelamar</h1>
  <p class="mb-4">Pelamar yang melamar pekerjaan dari lowongan perusahaan.</p>

  <!-- DataTales Example -->
  <div class="card shadow mb-4">
    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
      <h6 class="m-0 font-weight-bold text-primary">Data Pelamar</h6>
<!--       <a class="btn btn-sm btn-primary" href="/pelamar/create">
        <i class="fas fa-plus fa-sm fa-fw text-gray-400"></i> Tambah Pelamar
      </a> -->
    </div>
    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
          <thead>
            <tr>
              <th width="50">No.</th>
              <th>Nama</th>
              <th width="150">No. HP</th>
              <th width="150">Posisi</th>
              <th width="150">Tanggal</th>
              <th width="80">Opsi</th>
            </tr>
          </thead>
          <tbody>
            <?php $i = 1 ?>
            @foreach($pelamar as $data)
            <tr>
              <td>{{ $i }}</td>
              <td><a href="/admin/pelamar/profile/{{ $data->id_pelamar }}">{{ $data->nama_lengkap }}</a></td>
              <td>{{ $data->nomor_hp }}</td>
              <td>{{ $data->posisi->posisi }}</td>
              <td>{{ setFullDate($data->created_at) }}</td>
              <td>
                <a href="/admin/pelamar/edit/{{ $data->id_pelamar }}" class="btn btn-sm btn-info mr-2 mb-2" data-id="{{ $data->id_pelamar }}" data-toggle="tooltip" data-placement="top" title="Edit"><i class="fa fa-edit"></i></a>
                <a href="#" class="btn btn-sm btn-danger mb-2 delete" data-id="{{ $data->id_pelamar }}" data-toggle="tooltip" data-placement="top" title="Hapus"><i class="fa fa-trash"></i></a>
              </td>
            </tr>
            <?php $i++; ?>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>
  
@endsection

@section('css-extra')

<!-- Custom styles for this page -->
<link href="{{ asset('templates/sb-admin-2/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">

<!-- CSS -->
<style type="text/css">
  td a.btn {width: 36px;}
</style>

@endsection

@section('js-extra')

<!-- Page level plugins -->
<script src="{{ asset('templates/sb-admin-2/vendor/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('templates/sb-admin-2/vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>

<!-- JavaScripts -->
<script type="text/javascript">
  $(document).ready(function() {
    // Call the dataTables jQuery plugin
    $('#dataTable').DataTable();

    // Button Not Allowed
    $(document).on("click", ".not-allowed", function(e){
      e.preventDefault();
    });

    // Delete
    $(document).on("click", ".delete", function(e){
      e.preventDefault();
      var id = $(this).data("id");
      var ask = confirm("Anda yakin ingin menghapus data ini?");
      if(ask){
        $.ajax({
          type: "post",
          url: "/admin/pelamar/delete",
          data: {_token: "{{ csrf_token() }}", id: id},
          success: function(response){
            if(response == "Berhasil menghapus data!"){
              alert(response);
              window.location.href = "/admin/pelamar";
            }
          }
        })
      }
    });
  });
</script>

@endsection