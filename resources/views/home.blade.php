@extends('layouts.app')

@section('content')
<!-- Header -->
<div class="bg-primary-dark">
  <div class="content content-top">
    <div class="row push">
      <div class="col-md py-10 d-md-flex align-items-md-center text-center">
        <h1 class="text-white mb-0">
          <span class="font-w300">Beranda</span>
          <span class="font-w400 font-size-lg text-white-op d-none d-md-inline-block">Selamat Datang 
            @php
            $cek_profil = \App\Models\UserProfil::where('user_id',Auth::user()->id)->first();
            @endphp
            @if(!empty($cek_profil))
            {{ \App\Models\Profil::find($cek_profil->profil_id)->nama }}
            @else
            {{ Auth::user()->name }}
          @endif</span>
        </h1>
      </div>
      <div class="col-md py-10 d-md-flex align-items-md-center justify-content-md-end text-center">
        <button type="button" class="btn btn-alt-primary">
          <i class="fa fa-user-plus mr-5"></i> New Account
        </button>
      </div>
    </div>
  </div>
</div>
<!-- END Header -->

<!-- Page Content -->
<div class="bg-white">
  <!-- Breadcrumb -->
  <div class="content">
    <nav class="breadcrumb mb-0">
      <a class="breadcrumb-item" href="javascript:void(0)">Home</a>
      <span class="breadcrumb-item active">Beranda</span>
    </nav>
  </div>
  <!-- END Breadcrumb -->

  <!-- Content -->
  <div class="content">
    <!-- Icon Navigation -->
    @if(in_array(\Auth::user()->roles->pluck('id')[0], array_merge(getConfigValues('ROLE_PELAKSANA'))))
    @include('dashboard-pelaksana')
    @endif

    @if(in_array(\Auth::user()->roles->pluck('id')[0], array_merge(getConfigValues('ROLE_SITE_MANAGER'))))
    @include('dashboard-site-manager')
    @endif

    @if(in_array(\Auth::user()->roles->pluck('id')[0], array_merge(getConfigValues('ROLE_PROJECT_MANAGER'))))
    @include('dashboard-project-manager')
    @endif

    @if(in_array(\Auth::user()->roles->pluck('id')[0], array_merge(getConfigValues('ROLE_KOMERSIAL'))))
    @include('dashboard-komersial')
    @endif

    <!-- END Icon Navigation -->

    <!-- Mini Stats -->
    <div class="row">
      <div class="col-md-6 col-xl-3">
        <a class="block block-rounded block-bordered" href="javascript:void(0)">
          <div class="block-content p-5">
            <div class="py-30 text-center bg-body-light rounded">
              <div class="font-size-h2 font-w700 mb-0 text-muted">78</div>
              <div class="font-size-sm font-w600 text-uppercase">Sales</div>
            </div>
          </div>
        </a>
      </div>
      <div class="col-md-6 col-xl-3">
        <a class="block block-rounded block-bordered" href="javascript:void(0)">
          <div class="block-content p-5">
            <div class="py-30 text-center bg-body-light rounded">
              <div class="font-size-h2 font-w700 mb-0 text-muted">$880</div>
              <div class="font-size-sm font-w600 text-uppercase">Earnings</div>
            </div>
          </div>
        </a>
      </div>
      <div class="col-md-6 col-xl-3">
        <a class="block block-rounded block-bordered" href="javascript:void(0)">
          <div class="block-content p-5">
            <div class="py-30 text-center bg-body-light rounded">
              <div class="font-size-h2 font-w700 mb-0 text-muted">$4,500</div>
              <div class="font-size-sm font-w600 text-uppercase">Total</div>
            </div>
          </div>
        </a>
      </div>
      <div class="col-md-6 col-xl-3">
        <a class="block block-rounded block-bordered" href="javascript:void(0)">
          <div class="block-content p-5">
            <div class="py-30 text-center bg-body-light rounded">
              <div class="font-size-h2 font-w700 mb-0 text-muted">$19,700</div>
              <div class="font-size-sm font-w600 text-uppercase">Estimated</div>
            </div>
          </div>
        </a>
      </div>
    </div>
    <!-- END Mini Stats -->

    <!-- Charts -->
    <div class="row">
      <div class="col-md-6">
        <div class="block block-rounded block-bordered">
          <div class="block-header">
            <h3 class="block-title text-uppercase">Stok Gudang</h3>
            <div class="block-options">
              <button type="button" class="btn-block-option" data-toggle="block-option" data-action="state_toggle" data-action-mode="demo">
                <i class="si si-refresh"></i>
              </button>
              <button type="button" class="btn-block-option">
                <i class="si si-wrench"></i>
              </button>
            </div>
          </div>
          <div class="block-content p-5">
            <!-- Lines Chart Container functionality is initialized in js/pages/db_corporate.min.js which was auto compiled from _es6/pages/db_corporate.js -->
            <!-- For more info and examples you can check out http://www.chartjs.org/docs/ -->
            <div class="form-row" style="margin-left: 0.5em">
              <div class="form-group col-1">
                <label for="wizard-progress-nama-depan">Cari</label>
              </div>
              <div class="form-group col-5">
                <select class="select form-control form-control-sm" name="material_id" id="material_id" style="width: 100%;" data-placeholder="">
                </select>
              </div>
              <div class="form-group col-3">
                <a href="#" class="btn btn-alt-success btn-sm" id="reset"><i class="si si-refresh"></i> Reset</a>
              </div>
            </div>
            <div class="table-responsive">
              <table 
              data-toggle="table"
              data-ajax="ajaxRequest"
              data-search="false"
              data-side-pagination="server"
              data-pagination="true"
              data-page-list="[5,10, 25, 50, 100, 200, All]"
              data-show-fullscreen="false"
              data-show-extended-pagination="true"
              class="table table-sm table-vcenter" 
              id="table"
              >
              <thead>
                <tr>
                  <th data-field="no">No</th>
                  <th data-field="kode_material">Kode Material</th>
                  <th data-field="material">Material</th>
                  <th data-field="satuan">Satuan</th>
                  <th data-field="qty">Qty</th>
                </tr>
              </thead>
            </table>
          </div>

        </div>
      </div>
    </div>
    <div class="col-md-6">
      <div class="block block-rounded block-bordered">
        <div class="block-header">
          <h3 class="block-title text-uppercase">Riwayat Stok Gudang</h3>
          <div class="block-options">
            <button type="button" class="btn-block-option" data-toggle="block-option" data-action="state_toggle" data-action-mode="demo">
              <i class="si si-refresh"></i>
            </button>
            <button type="button" class="btn-block-option">
              <i class="si si-wrench"></i>
            </button>
          </div>
        </div>
        <div class="block-content p-5">
          <!-- Lines Chart Container functionality is initialized in js/pages/db_corporate.min.js which was auto compiled from _es6/pages/db_corporate.js -->
          <!-- For more info and examples you can check out http://www.chartjs.org/docs/ -->
          <div class="form-row" style="margin-left: 0.5em">
            <div class="form-group col-1">
              <label for="wizard-progress-nama-depan">Cari</label>
            </div>
            <div class="form-group col-4">
              <select class="select form-control form-control-sm" name="material_riwayat" id="material_riwayat" style="width: 100%;" data-placeholder="">
              </select>
            </div>
            <div class="form-group col-5">
              <div id="filter_tgl" class="input-group" style="display: inline;">
                <button class="btn btn-default btn-sm" id="daterange-btn" style="border:1px solid #ccc">
                  <i class="fa fa-calendar"></i> <span id="reportrange"><span> Tanggal</span></span>
                  <i class="fa fa-caret-down"></i>
                </button>
                <input type="hidden" name="daterangepicker_start" id="daterangepicker_start" value="">
                <input type="hidden" name="daterangepicker_end" id="daterangepicker_end" value="">
              </div>
            </div>
            <div class="form-group col-2">
              <a href="#" class="btn btn-alt-success btn-sm" id="reset_riwayat"><i class="si si-refresh"></i> Reset</a>
            </div>
          </div>
          <div class="table-responsive">
            <table 
            data-toggle="table"
            data-ajax="ajaxRequestRiwayat"
            data-search="false"
            data-side-pagination="server"
            data-pagination="true"
            data-page-list="[5,10, 25, 50, 100, 200, All]"
            data-show-fullscreen="false"
            data-show-extended-pagination="true"
            class="table table-sm table-vcenter" 
            id="table-riwayat-stok"
            >
            <thead>
              <tr>
                <th data-field="no">No</th>
                <th data-field="material">Material</th>
                <th data-field="tanggal_riwayat">Tanggal</th>
                <th data-field="qty">Qty</th>
                <th data-field="penambahan">(+)</th>
                <th data-field="pengurangan">(-)</th>
                <th data-field="user_input">User</th>
              </tr>
            </thead>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- END Charts -->

<!-- More Data -->
<div class="row">


  <!-- Top Products -->
  <div class="col-xl-12">
    <div class="block block-rounded block-bordered">
      <div class="block-header">
        <h3 class="block-title text-uppercase">Top Products</h3>
        <div class="block-options">
          <button type="button" class="btn-block-option" data-toggle="block-option" data-action="state_toggle" data-action-mode="demo">
            <i class="si si-refresh"></i>
          </button>
          <button type="button" class="btn-block-option">
            <i class="si si-wrench"></i>
          </button>
        </div>
      </div>
      <div class="block-content p-5">
       <div class="form-row">

       </div>
     </div>
   </div>
 </div>
 <!-- END Top Products -->
</div>
<!-- END More Data -->
</div>
<!-- END Content -->
</div>

<!-- END Page Content -->
@endsection

@push('js')
<script>
  var start = moment().startOf('month');
  var end = moment().endOf('month');

  $(function(){
    $(".select").select2({
      width: '100%'
    });

    $('#material_id').on("change", function(e) {
     $('#table').bootstrapTable('refresh');
   });

    $('#material_riwayat').on("change", function(e) {
     $('#table-riwayat-stok').bootstrapTable('refresh');
   });

    $('#reset').click(function(){
      $('#material_id').val('').trigger('change');
      $('#table').bootstrapTable('refresh');   
    })

    $('#reset_riwayat').click(function(){
      $('#material_riwayat').val('').trigger('change');
      cb(start,end);
      $('#table-riwayat-stok').bootstrapTable('refresh');   
    })

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
      endDate: moment().endOf('month'),
    },cb);

    cb(start,end);

    load_data_ajax('{{ url('home/material-dropdown') }}','POST',{}).then((e) => {
      insert_into_select_opt(e,'#material_id','id','material');
      insert_into_select_opt(e,'#material_riwayat','id','material');
    }).catch(e => console.log(e));
  })

  function cb(start, end) {
    $('#daterangepicker_start').val(start.format('YYYY-MM-DD'));
    $('#daterangepicker_end').val(end.format('YYYY-MM-DD'));
    $('#reportrange span').html(start.format('D MMM YYYY') + ' - ' + end.format('D MMM YYYY'));
    $('#table-riwayat-stok').bootstrapTable('refresh');
  }

  function ajaxRequest(params) {
    var formData = new FormData();
    formData.append('limit', params.data.limit);
    formData.append('offset', params.data.offset);
    formData.append('order', params.data.order);
    formData.append('search', params.data.search);
    formData.append('sort', params.data.sort);
    formData.append('material_id', $('#material_id').val());

    $.ajax({
      type: "POST",
      url: "{{ url('home/get-data-stok') }}",
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

  function ajaxRequestRiwayat(params) {
    var formData = new FormData();
    formData.append('limit', params.data.limit);
    formData.append('offset', params.data.offset);
    formData.append('order', params.data.order);
    formData.append('search', params.data.search);
    formData.append('sort', params.data.sort);
    formData.append('material_id', $('#material_riwayat').val());
    formData.append('date_start', $('#daterangepicker_start').val());
    formData.append('date_end', $('#daterangepicker_end').val());

    $.ajax({
      type: "POST",
      url: "{{ url('home/get-data-riwayat-stok') }}",
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

  function insert_into_select_opt(data, selector, value_prop, value_text){
    if(data.length>0) {
      $(selector).empty();
      $(selector).append('<option value="">-Pilih-</option>');
      $.each(data,function(key,value){
        $(selector).append('<option value="'+value[value_prop]+'">'+value[value_text]+'</option>');
      });
    } else {
      $(selector).empty();
      $(selector).append('<option value=""selected>-Data is empty-</option>');
    }
  };

  function load_data_ajax(url,method, params = {})
  {
    return new Promise((resolve, reject) => {
      $.ajax({
        url : url,
        type: method,
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        dataType: "JSON",
        data:params,
        cache: false,
        contentType: false,
        processData: false,
        success: function(data){
          resolve(data);
        },
        error: function (jqXHR, textStatus, errorThrown) {
          reject(errorThrown);
        }
      })
    })
  }
</script>
@endpush