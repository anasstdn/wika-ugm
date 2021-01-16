@extends('layouts.app')

@section('content')
<!-- Header -->
<div class="bg-primary-dark">
    <div class="content content-top">
        <div class="row push">
            <div class="col-md py-10 d-md-flex align-items-md-center text-center">
                <h1 class="text-white mb-0">
                    <span class="font-w300">Access Control List</span>
                    <span class="font-w400 font-size-lg text-white-op d-none d-md-inline-block">Manajemen Pengguna</span>
                </h1>
            </div>
            <div class="col-md py-10 d-md-flex align-items-md-center justify-content-md-end text-center">
                @can('user-create')
                <a class="btn btn-alt-primary" href="#" onclick="show_modal('{{ route('user.create') }}')">
                    <i class="fa fa-plus mr-5"></i> Tambah Baru
                </a>
                @endcan
            </div>
        </div>
    </div>
</div>
<!-- END Header -->

<!-- Page Content -->
<!-- Page Content -->
<div class="bg-white">
    <!-- Breadcrumb -->
    <div class="content">
        <nav class="breadcrumb mb-0">
            <a class="breadcrumb-item" href="javascript:void(0)">Access Control List</a>
            <span class="breadcrumb-item active">Manajemen Pengguna</span>
        </nav>
        <h2 class="content-heading"></h2>
        <!-- Dynamic Table Full -->
        <div class="block">
            <div class="block-header block-header-default">
                <h3 class="block-title">Manajemen Pengguna</h3>
            </div>
            <div class="block-content block-content-full">
                <!-- DataTables functionality is initialized with .js-dataTable-full class in js/pages/be_tables_datatables.min.js which was auto compiled from _es6/pages/be_tables_datatables.js -->
                <div class="table-responsive">
                  <table 
                  data-toggle="table"
                  data-ajax="ajaxRequest"
                  data-search="true"
                  data-side-pagination="server"
                  data-pagination="true"
                  data-page-list="[5,10, 25, 50, 100, 200, All]"
                  data-show-fullscreen="true"
                  data-show-extended-pagination="true"
                  class="table table-bordered table-striped table-vcenter" 
                  id="table"
                  >
                  <thead>
                    <tr>
                      <th data-field="no">No</th>
                      <th data-field="name">Nama</th>
                      <th data-field="username">Username</th>
                      <th data-field="email">Email</th>
                      <th data-field="roles">Role</th>
                      <th data-field="status_aktif">Status Aktif</th>
                      <th data-field="aksi">Aksi</th>
                  </tr>
              </thead>
          </table>
      </div>
{{--       <br/><br/>
      <div class="table-responsive">
                <table class="table table-bordered table-striped table-vcenter" id="tabel">
                    <thead>
                        <tr>
                            <th class="text-center"></th>
                            <th>Nama</th>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th class="d-none d-sm-table-cell" style="width: 15%;">Flag Aktif</th>
                            <th class="text-center" style="width: 15%;">Action</th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div> --}}
        </div>
    </div>
    <!-- END Breadcrumb -->
</div>
<!-- END Page Content -->
<div class="modal fade" id="formModal" aria-hidden="true" aria-labelledby="formModalLabel" role="dialog">
</div>
@endsection

@push('js')
{{-- <script>
    var table;
    $(function(){
        table = $('#tabel').DataTable({
            pagingType: "full_numbers",
            columnDefs: [ { orderable: false, targets: [ 4 ] } ],
            pageLength: 20,
            lengthMenu: [[5, 8, 15, 20,50,100], [5, 8, 15, 20,50,100]],
            autoWidth: false,
            stateSave: true,
            processing : true,
            serverSide : true,
            ajax : {
                url:"{{ url('users/get-data') }}",
                data: function (d) {

                }
            },
            columns: [
            {data: 'nomor', name: 'nomor'},
            {data: 'name', name: 'name'},
            {data: 'username', name: 'username'},
            {data: 'email', name: 'email'},
            { data: 'role', name: 'role',searchable:false,orderable:false , "render":function(data,type,row)
            {
              if(data.id==1)
              {
                return '<span class="badge badge-primary">'+data.role+'</span>';
            }
            else
            {
                return '<span class="badge badge-success">'+data.role+'</span>';
            }
        }},
        { data: 'status', name: 'status',searchable:false,orderable:false , "render":function(data,type,row)
        {
          if(data.status_aktif==1)
          {
            return '<a class="btn btn-success btn-sm" href="#" style="color:white;font-family:Arial" title="Nonaktifkan User" onclick="nonaktifkan(\'' + data.url + '\',\'' + data.status_aktif + '\')">Aktif</a>';
        }
        else
        {
            return '<a class="btn btn-danger btn-sm" href="#" style="color:white;font-family:Arial" title="Aktifkan User" onclick="nonaktifkan(\'' + data.url + '\',\'' + data.status_aktif + '\')">Nonaktif</a>';
        }
    }},
    {data: 'action', name: 'action', orderable: false, searchable: false},
    ],
    language: {
        lengthMenu : '{{ "Menampilkan _MENU_ data" }}',
        zeroRecords : '{{ "Data tidak ditemukan" }}' ,
        info : '{{ "_PAGE_ dari _PAGES_ halaman" }}',
        infoEmpty : '{{ "Data tidak ditemukan" }}',
        infoFiltered : '{{ "(Penyaringan dari _MAX_ data)" }}',
        loadingRecords : '{{ "Memuat data dari server" }}' ,
        processing :    '{{ "Memuat data data" }}',
        sSearchPlaceholder: "Pencarian..",
        lengthMenu: "_MENU_",
        search: "_INPUT_",
        paginate : {
            first :     '{{ "<" }}' ,
            last :      '{{ ">" }}' ,
            next :      '{{ ">>" }}',
            previous :  '{{ "<<" }}'
        }
    },
    aoColumnDefs: [{
        bSortable: false,
        aTargets: [-1]
    }],
    iDisplayLength: 5,

});
    })
</script> --}}

<script>
    function ajaxRequest(params) {
        var formData = new FormData();
        formData.append('limit', params.data.limit);
        formData.append('offset', params.data.offset);
        formData.append('order', params.data.order);
        formData.append('search', params.data.search);
        formData.append('sort', params.data.sort);

        $.ajax({
            type: "POST",
            url: "{{ url('user/get-data') }}",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: formData,
            dataType: "json",
            cache: false,
            contentType: false,
            processData: false,
            success: function (data) {
                params.success({
                    "rows": data.data,
                    "total": data.total
                })
            },
            error: function (er) {
                params.error(er);
            }
        });
    }
</script>
@endpush