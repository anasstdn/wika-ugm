@extends('layouts.app')

@section('content')
<div class="bg-primary-dark">
	<div class="content content-top">
		<div class="row push">
			<div class="col-md py-10 d-md-flex align-items-md-center text-center">
				<h1 class="text-white mb-0">
					<span class="font-w300">Masterdata</span>
					<span class="font-w400 font-size-lg text-white-op d-none d-md-inline-block">Tambah Pegawai</span>
				</h1>
			</div>
		</div>
	</div>
</div>

<div class="content">
	<div class="block block-themed">
		<div class="block-header bg-info">
			<h3 class="block-title"><i class="fa fa-user-circle mr-5 text-muted"></i> Informasi Kepegawaian</h3>
			<div class="block-options">
				<button type="button" class="btn-block-option" data-toggle="block-option" data-action="state_toggle" data-action-mode="demo">
					<i class="si si-refresh"></i>
				</button>
				<button type="button" class="btn-block-option" data-toggle="block-option" data-action="content_toggle"></button>
			</div>
		</div>
		<div class="block-content">
			<div class="row items-push">
				<div class="col-lg-3">
					<p class="text-muted">
						Silahkan isi form dengan data lengkap.
					</p>
				</div>
				<div class="col-lg-8 offset-lg-1">
					@if(isset($data) && !empty($data))
					{!! Form::model($data, ['method' => 'put','route' => ['pegawai.update', $data->id],'class' => 'js-validation form','id'=>'form','files'=>true]) !!}
					@else
					{!! Form::open(array('route' => 'pegawai.store','method'=>'POST','class' => 'js-validation form','id'=>'form','files'=>true)) !!}
					@endif
					<div class="form-row">
						<div class="form-group col-4">
							<label for="wizard-progress-nama-belakang">Pencarian Pegawai</label>
							<select class="form-control select2" id="profil_id" name="profil_id">
								{{-- @if($kecamatan->exists && !empty($kecamatan->id_kabupaten))
								<option value="{{ $kabupaten->id }}">{{ $kabupaten->kabupaten }}, {{ $kabupaten->provinsi->provinsi }}</option>
								@endif --}}
							</select>
						</div>
					</div>
					<div class="form-row">
						<div class="form-group col-4">
							<label for="wizard-progress-nama-depan">Nama</label>
							<input class="form-control form-control-sm" type="text" id="nama" readonly>
						</div>

						<div class="form-group col-4">
							<label for="wizard-progress-nama-depan">No KTP</label>
							<input class="form-control form-control-sm" type="text" id="nik" readonly>
						</div>
						<div class="form-group col-4">
							<label for="wizard-progress-nama-depan">Tempat / Tanggal Lahir</label>
							<input class="form-control form-control-sm" type="text" id="tempat_lahir" readonly>
						</div>
					</div>
					<div class="form-row">
						<div class="form-group col-4">
							<label for="wizard-progress-nama-depan">Jenis Kelamin</label>
							<input class="form-control form-control-sm" type="text" id="jenis_kelamin" readonly>
						</div>

						<div class="form-group col-4">
							<label for="wizard-progress-nama-depan">Agama</label>
							<input class="form-control form-control-sm" type="text" id="agama" readonly>
						</div>
						<div class="form-group col-4">
							<label for="wizard-progress-nama-depan">Status Perkawinan</label>
							<input class="form-control form-control-sm" type="text" id="status_perkawinan" readonly>
						</div>
					</div>
					<h3 class="block-title">
						<i class="fa fa-briefcase mr-5 text-muted"></i> Informasi Pegawai
					</h3>
					<hr/>
					<div class="form-row">
						<div class="form-group col-4">
							<label for="wizard-progress-nama-depan">NIP</label>
							<input class="form-control form-control-sm" type="text" id="nip" name="nip">
						</div>
						<div class="form-group col-4">
							<label for="wizard-progress-nama-depan">Jabatan</label>
							<select class="select form-control form-control-sm" name="jabatan_id" id="jabatan_id" style="width: 100%;" data-placeholder="">
							</select>
						</div>
						<div class="form-group col-4">
							<label for="wizard-progress-nama-depan">Departement</label>
							<select class="select form-control form-control-sm" name="departement_id" id="departement_id" style="width: 100%;" data-placeholder="">
							</select>
						</div>
					</div>
					<div class="form-row">
						<div class="form-group col-4">
							<label for="wizard-progress-nama-depan">Tanggal Bergabung</label>
							<input class="form-control form-control-sm datepicker" type="text" id="tgl_bergabung" name="tgl_bergabung">
						</div>
						<div class="form-group col-4">
							<label for="wizard-progress-nama-depan">Status Resign</label>
							<select class="select form-control form-control-sm" name="status_resign" id="status_resign" style="width: 100%;" data-placeholder="">
								<option value="Y">Ya</option>
								<option value="N" selected="">Tidak</option>
							</select>
						</div>
						<div class="form-group col-4 resign" style="display: none">
							<label for="wizard-progress-nama-depan">Tanggal Resign</label>
							<input class="form-control form-control-sm datepicker" type="text" id="tgl_resign" name="tgl_resign">
						</div>
					</div>
					<div class="form-group row" style="margin-top: 2em">
						<div class="col-12 text-right">
							<a href="{{ url('/pegawai') }}" class="btn btn-alt-success">Kembali</a>
							<button type="submit" id="simpan" class="btn btn-alt-primary">Simpan</button>
						</div>
					</div>
					{!! Form::close() !!}
				</div>
			</div>
		</div>
	</div>
</div>
@endsection

@push('js')
<script type="text/javascript">
	const load_autocomplete_data = async() => {
		tgl_resign();

		$('#status_resign').on('change',function(e){
			tgl_resign();
		})

		$("#profil_id").select2({
			dropdownParent: $("#form"),
			width: '100%',
			minimumInputLength: 2,
			ajax: {
				url: '{{ url('pegawai/profil-search') }}',
				dataType: 'json',
			},
		});

		$('#profil_id').on("select2:select", function(e) { 
			var profil_id = $('#profil_id').val();
			var formData = new FormData();
			formData.append('profil_id', profil_id);
			load_data_pegawai('{{ url('pegawai/load-profil') }}','POST',formData);
		});

		load_data_jabatan = await load_data_ajax('{{ url('pegawai/load-data-jabatan') }}','POST').then(function(result) {
			insert_into_select_opt(result,'#jabatan_id','id','jabatan');
		});

		load_data_departement = await load_data_ajax('{{ url('pegawai/load-data-departement') }}','POST').then(function(result) {
			insert_into_select_opt(result,'#departement_id','id','departement');
		});


	}

	function tgl_resign()
	{
		if($('#status_resign').val() == 'Y')
		{
			$('.resign').fadeIn();
		}
		else
		{
			$('.resign').fadeOut();
		}
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
					console.log(data);
					resolve(data);
				},
				error: function (jqXHR, textStatus, errorThrown) {
					reject(errorThrown);
				}
			})
		})
	}

	function load_data_pegawai(url,method, params = {})
	{
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
				$('#nama').val(data.nama);
				$('#nik').val(data.nik);
				$('#tempat_lahir').val(data.tempat_lahir);
				$('#jenis_kelamin').val(data.jenis_kelamin);
				$('#agama').val(data.agama);
				$('#status_perkawinan').val(data.status_perkawinan);
			},
			error: function (jqXHR, textStatus, errorThrown) {
				console.log(errorThrown);
			}
		})
	}

	$(function(){

		let formClassic     = $('#form');

		formClassic.on('keyup keypress', e => {
			let code = e.keyCode || e.which;

			if (code === 13) {
				e.preventDefault();
				return false;
			}
		});

		$(".select").select2({
			dropdownParent: $("#form"),
			width: '100%'
		});

		$('.datepicker').datepicker({
			format: "dd-mm-yyyy",
			locale: 'id',
			autoclose: true
		});

		load_autocomplete_data().then((e) => {
			// console.log('aaaaa');
		}).catch(e => console.log(e));

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
			rules: {
				'profil_id': {
					required: true,
				},
				'nip': {
					required: true,
					minlength: 2
				},
				'jabatan_id': {
					required: true,
				},
				'departement_id': {
					required: true,
				},
				'tgl_bergabung': {
					required: true,
					validDate:true,
				},
				'status_resign': {
					required: true,
				},
			},
			messages: {
				'profil_id': {
					required: 'Silahkan isi form',
				},
				'nip': {
					required: 'Silahkan isi form',
					minlength: 'Form diisi minimal 2 karakter'
				},
				'jabatan_id': {
					required: 'Silahkan isi form',
				},
				'departement_id': {
					required: 'Silahkan isi form',
				},
				'tgl_bergabung': {
					required: 'Silahkan isi form',
				},
				'status_resign': {
					required: 'Silahkan isi form',
				},
			},
		});

		$('input').on('focus focusout keyup', function () {
			$(this).valid();
		});

		$("select").on("select2:close", function (e) {  
			$(this).valid(); 
		});

		$(".datepicker").on("change", function (e) {  
			$(this).valid(); 
		});
	})
</script>
@endpush