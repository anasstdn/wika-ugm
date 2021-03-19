@extends('layouts.app')

@section('content')
<div class="bg-primary-dark">
	<div class="content content-top">
		<div class="row push">
			<div class="col-md py-10 d-md-flex align-items-md-center text-center">
				<h1 class="text-white mb-0">
					<span class="font-w300">SPM</span>
					<span class="font-w400 font-size-lg text-white-op d-none d-md-inline-block">Verifikasi Pengajuan SPM</span>
				</h1>
			</div>
		</div>
	</div>
</div>
<div class="bg-white">
	<div class="content">
		<div class="block block-themed">
			<div class="block-header bg-info">
				<h3 class="block-title">Detail Surat Permintaan Material</h3>
				<div class="block-options">
					<button type="button" class="btn-block-option" data-toggle="block-option" data-action="state_toggle" data-action-mode="demo">
						<i class="si si-refresh"></i>
					</button>
					<button type="button" class="btn-block-option" data-toggle="block-option" data-action="content_toggle"></button>
				</div>
			</div>
			{!! Form::model($data, ['method' => 'put','route' => ['verifikasi-spm.update', $data->id],'class' => 'js-wizard-validation-classic-form','id'=>'form','files'=>true]) !!}
			<div class="block-content block-content-full">
				<div class="form-row">
					<div class="form-group col-3">
						<label for="wizard-progress-nama-depan">Nomor Surat Permintaan Material</label>
						<input class="form-control form-control-sm" type="text" id="nama" value="{{ isset($data->no_spm)?$data->no_spm:'' }}" readonly="">
					</div>
					<div class="form-group col-3">
						<label for="wizard-progress-nama-depan">Tanggal SPM</label>
						<input class="form-control form-control-sm" type="text" id="nama" value="{{ isset($data->tgl_spm)?date_indo(date('Y-m-d',strtotime($data->tgl_spm))):'' }}" readonly="">
					</div>
					<div class="form-group col-3">
						<label for="wizard-progress-nama-depan">Pemohon</label>
						<input class="form-control form-control-sm" type="text" id="nama" value="{{ isset($data->nama_pemohon)?$data->nama_pemohon:'' }}" readonly="">
					</div>
					<div class="form-group col-3">
						<label for="wizard-progress-nama-depan">Lokasi</label>
						<input class="form-control form-control-sm" type="text" id="nama" value="{{ isset($data->lokasi)?$data->lokasi:'' }}" readonly="">
					</div>
				</div>
				<div class="form-row">
					<div class="form-group col-3">
						<label for="wizard-progress-nama-depan">Status Verifikasi Komersial</label>
						<br/>
						@if($data->flag_verif_komersial == 'Y')
						<span class="badge badge-success label_verifikasi">Disetujui</span>
						@elseif($data->flag_verif_komersial == 'N')
						<span class="badge badge-danger label_verifikasi">Ditolak</span>
						@else
						<span class="badge badge-danger label_verifikasi">Menunggu Verifikasi</span>
						@endif
					</div>
					<div class="form-group col-3">
						<label for="wizard-progress-nama-depan">User Pembuat</label>
						@php
						$nama = null;
						$pembuat = \DB::table('user_profil')->where('user_id',$data->user_input)->first();

						if(isset($pembuat) && !empty($pembuat->profil_id))
						{
							$profil = \DB::table('profil')->find($pembuat->profil_id);
							$nama = $profil->nama;
						}
						else
						{
							$users = \DB::table('users')->find($data->user_input);
							$nama = $users->name;
						}
						@endphp
						<input class="form-control form-control-sm" type="text" id="nama" value="{{$nama}}" readonly="">
					</div>
				</div>
				<hr/>
				<div class="table-responsive">
					<table class="table table-sm" width="100%">
						<thead>
							<tr>
								<th>No</th>
								<th>Jenis Material</th>
								<th>Spesifikasi</th>
								<th>Jumlah Pengajuan</th>
								<th>Stok Aktual</th>
								<th>Satuan</th>
								<th>Digunakan Tanggal</th>
								<th>Keterangan</th>
							</tr>
						</thead>
						<tbody>
							@if(isset($data_detail) && !$data_detail->isEmpty())
							@foreach($data_detail as $key => $value)
							@php
							$material = \DB::table('material')->find($value->material_id);
							@endphp
							<tr>
								<td>{{ $key+1 }}</td>
								<td>{{ $material->kode_material }} - {{ $material->material }}</td>
								<td>{{ $material->spesifikasi }}</td>
								<td>{{ $value->volume }}</td>
								<td>{{ get_jumlah_current_stok($material->id) }}</td>
								<td>{{ $material->satuan }}</td>
								<td>{{ date_indo(date('Y-m-d',strtotime($value->tgl_penggunaan))) }}</td>
								<td>{{ $value->keterangan }}</td>
							</tr>
							@endforeach
							@endif
						</tbody>
					</table>
				</div>
				<hr/>
				@if(!empty($data->catatan_site_manager))
				<div class="form-row">
					<div class="form-group col-6">
						<label for="wizard-progress-nama-depan">Catatan Site Manager</label>
						<textarea class="form-control pengajuan" readonly="">{{ $data->catatan_site_manager }}</textarea>
					</div>
				</div>
				@endif
				@if(!empty($data->catatan_project_manager))
				<div class="form-row">
					<div class="form-group col-6">
						<label for="wizard-progress-nama-depan">Catatan Project Manager</label>
						<textarea class="form-control pengajuan" readonly="">{{ $data->catatan_project_manager }}</textarea>
					</div>
				</div>
				@endif
				<div class="form-row">
					<div class="form-group col-3">
						<label for="wizard-progress-nama-depan">Pengajuan Diterima / Ditolak</label>
						<select class="form-control pengajuan" name="verifikasi" id="verifikasi">
							<option value="">- Silahkan Pilih -</option>
							<option value="Y">Diterima</option>
							<option value="N">Ditolak</option>
						</select>
					</div>
				</div>
				<div class="form-row">
					<div class="form-group col-6">
						<label for="wizard-progress-nama-depan">Catatan (Tidak Wajib Diisi)</label>
						<textarea class="form-control pengajuan" name="catatan_komersial" id="catatan_komersial" rows="5"></textarea>
					</div>
					<div class="form-group col-6">
						<label for="wizard-progress-nama-depan">Riwayat SPM No {{ isset($data->no_spm)?$data->no_spm:'' }}</label>
							@php
							$riwayat = [];
							@endphp
							@if(isset($riwayat_spm) && !$riwayat_spm->isEmpty())
							@foreach($riwayat_spm as $key => $val)
							@php
							$riwayat[] = ($key+1).". ".date('d-m-Y H:i:s',strtotime($val->created_at)).' '.$val->description['action'];
							@endphp
							@endforeach
							@endif
						<textarea class="form-control" rows="5" readonly="" style="font-size: 9pt;font-weight: bold">{{  implode("\n\n", $riwayat) }}</textarea>
					</div>
				</div>

				<br/><br/>
				<div class="row">
					<div class="col-12 text-right">
						<a href="{{ url('/verifikasi-spm/'.$data->id.'/test-pdf') }}" class="btn btn-alt-info print_pdf" style="display: none" target="_blank">Print PDF</a>
						<a href="{{ url('/verifikasi-spm') }}" class="btn btn-alt-success">Kembali</a>
						<button type="submit" id="simpan" class="btn btn-alt-primary">Simpan</button>
					</div>
				</div>
			</div>
			{!! Form::close() !!}
		</div>
	</div>
</div>
@endsection

@push('js')
<script>
	$(function(){
		initWizardSimple();
	})

		initWizardSimple = () => {
		let formClassic     = jQuery('.js-wizard-validation-classic-form');

		formClassic.on('keyup keypress', e => {
			let code = e.keyCode || e.which;

			if (code === 13) {
				e.preventDefault();
				return false;
			}
		});

		$.validator.addMethod("validDate", function(value, element) {
			return this.optional(element) || moment(value,"DD-MM-YYYY").isValid();
		}, "Format tanggal yang diperbolehkan, exp: DD-MM-YYYY");

		let validatorClassic = formClassic.validate({
			errorClass: 'invalid-feedback animated fadeInDown',
			errorElement: 'div',
			errorPlacement: (error, e) => {
				$(e).parents('.form-group').append(error);
			},
			highlight: e => {
				$(e).closest('.form-group').removeClass('is-invalid').addClass('is-invalid');
			},
			success: e => {
				$(e).closest('.form-group').removeClass('is-invalid');
				$(e).remove();
			},
		});

		$('.pengajuan').each(function(index,element){
			if($(element).attr('id') == 'verifikasi')
			{
				$(element).rules('add', {
					required: true,
					messages: {
						required: "Form Wajib Diisi"
					},
				});
			}

			if($(element).attr('id') == 'catatan_project_manager')
			{
				$(element).rules('add', {
					maxlength: 100,
					minlength: 1,
				});
			}
		})

		$('input').on('focus focusout keyup', function () {
			$(this).valid();
		});

		$("select").on("select2:close", function (e) {  
			$(this).valid(); 
		});

		$(".datepicker").on("change", function (e) {  
			$(this).valid(); 
		});

		$('#form').submit('#simpan',function(e){
			e.preventDefault();
			if($(this).valid())
			{
				// if(confirm('Apakah anda yakin untuk melanjutkan ke proses selanjutnya?')) {

				// 	save_data();
				// }
			}
		});
	}

	save_data = () => {
		var formData = new FormData();
        formData.append('id', {{ $data->id }});
        formData.append('mode', $('#mode').val());
        formData.append('catatan_komersial', $('#catatan_komersial').val());
        formData.append('verifikasi', $('#verifikasi').val());
        formData.append('_token', '{{csrf_token()}}');

        Codebase.layout('header_loader_on');

        $('#simpan').html('<i class="fa fa-circle-o-notch fa-spin"><i>').prop('disabled',true);

        $.ajax({
            url: '{{url('verifikasi-spm/verifikasi-komersil')}}',
            type: 'POST',
            data: formData,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            xhr: function () {
                var xhr = new window.XMLHttpRequest();
                xhr.upload.addEventListener("progress",
                    uploadProgressHandler,
                    false
                    );
                xhr.addEventListener("load", loadHandler, false);
                xhr.addEventListener("error", errorHandler, false);
                xhr.addEventListener("abort", abortHandler, false);

                return xhr;
            },
            cache: false,
            contentType: false,
            processData: false,
            success:function(data){

                if(data.status==true)
                {
                    notification(data.msg,'sukses');
                    $('#simpan').html('Simpan').prop('disabled',false);
                    Codebase.layout('header_loader_off');
                    $('#simpan').fadeOut();

                    setTimeout(function(){
                    	if(data.print_button == true)
                    	{
                    		$('.print_pdf').fadeIn();
                    		$('.label_verifikasi').removeClass('badge-danger').addClass('badge-success');
							$('.label_verifikasi').html('Disetujui');
                    	}
                    	else
                    	{
							$('.label_verifikasi').html('Ditolak');
                    	}
                     }, 2000);
                }
                else
                {
                    Codebase.layout('header_loader_off');
                    notification(data.msg,'gagal');
                }

            },
            error:function (xhr, status, error)
            {
                Codebase.layout('header_loader_off');
                notification(xhr.responseText,'gagal');
            },
        }); 
	}
</script>
@endpush