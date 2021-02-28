@extends('layouts.app')

@section('content')
<!-- Header -->
<div class="bg-primary-dark">
  <div class="content content-top">
    <div class="row push">
      <div class="col-md py-10 d-md-flex align-items-md-center text-center">
        <h1 class="text-white mb-0">
          <span class="font-w300">Purchase Order</span>
          <span class="font-w400 font-size-lg text-white-op d-none d-md-inline-block">Pengajuan PO</span>
        </h1>
      </div>
      <div class="col-md py-10 d-md-flex align-items-md-center justify-content-md-end text-center">
        @can('po-create')
        <a class="btn btn-alt-primary" href="{{ route('po.create') }}">
          <i class="fa fa-plus mr-5"></i> Tambah PO Baru
        </a>
        @endcan
      </div>
    </div>
  </div>
</div><div class="bg-white">
  <div class="content">
    <div class="block block-themed">
      <div class="block-header bg-primary">
        <h3 class="block-title">Pengajuan PO</h3>
        <div class="block-options">
          <button type="button" class="btn-block-option" data-toggle="block-option" data-action="state_toggle" data-action-mode="demo">
            <i class="si si-refresh"></i>
          </button>
          <button type="button" class="btn-block-option" data-toggle="block-option" data-action="content_toggle"></button>
        </div>
      </div>
      <div class="block-content block-content-full">
        <div class="form-row">
          <div class="form-group col-3">
            <label for="wizard-progress-nama-depan">Supplier</label>
            <select class="select form-control" name="supplier_id" id="supplier_id" style="width: 100%;" data-placeholder="">
              <option value="">-Silahkan Pilih-</option>
              @if(isset($supplier) && !$supplier->isEmpty())
              @foreach($supplier as $key => $val)
              <option value="{{ $val->id }}">{{ $val->kode_supplier }} - {{ $val->nama_supplier }}</option>
              @endforeach
              @endif
            </select>
          </div>
          <div class="form-group col-3">
            <label for="wizard-progress-nama-depan">Material</label>
            <select class="select form-control" name="material_id" id="material_id" style="width: 100%;" data-placeholder="">
              <option value="">-Silahkan Pilih-</option>
              @if(isset($material) && !$material->isEmpty())
              @foreach($material as $key => $val)
              <option value="{{ $val->id }}">{{ $val->kode_material }} - {{ $val->material }}</option>
              @endforeach
              @endif
            </select>
          </div>
          <div class="form-group col-3">
            <label for="wizard-progress-nama-depan">Tanggal Pengajuan</label><br/>
            <div id="filter_tgl" class="input-group" style="display: inline;">
              <button class="btn btn-default" id="daterange-btn" style="border:1px solid #ccc">
                <i class="fa fa-calendar"></i> <span id="reportrange"><span> Tanggal</span></span>
                <i class="fa fa-caret-down"></i>
              </button>
              <input type="hidden" name="daterangepicker_start" id="daterangepicker_start" value="">
              <input type="hidden" name="daterangepicker_end" id="daterangepicker_end" value="">
            </div>
          </div>
          <div class="form-group col-2">
            <br/>
            <a href="#" class="btn btn-alt-primary" id="cari">Cari</a>
            <a href="#" class="btn btn-alt-success" id="reset">Reset</a>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="content">
    <div class="block block-themed">
      <div class="block-header bg-info">
        <h3 class="block-title">Daftar Survei Barang</h3>
        <div class="block-options">
          <button type="button" class="btn-block-option" data-toggle="block-option" data-action="state_toggle" data-action-mode="demo">
            <i class="si si-refresh"></i>
          </button>
          <button type="button" class="btn-block-option" data-toggle="block-option" data-action="content_toggle"></button>
        </div>
      </div>
      <div class="block-content block-content-full">
       <div class="table-responsive">
        <table 
        data-toggle="table"
        data-ajax="ajaxRequest"
        data-search="false"
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
            <th data-field="tgl_pembuatan">Tanggal Pembuatan</th>
            <th data-field="supplier">Supplier</th>
            <th data-field="jumlah_material">Jumlah Material</th>
            <th data-field="flag_batal">Batal Pesan</th>
            <th data-field="flag_po">PO</th>
            <th data-field="aksi">Aksi</th>
          </tr>
        </thead>
      </table>
    </div>
  </div>
</div>
</div>
</div>
@endsection

@push('js')
<script>
  var start = moment().startOf('month');
  var end = moment().endOf('month');

  function ajaxRequest(params) {
    var formData = new FormData();
    formData.append('limit', params.data.limit);
    formData.append('offset', params.data.offset);
    formData.append('order', params.data.order);
    formData.append('search', params.data.search);
    formData.append('sort', params.data.sort);
    formData.append('supplier_id', $('#supplier_id').val());
    formData.append('date_start', $('#daterangepicker_start').val());
    formData.append('date_end', $('#daterangepicker_end').val());
    formData.append('material_id', $('#material_id').val());

    $.ajax({
      type: "POST",
      url: "{{ url('po/get-data') }}",
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

  $(function(){
    $(".select").select2({
      width: '100%'
    });

    $('#cari').click(function(){
      $('#table').bootstrapTable('refresh')
    });

    $('#reset').click(function(){
      $('#supplier_id').val('').trigger('change');
      $('#material_id').val('').trigger('change');
      $('#table').bootstrapTable('refresh');   
      cb(start,end);
    });

    $('#daterange-btn').daterangepicker({
      ranges: {
        'Hari ini': [moment(), moment()],
        'Kemarin': [moment().subtract('days', 1), moment().subtract('days', 1)],
        '7 Hari yang lalu': [moment().subtract('days', 6), moment()],
        '30 Hari yang lalu': [moment().subtract('days', 29), moment()],
        'Bulan ini': [moment().startOf('month'), moment().endOf('month')],
        'Bulan kemarin': [moment().subtract('month', 1).startOf('month'), moment().subtract('month', 1).endOf('month')],
        'Tahun ini': [moment().subtract('year', 0).startOf('year').startOf('month'), moment().subtract('year', 0).endOf('year').endOf('month')],
        'Tahun kemarin': [moment().subtract('year', 1).startOf('year').startOf('month'), moment().subtract('year', 1).endOf('year').endOf('month')],
      },
      showDropdowns: true,
      format: 'YYYY-MM-DD',
      startDate: moment().startOf('month'),
      endDate: moment().endOf('month')
    },cb);

    cb(start,end);

  })

  function cb(start, end) {
    $('#daterangepicker_start').val(start.format('YYYY-MM-DD'));
    $('#daterangepicker_end').val(end.format('YYYY-MM-DD'));
    $('#reportrange span').html(start.format('D MMM YYYY') + ' - ' + end.format('D MMM YYYY'));
  }
</script>
@endpush