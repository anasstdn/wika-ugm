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
        <table id="tblAppendGrid"></table>
         <hr/>
  <button id="load" type="button" class="btn btn-primary">Load Data</button>
        <div class="row gutters-tiny push">
            <div class="col-6 col-md-4 col-xl-2">
                <a class="block block-rounded block-bordered block-link-shadow text-center" href="javascript:void(0)">
                    <div class="block-content">
                        <p class="mt-5">
                            <i class="si si-home fa-3x text-muted"></i>
                        </p>
                        <p class="font-w600">Home</p>
                    </div>
                </a>
            </div>
            <div class="col-6 col-md-4 col-xl-2">
                <a class="block block-rounded block-bordered block-link-shadow ribbon ribbon-primary text-center" href="javascript:void(0)">
                    <div class="ribbon-box">5</div>
                    <div class="block-content">
                        <p class="mt-5">
                            <i class="si si-envelope fa-3x text-muted"></i>
                        </p>
                        <p class="font-w600">Inbox</p>
                    </div>
                </a>
            </div>
            <div class="col-6 col-md-4 col-xl-2">
                <a class="block block-rounded block-bordered block-link-shadow text-center" href="javascript:void(0)">
                    <div class="block-content">
                        <p class="mt-5">
                            <i class="si si-bag fa-3x text-muted"></i>
                        </p>
                        <p class="font-w600">Cart</p>
                    </div>
                </a>
            </div>
            <div class="col-6 col-md-4 col-xl-2">
                <a class="block block-rounded block-bordered block-link-shadow text-center" href="javascript:void(0)">
                    <div class="block-content">
                        <p class="mt-5">
                            <i class="si si-bar-chart fa-3x text-muted"></i>
                        </p>
                        <p class="font-w600">Statistics</p>
                    </div>
                </a>
            </div>
            <div class="col-6 col-md-4 col-xl-2">
                <a class="block block-rounded block-bordered block-link-shadow ribbon ribbon-primary text-center" href="javascript:void(0)">
                    <div class="ribbon-box">3</div>
                    <div class="block-content">
                        <p class="mt-5">
                            <i class="si si-cloud-download fa-3x text-muted"></i>
                        </p>
                        <p class="font-w600">Downloads</p>
                    </div>
                </a>
            </div>
            <div class="col-6 col-md-4 col-xl-2">
                <a class="block block-rounded block-bordered block-link-shadow text-center" href="javascript:void(0)">
                    <div class="block-content">
                        <p class="mt-5">
                            <i class="si si-docs fa-3x text-muted"></i>
                        </p>
                        <p class="font-w600">Documents</p>
                    </div>
                </a>
            </div>
        </div>
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
                        <h3 class="block-title text-uppercase">Sales</h3>
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
                        <canvas class="js-chartjs-corporate-lines"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="block block-rounded block-bordered">
                    <div class="block-header">
                        <h3 class="block-title text-uppercase">Earnings</h3>
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
                        <canvas class="js-chartjs-corporate-lines2"></canvas>
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
                          <div class="form-group col-12 text-right">
                             <button id="add_keluarga" type="button" class="btn btn-primary">Tambah Keluarga</button>
                         </div>
                     </div>
                     <table id="table-list-keluarga"  class="table borderless table-hover table-striped table-bordered w-full">
                        <thead>
                            <tr>
                                <th class="text-center">No</th>
                                <th class="text-center">Nama</th>
                                <th class="text-center">Hubungan Keluarga</th>
                                <th class="text-center">Pekerjaan</th>
                                <th class="text-center">Tanggal Lahir</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="table-list-keluarga-body">
                        </tbody>
                    </table>
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
    $.fn.hasId = function(id) {
      return this.attr('id') == id;
  };
    const table_list_pegawai = $('#table-list-keluarga');
    let flag_new_table = true;
    $(function(){
        $('#add_keluarga').click(()=>{
            if(flag_new_table){
                flag_new_table = false;
            }
            add_row_to_table(table_list_pegawai);
        })

        $("#table-list-keluarga-body").on( "click", ".delete-keluarga-btn", function(e) {

            let row = $(this).closest('tr');
            remove_row_table(table_list_pegawai,row);

        });
    })

    const add_row_to_table = (table) => {
        row = `
        <tr class="table-new-row-keluarga" data-row-id-pegawai = "">
        <th class="text-center" style="text-align:center">
        <span class="isi number"></span>
        </th>
        <th class="text-left">
        <input name="pegawai[]" id="pegawai_" class="form-control isi" value="" type="text" autocomplete="on">
        </th>
        <th class="text-center">
        <select class="form-control select isi" id="id_hubungan_keluarga_" name="id_hubungan_keluarga[]" data-plugin="select2">
        <optgroup label="Hubungan Keluarga">
        <option value="">-Pilih-</option>
        @if(isset($hubungan_keluarga_select) && !$hubungan_keluarga_select->isEmpty)
        @foreach($hubungan_keluarga_select as $a)
        <option value="<?php echo $a->id?>"><?php echo $a->hubungan_keluarga ?></option>
        @endforeach
        @endif
        </optgroup>
        </select>
        </th>
        <th class="text-left">
        <select class="form-control select keluar isi" id="id_profesi_" name="id_profesi[]" data-plugin="select2">
        <optgroup label="Profesi">
        <option value="">-Pilih-</option>
        @if(isset($pekerjaan_select) && !$pekerjaan_select->isEmpty())
        @foreach($pekerjaan_select as $a)
        <option value="<?php echo $a->id?>"><?php echo $a->nama_profesi ?></option>
        @endforeach
        @endif
        </optgroup>
        </select>
        </th>
        <th class="text-left">
        <input name="tanggal_lahir[]" style="text-align:right;" id="tanggal_lahir_'+count+'" class="form-control isi" value="" type="text" autocomplete="on">
        </th>
        <th class="text-center">
        <button type="button" class="btn btn-danger btn-icon btn-xs delete-keluarga-btn" aria-label="Left Align">
        <span class="glyphicon glyphicon-trash" aria-hidden="true" data-id-pegawai=""></span>
        </button>
        </th>
        </tr>
        `;

            let tbody = table.find("tbody").attr('id'); // tbody of table
            $('#'+tbody).append(row);
            updateRows();
        }

        const remove_row_table = (table,row = null) => {

            if(row){
                let tbody = row.parent();
                row.remove();
                if(tbody.children().length < 1) {
                    flag_new_table = true;
                } else {
                    updateRows();
                }
            } else {
                let row = table.find("tbody>tr");
                row.remove();
            }
        }

        function updateRows(){
            $('#table-list-keluarga tbody tr').each( (tr_index,tr) =>{
                $(tr).children('th').each( (th_index, th) => {
                    $(th).find('.number').html(tr_index + 1);
                });    
            });
        };
</script>
@endpush