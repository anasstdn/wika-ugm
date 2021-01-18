@extends('layouts.app')

@section('content')
<!-- Header -->
<div class="bg-primary-dark">
    <div class="content content-top">
        <div class="row push">
            <div class="col-md py-10 d-md-flex align-items-md-center text-center">
                <h1 class="text-white mb-0">
                    <span class="font-w300">Masterdata</span>
                    <span class="font-w400 font-size-lg text-white-op d-none d-md-inline-block">Pegawai</span>
                </h1>
            </div>
            <div class="col-md py-10 d-md-flex align-items-md-center justify-content-md-end text-center">
                @can('pegawai-create')
                <a class="btn btn-alt-primary" href="{{ route('pegawai.create') }}">
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
        {{-- <h2 class="content-heading"></h2> --}}
        <!-- Dynamic Table Full -->
        <div class="block block-themed">
          <div class="block-header bg-info">
            <h3 class="block-title">Data Pegawai</h3>
            <div class="block-options">
              <button type="button" class="btn-block-option" data-toggle="block-option" data-action="state_toggle" data-action-mode="demo">
                <i class="si si-refresh"></i>
              </button>
              <button type="button" class="btn-block-option" data-toggle="block-option" data-action="content_toggle"></button>
            </div>
          </div>
            {{-- <div class="block-header block-header-default">
                <h3 class="block-title">Pegawai</h3>
            </div> --}}
            <div class="block-content block-content-full">
              <div class="form-row">
                <div class="form-group col-3">
                  <label for="wizard-progress-nama-depan">Nama</label>
                  <input class="form-control form-control-sm" type="text" id="nama">
                </div>
                <div class="form-group col-3">
                  <label for="wizard-progress-nama-depan">Jabatan</label>
                  <select class="select form-control form-control-sm" name="jabatan_id" id="jabatan_id" style="width: 100%;" data-placeholder="">
                  </select>
                </div>
                <div class="form-group col-3">
                  <label for="wizard-progress-nama-depan">Departement</label>
                  <select class="select form-control form-control-sm" name="departement_id" id="departement_id" style="width: 100%;" data-placeholder="">
                  </select>
                </div>
                <div class="form-group col-3">
                  <br/>
                  <a href="javascript::void(0)" class="btn btn-alt-primary" id="cari">Cari</a>
                  <a href="javascript::void(0)" class="btn btn-alt-success" id="reset">Reset</a>
                </div>
              </div>
              
              <div class="form-row">
                
              </div>
              
                <!-- DataTables functionality is initialized with .js-dataTable-full class in js/pages/be_tables_datatables.min.js which was auto compiled from _es6/pages/be_tables_datatables.js -->
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
                      <th data-field="nama">Nama Pegawai</th>
                      <th data-field="nip">NIP</th>
                      <th data-field="jabatan">Jabatan</th>
                      <th data-field="departement">Departement</th>
                      <th data-field="status_resign">Status Resign</th>
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
        formData.append('nama', $('#nama').val());
        formData.append('jabatan_id', $('#jabatan_id').val());
        formData.append('departement_id', $('#departement_id').val());

        $.ajax({
            type: "POST",
            url: "{{ url('pegawai/get-data') }}",
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

    const load_autocomplete_data = async() => {
      load_data_jabatan = await load_data_ajax('{{ url('pegawai/load-data-jabatan') }}','POST').then(function(result) {
        insert_into_select_opt(result,'#jabatan_id','id','jabatan');
      });

      load_data_departement = await load_data_ajax('{{ url('pegawai/load-data-departement') }}','POST').then(function(result) {
        insert_into_select_opt(result,'#departement_id','id','departement');
      });
    }

    function insert_into_select_opt(data, selector, value_prop, value_text){
      if(data.length>0) {
        $(selector).empty();
        $(selector).append('<option value="" selected>-Pilih-</option>');
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
            console.log(data);
            resolve(data);
          },
          error: function (jqXHR, textStatus, errorThrown) {
            reject(errorThrown);
          }
        })
      })
    }

    $(function(){
      // $(".select").select2({
      //   width: '100%'
      // });

      load_autocomplete_data().then((e) => {
        $('#table').bootstrapTable('refresh');
      }).catch(e => console.log(e));

      $('#cari').click(function(){
        $('#table').bootstrapTable('refresh')
      });

      $('#reset').click(function(){
        $('#nama').val('');
        $('#departement_id').val('').trigger('change');
        $('#jabatan_id').val('').trigger('change');
        $('#table').bootstrapTable('refresh');   
      });
    })
</script>
@endpush