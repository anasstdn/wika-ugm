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
	<div class="block">
		<div class="block-header block-header-default">
			<h3 class="block-title">
				<i class="fa fa-user-circle mr-5 text-muted"></i> Informasi Pribadi
			</h3>
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
						<form class="js-wizard-validation-classic-form" action="#" method="post">
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
											<input class="form-control form-control-sm" type="text" id="nik" name="nik">
										</div>
									</div>

									<div class="form-group">
										<label for="wizard-progress-nama-depan">Nama</label>
										<input class="form-control form-control-sm" type="text" id="nama" name="nama">
									</div>
									<div class="form-row">
										<div class="form-group col-6">
											<label for="wizard-progress-nama-depan">Tempat Lahir</label>
											<input class="form-control form-control-sm" type="text" id="tempat_lahir" name="tempat_lahir">
										</div>

										<div class="form-group col-6">
											<label for="wizard-progress-nama-depan">Tanggal Lahir</label>
											<input class="form-control form-control-sm datepicker" type="text" id="tanggal_lahir" name="tanggal_lahir">
										</div>
									</div>

									<div class="form-row">
										<div class="form-group col-4">
											<label for="wizard-progress-nama-depan">Jenis Kelamin</label>
											<input class="form-control form-control-sm" type="text" id="tempat_lahir" name="tempat_lahir">
										</div>
										<div class="form-group col-4">
											<label for="wizard-progress-nama-depan">Agama</label>
											<input class="form-control form-control-sm datepicker" type="text" id="tanggal_lahir" name="tanggal_lahir">
										</div>
										<div class="form-group col-4">
											<label for="wizard-progress-nama-depan">Status Perkawinan</label>
											<input class="form-control form-control-sm datepicker" type="text" id="tanggal_lahir" name="tanggal_lahir">
										</div>
									</div>
								</div>
								<!-- END Step 1 -->

								<!-- Step 2 -->
								<div class="tab-pane" id="wizard-progress-alamat" role="tabpanel">
									<div class="form-group">
										<label for="wizard-progress-bio">Bio</label>
										<textarea class="form-control" id="wizard-progress-bio" name="wizard-progress-bio" rows="9"></textarea>
									</div>
								</div>
								<!-- END Step 2 -->

								<div class="tab-pane" id="wizard-progress-kontak" role="tabpanel">
									<div class="form-group">
										<label for="wizard-progress-email">Nomor Telepon</label>
										<input class="form-control form-control-sm" type="email" id="wizard-progress-email" name="wizard-progress-email">
									</div>
									<div class="form-group">
										<label for="wizard-progress-email">Email</label>
										<input class="form-control form-control-sm" type="email" id="wizard-progress-email" name="wizard-progress-email">
									</div>
								</div>

								<!-- Step 3 -->
								<div class="tab-pane" id="wizard-progress-foto" role="tabpanel">
									<div class="form-group">
										<label for="wizard-progress-location">Location</label>
										<input class="form-control" type="text" id="wizard-progress-location" name="wizard-progress-location">
									</div>
									<div class="form-group">
										<label for="wizard-progress-skills">Skills</label>
										<select class="form-control" id="wizard-progress-skills" name="wizard-progress-skills" size="1">
											<option value="">Please select your best skill</option>
											<option value="1">Photoshop</option>
											<option value="2">HTML</option>
											<option value="3">CSS</option>
											<option value="4">JavaScript</option>
										</select>
									</div>
									<div class="form-group">
										<label class="css-control css-control-primary css-switch" for="wizard-progress-terms">
											<input type="checkbox" class="css-control-input" id="wizard-progress-terms" name="wizard-progress-terms">
											<span class="css-control-indicator"></span> Agree with the terms
										</label>
									</div>
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
											<button type="submit" class="btn btn-alt-primary d-none" data-wizard="finish">
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
					'wizard-validation-classic-email': {
						required: true,
						email: true
					},
					'wizard-validation-classic-bio': {
						required: true,
						minlength: 5
					},
					'wizard-validation-classic-location': {
						required: true
					},
					'wizard-validation-classic-skills': {
						required: true
					},
					'wizard-validation-classic-terms': {
						required: true
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
					'wizard-validation-classic-email': 'Please enter a valid email address',
					'wizard-validation-classic-bio': 'Let us know a few thing about yourself',
					'wizard-validation-classic-skills': 'Please select a skill!',
					'wizard-validation-classic-terms': 'You must agree to the service terms!'
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

		$(function(){
			initWizardDefaults();
			initWizardSimple();

			$(".select2").select2({
				dropdownParent: $("#personal"),
				width: '100%'
			});

			$('.datepicker').datepicker({
				format: "dd-mm-yyyy",
				locale: 'id',
				autoclose: true
			});
		})
	</script>
	@endpush