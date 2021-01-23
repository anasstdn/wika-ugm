@extends('layouts.app')

@section('content')
<!-- Header -->
<div class="bg-primary-dark">
    <div class="content content-top">
        <div class="row push">
            <div class="col-md py-10 d-md-flex align-items-md-center text-center">
                <h1 class="text-white mb-0">
                    <span class="font-w300">Masterdata</span>
                    <span class="font-w400 font-size-lg text-white-op d-none d-md-inline-block">Stok Gudang</span>
                </h1>
            </div>
            <div class="col-md py-10 d-md-flex align-items-md-center justify-content-md-end text-center">
                @can('stok-create')
                <a class="btn btn-alt-primary" href="{{ route('stok.create') }}">
                    <i class="fa fa-plus mr-5"></i> Atur Semua Stok
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
            <a class="breadcrumb-item" href="javascript:void(0)">Masterdata</a>
            <span class="breadcrumb-item active">Stok Gudang</span>
        </nav>
        <h2 class="content-heading"></h2>
        <!-- Dynamic Table Full -->
        <div class="block">
            <div class="block-header block-header-default">
                <h3 class="block-title">Stok Gudang</h3>
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
                      <th data-field="kode_material">Kode Material</th>
                      <th data-field="material">Nama Material</th>
                      <th data-field="satuan">Satuan</th>
                      <th data-field="qty">Qty</th>
                      <th data-field="aksi">Aksi</th>
                  </tr>
              </thead>
          </table>
      </div>
  </div>
</div>
<!-- END Breadcrumb -->
</div>
<!-- END Page Content -->
<div class="modal fade" id="formModal" aria-hidden="true" aria-labelledby="formModalLabel" role="dialog">
</div>
@endsection

@push('js')


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
            url: "{{ url('stok/get-data') }}",
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