@extends('layouts.app')

@section('content')
<!-- Header -->
<div class="bg-primary-dark">
  <div class="content content-top">
    <div class="row push">
      <div class="col-md py-10 d-md-flex align-items-md-center text-center">
        <h1 class="text-white mb-0">
          <span class="font-w300">Activity Log</span>
          {{-- <span class="font-w400 font-size-lg text-white-op d-none d-md-inline-block">Agama</span> --}}
        </h1>
      </div>
      <div class="col-md py-10 d-md-flex align-items-md-center justify-content-md-end text-center">
{{--                 @can('agama-create')
                <a class="btn btn-alt-primary" href="#" onclick="show_modal('{{ route('agama.create') }}')">
                    <i class="fa fa-plus mr-5"></i> Tambah Baru
                </a>
                @endcan --}}
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
        {{-- <nav class="breadcrumb mb-0">
            <a class="breadcrumb-item" href="javascript:void(0)">Masterdata</a>
            <span class="breadcrumb-item active">Agama</span>
          </nav> --}}
          <h2 class="content-heading"></h2>
          <!-- Dynamic Table Full -->
          <div class="block">
            <div class="block-header block-header-default">
              <h3 class="block-title">Activity Log</h3>
            </div>
            <div class="block-content block-content-full">
              <!-- DataTables functionality is initialized with .js-dataTable-full class in js/pages/be_tables_datatables.min.js which was auto compiled from _es6/pages/be_tables_datatables.js -->
              <div class="form-row">
                <div class="form-group col-3">
                  <label for="wizard-progress-nama-depan">User</label>
                  <select class="select form-control" name="user_id" id="user_id" style="width: 100%;" data-placeholder="">
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
                    <th data-field="no" data-width="50px">No</th>
                    <th data-field="created_at" data-width="200px">Date Time</th>
                    <th data-field="causer_id" data-width="200px">User</th>
                    <th data-field="description" data-width="400px">Description</th>
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
        var start = moment().startOf('month');
        var end = moment().endOf('month');

        function ajaxRequest(params) {
          var formData = new FormData();
          formData.append('limit', params.data.limit);
          formData.append('offset', params.data.offset);
          formData.append('order', params.data.order);
          formData.append('search', params.data.search);
          formData.append('sort', params.data.sort);

          $.ajax({
            type: "POST",
            url: "{{ url('activity-log/get-data') }}",
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

          load_data_ajax('{{ url('activity-log/users') }}','POST',{}).then((e) => {
            insert_into_select_opt(e,'#user_id','id','name');
          }).catch(e => console.log(e));

        });

        function cb(start, end) {
          $('#daterangepicker_start').val(start.format('YYYY-MM-DD'));
          $('#daterangepicker_end').val(end.format('YYYY-MM-DD'));
          $('#reportrange span').html(start.format('D MMM YYYY') + ' - ' + end.format('D MMM YYYY'));
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