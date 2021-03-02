@extends('layouts.app')

@section('content')
<div class="bg-image bg-image-bottom" style="background-image: url('{{asset('codebase/')}}/src/assets/media/photos/photo12@2x.jpg');">
	<div class="bg-black-op-75 py-30">
		<div class="content content-top text-left">
			<div class="row push">
				<div class="col-md py-10 d-md-flex align-items-md-center text-center">
					<h1 class="text-white mb-0">
						<span class="font-w300">Manajemen Informasi Pengguna</span>
						&nbsp
						<a href="{{url('profil')}}" class="btn btn-rounded btn-hero btn-sm btn-alt-secondary mb-5">
							<i class="fa fa-arrow-left mr-5"></i> Kembali
						</a>
					</h1>
				</div>
			</div>
			<!-- END Actions -->
		</div>
	</div>
</div>


<div class="content">
	<div class="block block-themed">
		<div class="block-header bg-info">
			<h3 class="block-title"><i class="fa fa-user-circle mr-5 text-muted"></i> Informasi Pribadi</h3>
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
						Silahkan isi form dengan data lengkap. Isian wajib yang terdapat tanda *.
					</p>
				</div>

				<div class="col-lg-7 offset-lg-1">
					<!-- Progress Wizard -->
					<div class="js-wizard-validation-classic block">
						<!-- Step Tabs -->
						<ul class="nav nav-tabs nav-tabs-block nav-fill" role="tablist">
							<li class="nav-item">
								<a class="nav-link active" href="#wizard-progress-data-pribadi" data-toggle="tab">1. Data Pribadi</a>
							</li>
							<li class="nav-item">
								<a class="nav-link" href="#wizard-progress-alamat" data-toggle="tab">2. Alamat</a>
							</li>
							<li class="nav-item">
								<a class="nav-link" href="#wizard-progress-kontak" data-toggle="tab">3. Kontak</a>
							</li>
							<li class="nav-item">
								<a class="nav-link" href="#wizard-progress-foto" data-toggle="tab">4. Foto Profil</a>
							</li>
						</ul>
						<!-- END Step Tabs -->

						<!-- Form -->
						{!! Form::model(null, ['method' => 'put','route' => ['profil.update', $id_profil],'class' => 'js-wizard-validation-classic-form','id'=>'form','files'=>true]) !!}
						{{-- <form class="js-wizard-validation-classic-form" id="form" action="{{ url('profile/update') }}" method="post" enctype='multipart/form-data'>
							{{ csrf_field() }} --}}
							<!-- Wizard Progress Bar -->
							<div class="block-content block-content-sm">
								<div class="progress" data-wizard="progress" style="height: 8px;">
									<div class="progress-bar progress-bar-striped progress-bar-animated bg-primary" role="progressbar" style="width: 30%;" aria-valuenow="30" aria-valuemin="0" aria-valuemax="100"></div>
								</div>
							</div>
							<!-- END Wizard Progress Bar -->

							<!-- Steps Content -->
							<div class="block-content block-content-full tab-content" style="min-height: 265px;">
								<!-- Step 1 -->
								<div class="tab-pane active" id="wizard-progress-data-pribadi" role="tabpanel">
									<div class="form-row">
										<div class="form-group col-6">
											<label for="wizard-progress-nama-belakang">No KTP</label>
											<input class="form-control form-control-sm" type="text" id="nik" name="nik" value="{{ isset($profile->nik) && !empty($profile->nik)?$profile->nik:'' }}">
										</div>
									</div>

									<div class="form-group">
										<label for="wizard-progress-nama-depan">Nama</label>
										<input class="form-control form-control-sm" type="text" id="nama" name="nama" value="{{ isset($profile->nama) && !empty($profile->nama)?$profile->nama:'' }}">
									</div>
									<div class="form-row">
										<div class="form-group col-6">
											<label for="wizard-progress-nama-depan">Tempat Lahir</label>
											<input class="form-control form-control-sm" type="text" id="tempat_lahir" name="tempat_lahir" value="{{ isset($profile->tempat_lahir) && !empty($profile->tempat_lahir)?$profile->tempat_lahir:'' }}">
										</div>

										<div class="form-group col-6">
											<label for="wizard-progress-nama-depan">Tanggal Lahir</label>
											<input class="form-control form-control-sm datepicker" type="text" id="tanggal_lahir" name="tanggal_lahir" value="{{ isset($profile->tgl_lahir) && !empty($profile->tgl_lahir)?date('d-m-Y',strtotime($profile->tgl_lahir)):'' }}">
										</div>
									</div>

									<div class="form-row">
										<div class="form-group col-4">
											<label for="wizard-progress-nama-depan">Jenis Kelamin</label>
											<select class="select2 form-control form-control-sm" name="jenis_kelamin" id="jenis_kelamin" style="width: 100%;" data-placeholder="">
											</select>
										</div>
										<div class="form-group col-4">
											<label for="wizard-progress-nama-depan">Agama</label>
											<select class="select2 form-control form-control-sm" name="agama" id="agama" style="width: 100%;" data-placeholder="">
                							</select>
										</div>
										<div class="form-group col-4">
											<label for="wizard-progress-nama-depan">Status Perkawinan</label>
											<select class="select2 form-control form-control-sm" name="status_perkawinan" id="status_perkawinan" style="width: 100%;" data-placeholder="">
											</select>
										</div>
									</div>
								</div>
								<!-- END Step 1 -->

								<!-- Step 2 -->
								<div class="tab-pane" id="wizard-progress-alamat" role="tabpanel">
									<div class="form-row">
										<div class="form-group col-6">
											<label for="wizard-progress-nama-depan">Alamat KTP</label>
											<textarea class="form-control" id="alamat_ktp" name="alamat_ktp" rows="5">{{ isset($profile->alamat_ktp) && !empty($profile->alamat_ktp)?$profile->alamat_ktp:'' }}</textarea>
										</div>

										<div class="form-group col-6">
											<label for="wizard-progress-nama-depan">Kota / Kabupaten KTP</label>
											<input class="form-control form-control-sm" type="text" id="kota_ktp" name="kota_ktp" value="{{ isset($profile->kota_ktp) && !empty($profile->kota_ktp)?$profile->kota_ktp:'' }}">
										</div>
									</div>
									<div class="form-row">
										<div class="form-group col-6">
											<label for="wizard-progress-nama-depan">Alamat Domisili</label>
											<textarea class="form-control" id="alamat_domisili" name="alamat_domisili" rows="5">{{ isset($profile->alamat_domisili) && !empty($profile->alamat_domisili)?$profile->alamat_domisili:'' }}</textarea>
										</div>

										<div class="form-group col-6">
											<label for="wizard-progress-nama-depan">Kota / Kabupaten Domisili</label>
											<input class="form-control form-control-sm" type="text" id="kota_domisili" name="kota_domisili" value="{{ isset($profile->kota_domisili) && !empty($profile->kota_domisili)?$profile->kota_domisili:'' }}">
										</div>
									</div>
								</div>
								<!-- END Step 2 -->

								<div class="tab-pane" id="wizard-progress-kontak" role="tabpanel">
									<div class="form-group">
										<label for="wizard-progress-email">Nomor Telepon</label>
										<input class="form-control form-control-sm" type="text" id="no_telp" name="no_telp" value="{{ isset($profile->no_telp) && !empty($profile->no_telp)?$profile->no_telp:'' }}">
									</div>
									<div class="form-group">
										<label for="wizard-progress-email">Email</label>
										<input class="form-control form-control-sm" type="email" id="email" name="email" value="{{ isset($profile->email) && !empty($profile->email)?$profile->email:'' }}">
									</div>
									<div class="form-group">
										<label for="wizard-progress-email">User ID Telegram</label>
										<input class="form-control form-control-sm" type="text" id="telegram_id" name="telegram_id" value="{{ isset($profile->telegram_id) && !empty($profile->telegram_id)?$profile->telegram_id:'' }}">
									</div>
								</div>

								<!-- Step 3 -->
								<div class="tab-pane" id="wizard-progress-foto" role="tabpanel">
									<div class="form-group row">
										<div class="col-md-10 col-xl-6">
											<div class="push">
												@if(isset($profile) && $profile->foto!==null)
												<img class="img-avatar" id="uploadPreview" src="{{asset('images/')}}/profile/{{$profile->foto}}" alt="">
												@else
												<img class="img-avatar" id="uploadPreview" src="{{asset('codebase/')}}/src/assets/media/avatars/avatar15.jpg" alt="">
												@endif
											</div>
											<div class="custom-file">
												<!-- Populating custom file input label with the selected filename (data-toggle="custom-file-input" is initialized in Helpers.coreBootstrapCustomFileInput()) -->
												<input type="file" class="custom-file-input" id="profile-settings-avatar" onchange="PreviewImage();" name="profile-settings-avatar" data-toggle="custom-file-input">
												<label class="custom-file-label" for="profile-settings-avatar">Pilh Foto</label>
											</div>
										</div>
									</div>
									<input type="hidden" name="foto_name" id="foto_name" value="{{isset($profile)?isset($profile->foto)?$profile->foto:'':''}}">
								</div>
								<!-- END Step 3 -->
							</div>
							<!-- END Steps Content -->

							<!-- Steps Navigation -->
							<div class="block-content block-content-sm block-content-full bg-body-light">
								<div class="row">
										{{-- <div class="col-6">
											<button type="button" class="btn btn-alt-secondary" data-wizard="prev">
												<i class="fa fa-angle-left mr-5"></i> Previous
											</button>
										</div> --}}
										<div class="col-12 text-right">
											<button type="button" class="btn btn-alt-secondary" data-wizard="prev">
												<i class="fa fa-angle-left mr-5"></i> Sebelumnya
											</button>
											<button type="button" class="btn btn-alt-secondary" data-wizard="next">
												Selanjutnya <i class="fa fa-angle-right ml-5"></i>
											</button>
											<button type="submit" id="simpan" class="btn btn-alt-primary d-none" data-wizard="finish">
												<i class="fa fa-check mr-5"></i> Simpan
											</button>
										</div>
									</div>
								</div>
								<!-- END Steps Navigation -->
							</form>
							<!-- END Form -->
						</div>
						<!-- END Progress Wizard -->
					</div>
				</div>
			</div>
		</div>
	</div>


	@endsection

	@push('js')
	<script>
		initWizardSimple = () => {
			$('.js-wizard-validation-classic').bootstrapWizard({
				onTabShow: (tab, navigation, index) => {
					let percent = ((index + 1) / navigation.find('li').length) * 100;

                // Get progress bar
                let progress = navigation.parents('.block').find('[data-wizard="progress"] > .progress-bar');

                // Update progress bar if there is one
                if (progress.length) {
                	progress.css({ width: percent + 1 + '%' });
                }
            },
            onNext: (tab, navigation, index) => {
            	if(!formClassic.valid()) {
            		validatorClassic.focusInvalid();
            		return false;
            	}
            },
            onTabClick: (tab, navigation, index) => {
            	jQuery('a', navigation).blur();
            	return false;
            }
        });

			let formClassic     = jQuery('.js-wizard-validation-classic-form');

			// Prevent forms from submitting on enter key press
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
				rules: {
					'nama': {
						required: true,
						minlength: 2
					},
					'nik': {
						required: true,
						minlength: 2
					},
					'tempat_lahir': {
						required: true,
						minlength: 2
					},
					'tanggal_lahir': {
						required: true,
						validDate:true,
					},
					'jenis_kelamin': {
						required: true,
					},
					'agama': {
						required: true,
					},
					'status_perkawinan': {
						required: true,
					},
					'alamat_ktp': {
						required: true,
						minlength: 2
					},
					'kota_ktp': {
						required: true,
						minlength: 2
					},
					'alamat_domisili': {
						required: true,
						minlength: 2
					},
					'kota_domisili': {
						required: true,
						minlength: 2
					},
					'email': {
						required: true,
						email: true
					},
					'no_telp': {
						required: true,
						minlength: 1,
						maxlength: 20,
                    // digits: true,
                },
                'telegram_id': {
                	required: false,
                	minlength: 8,
                	maxlength: 12,
                	digits: true,
                }
				},
				messages: {
					'nama': {
						required: 'Silahkan isi form',
						minlength: 'Form diisi minimal 2 karakter'
					},
					'nik': {
						required: 'Silahkan isi form',
						minlength: 'Form diisi minimal 2 karakter'
					},
					'tempat_lahir': {
						required: 'Silahkan isi form',
						minlength: 'Form diisi minimal 2 karakter'
					},
					'tanggal_lahir': {
						required: 'Silahkan isi form',
					},
					'jenis_kelamin': {
						required: 'Silahkan isi form',
					},
					'agama': {
						required: 'Silahkan isi form',
					},
					'status_perkawinan': {
						required: 'Silahkan isi form',
					},
					'telegram_id': {
						minlength: 'Form diisi minimal 8 karakter',
						maxlength: 'Form diisi minimal 12 karakter',
						digits : 'Form hanya diisi angka'
					},
					'email': 'Please enter a valid email address',
				}
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

		}

		initWizardDefaults = () => {
			jQuery.fn.bootstrapWizard.defaults.tabClass         = 'nav nav-tabs';
			jQuery.fn.bootstrapWizard.defaults.nextSelector     = '[data-wizard="next"]';
			jQuery.fn.bootstrapWizard.defaults.previousSelector = '[data-wizard="prev"]';
			jQuery.fn.bootstrapWizard.defaults.firstSelector    = '[data-wizard="first"]';
			jQuery.fn.bootstrapWizard.defaults.lastSelector     = '[data-wizard="lsat"]';
			jQuery.fn.bootstrapWizard.defaults.finishSelector   = '[data-wizard="finish"]';
			jQuery.fn.bootstrapWizard.defaults.backSelector     = '[data-wizard="back"]';
		}

		const load_dropdown_data = async() => {
			load_data_jenis_kelamin = await load_data_ajax('{{ url('profil/load-data-jenis-kelamin') }}','POST').then(function(result) {
				insert_into_select_opt(result,'#jenis_kelamin','id','jenis_kelamin');
			});

			load_data_agama = await load_data_ajax('{{ url('profil/load-data-agama') }}','POST').then(function(result) {
				insert_into_select_opt(result,'#agama','id','agama');
			});

			load_data_status_perkawinan = await load_data_ajax('{{ url('profil/load-data-status-perkawinan') }}','POST').then(function(result) {
				insert_into_select_opt(result,'#status_perkawinan','id','status_perkawinan');
			});

		}

		var id_jenis_kelamin = "{{ isset($profile->jenis_kelamin) && !empty($profile->jenis_kelamin)?$profile->jenis_kelamin:null }}";
		var id_agama = "{{ isset($profile->agama) && !empty($profile->agama)?$profile->agama:null }}";
		var id_status_perkawinan = "{{ isset($profile->status_perkawinan) && !empty($profile->status_perkawinan)?$profile->status_perkawinan:null }}";

		$(function(){
			initWizardDefaults();
			initWizardSimple();

			load_dropdown_data().then((e) => {
				console.log("load data dropdown berhasil");
				if(id_jenis_kelamin !== null)
				{
					$('#jenis_kelamin').val(id_jenis_kelamin).trigger('change');
				}
				if(id_agama !== null)
				{
					$('#agama').val(id_agama).trigger('change');
				}
				if(id_status_perkawinan !== null)
				{
					$('#status_perkawinan').val(id_status_perkawinan).trigger('change');
				}
			}).catch(e => console.log(e));

			$(".select2").select2({
				dropdownParent: $("#form"),
				width: '100%'
			});

			$('.datepicker').datepicker({
				format: "dd-mm-yyyy",
				locale: 'id',
				autoclose: true
			});

			$('#form').submit('#simpan',function(e){
				clicked(e);
			})
		})

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
			// const options = {
			// 	method : method,
			// 	url : url,
			// 	headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
			// 	data: params,
			// 	dataType: "json",
			// 	cache: false,
			// 	contentType: false,
			// 	processData: false,
			// }

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

		function PreviewImage() {
			var oFReader = new FileReader();
			oFReader.readAsDataURL($('#profile-settings-avatar').prop('files')[0]);


			oFReader.onload = function (oFREvent) {
				console.log(oFREvent.target.result);
				document.getElementById("uploadPreview").src = oFREvent.target.result;
			};
		};
	</script>
	@endpush