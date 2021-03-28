@extends('layouts.app')
@section('content')
    <!-- Header -->
    <div class="bg-primary-dark">
        <div class="content content-top">
            <div class="row push">
                <div class="col-md py-10 d-md-flex align-items-md-center text-center">
                    <h1 class="text-white mb-0">
                        <span class="font-w300">SPM</span>
                        <span class="font-w400 font-size-lg text-white-op d-none d-md-inline-block">Riwayat SPM</span>
                    </h1>
                </div>
                <div class="col-md py-10 d-md-flex align-items-md-center justify-content-md-end text-center">
                    {{-- @can('spm-create')
          <a class="btn btn-alt-primary" href="{{ route('spm.create') }}">
            <i class="fa fa-plus mr-5"></i> Tambah SPM Baru
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
            {{-- <h2 class="content-heading"></h2> --}}
            <!-- Dynamic Table Full -->
            <div class="block block-themed">
                <div class="block-header bg-info">
                    <h3 class="block-title">Riwayat SPM</h3>
                    <div class="block-options">
                        <button type="button" class="btn-block-option" data-toggle="block-option" data-action="state_toggle"
                            data-action-mode="demo">
                            <i class="si si-refresh"></i>
                        </button>
                        <button type="button" class="btn-block-option" data-toggle="block-option"
                            data-action="content_toggle"></button>
                    </div>
                </div>
                {{-- <div class="block-header block-header-default">
                  <h3 class="block-title">Pegawai</h3>
                </div> --}}
                <div class="block-content block-content-full">
                    <div class="form-row">
                        <div class="form-group col-4">
                            <label for="wizard-progress-nama-depan">Pencarian (No SPM, Pemohon dan Lokasi)</label>
                            <input class="form-control" type="text" id="nama">
                        </div>
                        <div class="form-group col-3">
                            <label for="wizard-progress-nama-depan">Material</label>
                            <select class="select form-control" name="material_id" id="material_id" style="width: 100%;"
                                data-placeholder="">
                            </select>
                        </div>
                        <div class="form-group col-3">
                            <label for="wizard-progress-nama-depan">Tanggal Pengajuan</label><br />
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
                            <br />
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
                    <h3 class="block-title">Daftar Riwayat SPM</h3>
                    <div class="block-options">
                        {{-- <button type="button" class="btn-block-option" data-toggle="block-option" data-action="state_toggle"
                            data-action-mode="demo">
                            <i class="si si-refresh"></i>
                        </button>
                        <button type="button" class="btn-block-option" data-toggle="block-option"
                            data-action="content_toggle"></button> --}}
                            <a href="#" class="btn btn-alt-success" onclick="exportToExcel()" target="_blank">Export ke
                                Excel</a>
                    </div>
                </div>
                {{-- <div class="block-header block-header-default">
          <h3 class="block-title">Pegawai</h3>
        </div> --}}
                <div class="block-content block-content-full">
                    <div id="info">
                    </div>
                    <div class="table-responsive">
                        <font style="font-size: 11px" face="Arial">
                            <table data-toggle="table" data-ajax="ajaxRequest" data-search="false"
                                data-side-pagination="server" data-pagination="true"
                                data-page-list="[5,10, 25, 50, 100, 200, All]" data-show-fullscreen="true"
                                data-show-extended-pagination="true"
                                class="table table-bordered table-striped table-vcenter table-sm" id="table">
                                <thead>
                                    <tr>
                                        <th data-field="no">No</th>
                                        <th data-field="no_spm">No. SPM</th>
                                        <th data-field="tanggal">Tanggal</th>
                                        <th data-field="kode_material">Kode Material</th>
                                        <th data-field="material">Material</th>
                                        <th data-field="volume">Volume</th>
                                        <th data-field="satuan">Satuan</th>
                                        <th data-field="nama_pemohon">Pemesan</th>
                                        <th data-field="lokasi">Lokasi</th>
                                        <th data-field="spm_batal">Status SPM</th>
                                        <th data-field="sm">Site Manager</th>
                                        <th data-field="k">Komersial</th>
                                        <th data-field="pm">Project Manager</th>
                                    </tr>
                                </thead>
                            </table>
                        </font>
                    </div>
                    {{-- <div class="row">
                        <div class="col-12 text-right">
                            <a href="#" class="btn btn-alt-success" onclick="exportToExcel()" target="_blank">Export ke
                                Excel</a> --}}
                            {{-- <a href="#" class="btn btn-alt-danger" onclick="exportToPDF()" target="_blank">Export ke PDF</a> --}}
                        {{-- </div>
                    </div> --}}
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script>
        var start = moment().subtract('year', 0).startOf('year').startOf('month');
        var end = moment().subtract('year', 0).endOf('year').endOf('month');

        cb(start, end);

        function ajaxRequest(params) {
            var formData = new FormData();
            formData.append('limit', params.data.limit);
            formData.append('offset', params.data.offset);
            formData.append('order', params.data.order);
            formData.append('search', params.data.search);
            formData.append('sort', params.data.sort);
            formData.append('nama', $('#nama').val());
            formData.append('date_start', $('#daterangepicker_start').val());
            formData.append('date_end', $('#daterangepicker_end').val());
            formData.append('material_id', $('#material_id').val());

            $.ajax({
                type: "POST",
                url: "{{ url('history-spm/get-data') }}",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: formData,
                dataType: "json",
                cache: false,
                contentType: false,
                processData: false,
                success: function(data) {
                    params.success({
                        "rows": data.data,
                        "total": data.total
                    })
                },
                error: function(er) {
                    params.error(er);
                }
            });

            load_data_ajax('{{ url('history-spm/count-data') }}', 'POST', formData).then((e) => {
                // insert_into_select_opt(e,'#material_id','id','material');
                $('#info').empty();
                var html;
                html = `<div class="row">
                      <div class="col-4">
                      <label>Pengajuan Barang Sedang Berjalan : ` + e.tidak_batal + `</label>
                      </div>
                      <div class="col-4">
                      <label>Pengajuan Barang Dibatalkan : ` + e.batal + `</label>
                      </div>
                      </div>
                      `;
                $('#info').append(html);

                console.log(e);
            }).catch(e => console.log(e));
        }

        const load_autocomplete_data = async () => {
            load_data_jabatan = await load_data_ajax('{{ url('pegawai/load-data-jabatan') }}', 'POST').then(
                function(result) {
                    insert_into_select_opt(result, '#jabatan_id', 'id', 'jabatan');
                });

            load_data_departement = await load_data_ajax('{{ url('pegawai/load-data-departement') }}', 'POST')
                .then(function(result) {
                    insert_into_select_opt(result, '#departement_id', 'id', 'departement');
                });
        }

        function insert_into_select_opt(data, selector, value_prop, value_text) {
            if (data.length > 0) {
                $(selector).empty();
                $(selector).append('<option value="" selected>-Pilih-</option>');
                $.each(data, function(key, value) {
                    $(selector).append('<option value="' + value[value_prop] + '">' + value[value_text] +
                        '</option>');
                });
            } else {
                $(selector).empty();
                $(selector).append('<option value=""selected>-Data is empty-</option>');
            }
        };

        // function load_data_ajax(url,method, params = {})
        // {
        //   return new Promise((resolve, reject) => {
        //     $.ajax({
        //       url : url,
        //       type: method,
        //       headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        //       dataType: "JSON",
        //       data:params,
        //       cache: false,
        //       contentType: false,
        //       processData: false,
        //       success: function(data){
        //         console.log(data);
        //         resolve(data);
        //       },
        //       error: function (jqXHR, textStatus, errorThrown) {
        //         reject(errorThrown);
        //       }
        //     })
        //   })
        // }

        $(function() {
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
                    'Bulan kemarin': [moment().subtract('month', 1).startOf('month'), moment().subtract(
                        'month', 1).endOf('month')],
                    'Tahun ini': [moment().subtract('year', 0).startOf('year').startOf('month'), moment()
                        .subtract('year', 0).endOf('year').endOf('month')
                    ],
                    'Tahun kemarin': [moment().subtract('year', 1).startOf('year').startOf('month'),
                        moment().subtract('year', 1).endOf('year').endOf('month')
                    ],
                },
                showDropdowns: true,
                format: 'YYYY-MM-DD',
                startDate: moment().subtract('year', 0).startOf('year').startOf('month'),
                endDate: moment().subtract('year', 0).endOf('year').endOf('month')
            }, cb);


            load_autocomplete_data().then((e) => {
                $('#table').bootstrapTable('refresh');
            }).catch(e => console.log(e));

            $('#cari').click(function() {
                $('#table').bootstrapTable('refresh')
                $('#table-diterima').bootstrapTable('refresh');
                $('#table-ditolak').bootstrapTable('refresh');
            });

            $('#reset').click(function() {
                $('#nama').val('');
                $('#departement_id').val('').trigger('change');
                $('#jabatan_id').val('').trigger('change');
                $('#material_id').val('').trigger('change');
                cb(start, end);
                $('#table').bootstrapTable('refresh');
                $('#table-diterima').bootstrapTable('refresh');
                $('#table-ditolak').bootstrapTable('refresh');
            });

            load_data_ajax('{{ url('home/material-dropdown') }}', 'POST', {}).then((e) => {
                insert_into_select_opt(e, '#material_id', 'id', 'material');
            }).catch(e => console.log(e));
        })

        function cb(start, end) {
            $('#daterangepicker_start').val(start.format('YYYY-MM-DD'));
            $('#daterangepicker_end').val(end.format('YYYY-MM-DD'));
            $('#reportrange span').html(start.format('D MMM YYYY') + ' - ' + end.format('D MMM YYYY'));
        }

        function insert_into_select_opt(data, selector, value_prop, value_text) {
            if (data.length > 0) {
                $(selector).empty();
                $(selector).append('<option value="">-Pilih-</option>');
                $.each(data, function(key, value) {
                    $(selector).append('<option value="' + value[value_prop] + '">' + value[value_text] +
                        '</option>');
                });
            } else {
                $(selector).empty();
                $(selector).append('<option value=""selected>-Data is empty-</option>');
            }
        };

        function load_data_ajax(url, method, params = {}) {
            return new Promise((resolve, reject) => {
                $.ajax({
                    url: url,
                    type: method,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    dataType: "JSON",
                    data: params,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function(data) {
                        resolve(data);
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        reject(errorThrown);
                    }
                })
            })
        }

        function exportToExcel() {
            var nama = $('#nama').val();
            var date_start = $('#daterangepicker_start').val();
            var date_end = $('#daterangepicker_end').val();
            var material_id = $('#material_id').val();
            var url = '{{ url('history-spm/excel') }}?nama=' + nama + '&date_start='+date_start+'&date_end='+date_end+'&material_id='+material_id;

            newtab = window.open('about:blank', '_newtab');
            newtab.location = (url);
        }

    </script>
@endpush
