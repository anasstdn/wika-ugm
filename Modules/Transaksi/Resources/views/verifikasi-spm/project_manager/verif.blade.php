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
						<label for="wizard-progress-nama-depan">Status Verifikasi Project Manager</label>
						<br/>
						@if($data->flag_verif_pm == 'Y')
						<span class="badge badge-success">Disetujui</span>
						@elseif($data->flag_verif_pm == 'N')
						<span class="badge badge-danger">Ditolak</span>
						@else
						<span class="badge badge-danger">Menunggu Verifikasi</span>
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
				<div class="form-row">
					<div class="form-group col-12 text-right">
						<label class="css-control css-control-primary css-checkbox">
							<input type="checkbox" class="css-control-input" id="checkAll">
							<span class="css-control-indicator"></span> Centang Semua
						</label>
					</div>
				</div>
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
								<th>Verifikasi</th>
							</tr>
						</thead>
						<tbody>
							@if(isset($data_detail) && !$data_detail->isEmpty())
							@foreach($data_detail as $key => $value)
							@php
							$material = \DB::table('material')->find($value->material_id);
							@endphp
							<tr>
								<td>{{ $key+1 }}
									<input type="hidden" name="detail_id[{{ $key }}]" id="detail_id_{{ $key }}" value="{{ $value->id }}">
								</td>
								<td>{{ $material->kode_material }} - {{ $material->material }}</td>
								<td>{{ $material->spesifikasi }}</td>
								<td><input type="number" class="form-control form-control-sm" name="volume[]" id="volume_{{ $key }}" step="0.01" min="0" value="{{ $value->volume }}"></td>
								<td>{{ get_jumlah_current_stok($material->id) }}</td>
								<td>{{ $material->satuan }}</td>
								<td>{{ date_indo(date('Y-m-d',strtotime($value->tgl_penggunaan))) }}</td>
								<td>{{ $value->keterangan }}</td>
								<td>
									<label class="css-control css-control-sm css-control-primary css-switch css-switch-square">
										<input type="checkbox" class="css-control-input verif" name="verified[{{ $key }}]" id="verified_{{ $key }}" value="Y">
										<span class="css-control-indicator"></span>
									</label>
								</td>
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
				@if(!empty($data->catatan_komersial))
				<div class="form-row">
					<div class="form-group col-6">
						<label for="wizard-progress-nama-depan">Catatan Komersial</label>
						<textarea class="form-control pengajuan" readonly="">{{ $data->catatan_komersial }}</textarea>
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
						<textarea class="form-control pengajuan" name="catatan_project_manager" id="catatan_project_manager" rows="5"></textarea>
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
						<textarea class="form-control" rows="5" readonly="" style="font-weight: bold">{{  implode("\n\n", $riwayat) }}</textarea>
					</div>
				</div>

				<br/><br/>
				<div class="row">
					<div class="col-12 text-right">
						{{-- <a href="{{ url('/verifikasi-spm/'.$data->id.'/test-pdf') }}" class="btn btn-alt-info">Print</a> --}}
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
		$('#checkAll').change(function(){
			if ($(this).is(':checked')) {
				$('input:checkbox.verif').not(':disabled').prop('checked',true);
			} else {
				$('input:checkbox.verif').prop('checked', false);
			}
		});
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
			var err = 0;
			var atLeastOneIsChecked = false;

			$('input:checkbox.verif').each(function () {
				if ($(this).is(':checked')) {
					atLeastOneIsChecked = true;
					return false;
				}
			});

			if(atLeastOneIsChecked == false)
			{
				err += 1;
				notification('Silahkan verifikasi minimal 1 data.','gagal')
			}
			
			if($(this).valid() && err == 0)
			{
				clicked(e);
			}
			else{
				e.preventDefault();
			}
		});
	}
</script>
@endpush